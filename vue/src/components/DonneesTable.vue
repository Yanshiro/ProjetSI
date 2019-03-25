<template>
  <div id="DonnneesTable">
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
            <button class="btn btn-info">Modification</button>
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
      finChargementCleEtranger: false
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
      this.arrayCleEtranger = this.structureTable.filter(e => e.Key == "MUL");
      let url =
        this.$urlApi +
        "?controller=" +
        this.controller +
        "&f=chargeValueEtranger";

      let data = new FormData();
      data.append("table", this.$route.params.table);
      data.append("colonnes", JSON.stringify(this.arrayCleEtranger));

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
    }
  }
};
</script>

<style lang="fr">
</style>