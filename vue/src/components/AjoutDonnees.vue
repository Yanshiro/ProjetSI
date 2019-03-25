<template>
  <div id="AjoutDonnees">
    <h2>Ajouter des données {{this.$route.params.table}}</h2>
    <AddDonneesCSV :structureTable="structureTable" v-if="structureTable.length > 0"></AddDonneesCSV>
    <!-- donnees csv -->
    <div class="form-group DivNbDataAouter">
      <label for="nbDataAouter">Nombre de données a ajouter</label>
      <input
        id="nbDataAouter"
        class="form-control"
        :input="addForm(nbLigne)"
        v-model="nbLigne"
        type="number"
      >
    </div>

    <form action v-for="(val, index) in arrayForm" :key="index">
      <h1>Ajout données {{index + 1}}</h1>
      <hr>
      <div class="form-group" v-for="(col, indexColonne) in structureTable" :key="indexColonne">
        <label>
          {{col.Field}}
          <p v-if="col.Null == 'NO'">(Champ obligatoire{{col.Extra == "" ? "" : ", " + col.Extra}})</p>
          <p v-else>(Champs pas obligatoire {{col.Extra == "" ? "jnlk" : col.Extra}})</p>
        </label>
        <input
          v-if="col.Extra != 'auto_increment' && col.Key != 'MUL'"
          :type="verifType(col.Type)"
          :placeholder="col.Field"
          class="form-control"
          @input="loadDataInForm(index, col.Field, $event.target.value)"
        >
        <select
          class="form-control"
          @input="loadDataInForm(index, col.Field, $event.target.value)"
          v-else-if="col.Key == 'MUL'"
        >
          <option>Choisir une valeur</option>
          <option
            :value="prop.id"
            v-for="(prop, index) in chargeDataClefEtranger(col)"
            :key="index"
          >{{prop}}</option>
        </select>
        <input
          v-else
          disabled
          :type="verifType(col.Type)"
          name
          :placeholder="col.Field"
          class="form-control"
        >
      </div>
      <button
        @click.prevent="AjouteUneDonneesBdd(arrayForm[index])"
        class="btn btn-success"
      >Ajouter les données</button>
    </form>
  </div>
</template>

<script>
import store from "./../store/store.js";
import vuex from "vuex";
import AddDonneesCSV from "./AddDonneesCSV";

export default {
  store,
  props: {
    structureTable: Array,
    arrayCleEtranger: Array
  },
  data() {
    return {
      // Nombre de données a afficher
      nbLigne: 1,
      arrayForm: [],
      arrayTableValeurColonne: [],
      controller: "TableController"
    };
  },
  components: {
    AddDonneesCSV
  },
  computed: {
    ...vuex.mapGetters(["data"])
  },
  methods: {
    // Ajout des ligne
    addForm(nbLigne) {
      if (nbLigne != "") {
        for (var i = this.arrayForm.length; i < nbLigne; i++) {
          this.arrayForm.push({});
          var v = {};

          // Creationd de l'objet en fonction de la structure de la table
          for (var e in this.structureTable) {
            v[this.structureTable[e].Field] = "";
          }

          this.arrayForm[i] = v;
        }

        for (var b = this.arrayForm.length; b > nbLigne; b--) {
          this.arrayForm.splice(b - 1, 1);
        }
      }
    },
    // chargement des données dans le tableau d'ajout a la base de données
    loadDataInForm(index, colonne, value) {
      let obj = this.arrayForm[index];
      obj[colonne] = value;
      this.arrayForm[index] = obj;
    },
    // Verification des type de champs input
    verifType(type) {
      var int = /[int]/;
      if (type.match(int)) {
        return "number";
      }
      return "text";
    },
    // Chargement des valeurs de clef etranger
    chargeDataClefEtranger(col) {
      return this.arrayCleEtranger.filter(e => e.colonne.Champ1 == col.Field)[0]
        .value;
    },
    // Ajouter une donnees dans la base de données
    AjouteUneDonneesBdd(dataForm) {
      var data = {};
      var data2 = {};
      data["table"] = this.$route.params.table;

      var donnees = [];
      for (var key in dataForm) {
        data2[key] = dataForm[key];
      }
      donnees.push(data2);
      data["data"] = donnees;

      this.AjouteDonneAjax(data);
    },
    /**
     * Appel ajax pour l'ajout des donnees
     */
    AjouteDonneAjax(data) {
      /**
       * data [ table, data [{obj1}, {obj2}]]
       */
      var dataSend = new FormData();
      dataSend.append("data", JSON.stringify(data));
      this.$http
        .post(
          this.$urlApi + "?controller=" + this.controller + "&f=addData",
          dataSend,
          "json"
        )
        .then(response => {
          this.arrayForm.splice(this.arrayForm.indexOf(data), 1);
          var e = response.data;
          store.commit("setData", e);
          this.nbLigne--;
        })
        .catch(error => {
          this.$parent.messageErreur = error.body;
        });
    }
  }
};
</script>

<style>
.DivNbDataAouter {
  margin-top: 2em;
}
</style>