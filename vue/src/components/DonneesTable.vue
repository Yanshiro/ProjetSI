<template>
  <div id="DonnneesTable">
    <modal
      v-if="objectModif != {}"
      :adaptive="true"
      :draggable="true"
      height="auto"
      :scrollable="true"
      :name="nameModal"
    >
      <div class="container">
        <div class="row">
          <div class="col 12">
            <form>
              <div
                class="form-group"
                v-for="(col, indexColonne) in structureTable"
                :key="indexColonne"
              >
                <label>
                  {{col.Field}}
                  <p
                    v-if="col.Null == 'NO'"
                  >(Champ obligatoire{{col.Extra == "" ? "" : ", " + col.Extra}})</p>
                  <p v-else>(Champs pas obligatoire {{col.Extra == "" ? "jnlk" : col.Extra}})</p>
                </label>
                <input
                  v-if="col.Extra != 'auto_increment' && col.Key != 'MUL'"
                  :type="$getTypeChampsInputBySQL(col.Type)"
                  :placeholder="col.Field"
                  class="form-control"
                  :value="objectModif[col.Field]"
                  v-model="objectModif[col.Field]"
                >
                <select
                  class="form-control"
                  v-else-if="col.Key == 'MUL' && arrayCleEtranger.length > 0 "
                  v-model="objectModif[col.Field]"
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
                  :type="$getTypeChampsInputBySQL(col.Type)"
                  name
                  :placeholder="col.Field"
                  class="form-control"
                  v-model="objectModif[col.Field]"
                  :value="objectModif[col.Field]"
                >
              </div>
              <button
                @click.prevent="modifDonnes(objectModif)"
                class="btn btn-success"
              >Modifier la donnée</button>
              <button @click.prevent="hideModal()" class="btn btn-danger">Fermer la modal</button>
            </form>
          </div>
        </div>
      </div>
    </modal>
    <table class="table center" v-if="finChargementCleEtranger == true">
      <thead>
        <tr>
          <th v-for="(col, index) in structureTable" :key="index">{{col.Field}}</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(dataDonne, col) in data" :key="col">
          <td v-for="(colonne, index) in structureTable" :key="index">
            <p v-if="colonne.Key != 'MUL'">{{dataDonne[colonne.Field]}}</p>
            <p v-else>{{chargeDataEtranger(colonne.Field, dataDonne[colonne.Field])}}</p>
          </td>
          <td>
            <button class="btn btn-info" @click="modification(dataDonne)">Modification</button>
            <button class="btn btn-danger" @click="suppressionData(dataDonne)">Suppression</button>
          </td>
        </tr>
      </tbody>
    </table>
    <AjoutDonnees
      v-if="finChargementCleEtranger == true"
      :arrayCleEtranger="arrayCleEtranger"
      :structureTable="structureTable"
    ></AjoutDonnees>
  </div>
</template>


<script>
import store from "./../store/store.js";
import vuex from "vuex";
import AjoutDonnees from "./AjoutDonnees.vue";

export default {
  store,
  data() {
    return {
      structureTable: [],
      arrayCleEtranger: [],
      controller: "TableController",
      finChargementCleEtranger: false,
      nameModal: "ModificationModal",
      objectModif: {}
    };
  },
  computed: {
    ...vuex.mapGetters(["data"])
  },
  components: {
    AjoutDonnees
  },
  mounted() {
    this.chargementDonneesTable();
    this.chargementStructureTable();
  },
  methods: {
    chargementLienTable() {
      // this.arrayCleEtranger = this.structureTable.filter(e => e.Key == "MUL");
      let cleEtranger = this.structureTable.filter(e => e.Key == "MUL");
      let url =
        this.$urlApi +
        "?controller=" +
        this.controller +
        "&f=chargeValueEtranger";

      let data = new FormData();
      data.append("table", this.$route.params.table);
      data.append("colonnes", JSON.stringify(cleEtranger));

      this.$http
        .post(url, data, {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
          }
        })
        .then(response => {
          this.arrayCleEtranger = response.data;
          this.finChargementCleEtranger = true;
        })
        .catch(error => {
          this.messageErreur = error.body;
        });
    },
    chargementDonneesTable() {
      store.commit("changeLodding", true);
      this.$http
        .get(
          this.$urlApi +
            "?controller=" +
            this.controller +
            "&f=getDonnees&table=" +
            this.$route.params.table
        )
        .then(response => {
          store.commit("setData", response.data);
        })
        .catch(error => {
          this.$parent.messageErreur = error.body;
        })
        .then(() => {
          store.commit("changeLodding", false);
        });
    },
    chargementStructureTable() {
      store.commit("changeLodding", true);
      this.$http
        .get(
          this.$urlApi +
            "?controller=" +
            this.controller +
            "&f=afficheStructure&table=" +
            this.$route.params.table
        )
        .then(response => {
          this.structureTable = response.data;
          this.chargementLienTable();
        })
        .catch(error => {
          this.$parent.messageErreur = error.body;
        })
        .then(() => {
          store.commit("changeLodding", false);
        });
    },
    suppressionData(colonne) {
      let data = new FormData();
      data.append("id", colonne.id);
      data.append("table", this.$route.params.table);
      this.$http
        .post(
          this.$urlApi + "?controller=" + this.controller + "&f=deletData",
          data
        )
        .then(() => {
          store.commit("removeData", colonne);
        })
        .catch(error => {
          this.$parent.messageErreur = error.body;
        });
    },
    /**
     * Chargement de la valeur table etranger
     */
    chargeDataEtranger(colonne, valeur) {
      let indexTable = this.arrayCleEtranger.filter(
        e => e.colonne.Champ1 == colonne
      )[0];
      let val = indexTable.value.filter(
        e => e[indexTable.colonne.Champ2] == valeur
      );
      return val;
    },
    modification(dataModification) {
      this.objectModif = dataModification;
      this.$modal.show(this.nameModal);
    },
    // Modifie la données dans la base de données
    modifDonnes(data) {
      let dataSend = new FormData();
      dataSend.append("data", JSON.stringify(data));
      dataSend.append("table", this.$route.params.table);
      this.$http
        .post(
          this.$urlApi + "?controller=" + this.controller + "&f=updateData",
          dataSend
        )
        .then(response => {
          store.commit("setData", response.data);
        })
        .catch(error => {
          this.$parent.messageErreur = error.body;
        })
        .then(() => {
          this.hideModal();
        });
    },

    // Chargement des valeurs de clef etranger
    chargeDataClefEtranger(col) {
      return this.arrayCleEtranger.filter(e => e.colonne.Champ1 == col.Field)[0]
        .value;
    },
    hideModal() {
      this.$modal.hide(this.nameModal);
      this.objectModif = {};
    }
  }
};
</script>

<style lang="fr">
</style>