<template>
  <div id="AddDonneesCSV">
    <div class="importExel">
      <VueCsvImport
        :inputClass="'fiche de contrôle de formulaire'"
        :loadBtnText="'Soumettre'"
        v-model="parseCsv"
        @input="loadDataCSV(parseCsv)"
        :map-fields="structureTable.filter(e => e.Extra != 'auto_increment' && e.Type != 'MUL').map(e => e.Field)"
      ></VueCsvImport>
      <div v-if="filterParse.length  > 0">
        <button class="btn btn-primary" v-on:click="loadDataCSV(parseCsv)">Réinitialiser</button>
        <button class="btn btn-success" @click="AddData()">Ajouter dans la base de données</button>
        <button class="btn btn-danger" @click="annulerImport()">Annuler l'import csv</button>
        <div class="form-group">
          <input type="text" class="form-control" placeholder="barre de recherche" v-model="search">
          <small
            id="helpId"
            class="text-muted"
          >Ce champ permet juste de vérifier les résultats de l'import</small>
        </div>
        <hr>
        <div class="form-check" v-if="doublon">
          <input
            @click="deleteDoublon()"
            class="form-check-input"
            type="checkbox"
            value
            id="defaultCheck2"
          >
          <label
            class="form-check-label"
            for="defaultCheck2"
            @click="deleteDoublon()"
          >Enlever les doublons</label>
        </div>
        <h1>Aperçu de l'import csv</h1>
        <small>Resultat Total: {{nombreResultat()}} | Nombre de data selectionner pour l'enregistrement {{nbChecked}}</small>
        <table class="table">
          <thead>
            <th
              v-for="(item, index) in structureTable.filter(e => e.Extra != 'auto_increment' && e.Type != 'MUL')"
              :key="index"
            >
              <div style="display: block;" class="box">
                <p>
                  {{item.Field}}
                  <a href="#" @click.prevent.stop="changeFleche(item)">
                    <svg
                      v-if="item.trie == true"
                      id="i-caret-bottom"
                      viewBox="0 0 32 32"
                      width="22"
                      height="22"
                      fill="none"
                      stroke="currentcolor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                    >
                      <path d="M30 10 L16 26 2 10 Z"></path>
                    </svg>
                    <svg
                      v-else
                      id="i-caret-top"
                      viewBox="0 0 32 32"
                      width="22"
                      height="22"
                      fill="none"
                      stroke="currentcolor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                    >
                      <path d="M30 22 L16 6 2 22 Z"></path>
                    </svg>
                  </a>
                </p>
              </div>
            </th>
            <th>
              Ajout dans la database
              <input type="checkbox" @input="changeCheckedForAllData()">
            </th>
          </thead>
          <tbody>
            <tr v-for="(i2, index) in filterDataCSV" :key="index">
              <td v-for="(data, col, index2) in i2" :key="index2">
                <p v-if="col != 'checked'">{{data}}</p>
                <input
                  v-else
                  type="checkbox"
                  :checked="data == true ? 'checked' : ''"
                  @input="changeChecked(i2)"
                >
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>


<script>
import VueCsvImport from "vue-csv-import";
import store from "./../store/store.js";
import vuex from "vuex";

export default {
  props: {
    structureTable: Array
  },
  mounted() {
    this.structureTable.filter(e => (e["trie"] = false));
  },
  data() {
    return {
      nbChecked: 0,
      checked: false,
      search: "",
      parseCsv: [],
      filterParse: [],
      tabNonTrie: [],
      doublon: true,
      controller: "TableController"
    };
  },
  computed: {
    ...vuex.mapGetters(["data"]),

    filterDataCSV: {
      get() {
        var array;
        return this.filterParse.filter(e => {
          array = Object.keys(e);
          for (var v in array) {
            try {
              if (
                e[array[v]].toLowerCase().includes(this.search.toLowerCase())
              ) {
                return true;
              }
            } catch {
              return false;
            }
          }
          return false;
        });
      }
    }
  },
  components: {
    VueCsvImport
  },
  methods: {
    loadDataCSV(value) {
      value = this.videLigneVideFichierCSV(value);
      this.filterParse = value;
      this.doublon = true;
      for (var e in this.filterParse) {
        this.filterParse[e]["checked"] = false;
      }
    },
    changeChecked(object) {
      this.filterParse[this.filterParse.indexOf(object)].checked =
        this.filterParse[this.filterParse.indexOf(object)].checked == true
          ? false
          : true;
      this.calculNbChecked();
    },
    changeCheckedForAllData() {
      this.checked = this.checked == true ? false : true;
      this.filterDataCSV.filter(e => (e.checked = this.checked));
      this.$forceUpdate();
      this.calculNbChecked();
    },
    initParameter() {
      this.parameter;
    },
    nombreResultat() {
      return this.filterDataCSV.length;
    },
    calculNbChecked() {
      this.nbChecked = this.filterParse.filter(e => e.checked == true).length;
    },

    /**
     * trie des données, est change la fleche de trie
     */
    changeFleche(item) {
      item.trie = item.trie == true ? false : true;
      if (item.trie == true) {
        this.tabNonTrie = this.filterParse;
        this.filterParse = this.filterParse
          .filter(e => e[item.Field])
          .sort(function(a, b) {
            if (a[item.Field] < b[item.Field]) return -1;
            else if (a[item.Field] == b[item.Field]) return 0;
            else return 1;
          });
      } else {
        this.filterParse = this.tabNonTrie;
      }
    },
    /**
     * Supprimer les lignes vides du fichier csv
     */
    videLigneVideFichierCSV(value) {
      return value.filter(e => {
        for (var v in e) {
          try {
            if (e[v].length > 0) {
              return true;
            }
          } catch {
            return false;
          }
        }
        return false;
      });
    },
    deleteDoublon() {
      var uniqueArray = this.filterParse.filter((obj1, index) => {
        return (
          index ===
          this.filterParse.findIndex(obj2 => {
            return (
              JSON.stringify(obj2).toLowerCase() ===
              JSON.stringify(obj1).toLowerCase()
            );
          })
        );
      });
      this.filterParse = uniqueArray;
      this.doublon = false;

      this.tabNonTrie = this.tabNonTrie.filter((obj1, index) => {
        return (
          index ===
          this.tabNonTrie.findIndex(obj2 => {
            return (
              JSON.stringify(obj2).toLowerCase() ===
              JSON.stringify(obj1).toLowerCase()
            );
          })
        );
      });
    },
    AddData() {
      var array = this.filterParse.filter(e => e.checked == true).slice();
      array.forEach(function(v) {
        delete v.checked;
      });

      var data = {};
      data["table"] = this.$route.params.table;
      data["data"] = array;
      var dataSend = new FormData();
      dataSend.append("data", JSON.stringify(data));
      this.$http
        .post(
          this.$urlApi + "?controller=" + this.controller + "&f=addData",
          dataSend,
          "json"
        )
        .then(response => {
          var e = response.data;
          store.commit("setData", e);
          this.annulerImport();
        })
        .catch(error => {
          this.$parent.$parent.messageErreur = error.body;
        });
    },
    annulerImport() {
      this.filterParse = [];
      this.parseCsv = [];
    }
  }
};
</script>

<style>
</style>
  