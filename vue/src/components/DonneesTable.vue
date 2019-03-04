<template>
  <div id="DonnneesTable">
    <table class="table center">
      <thead>
        <tr>
          <th v-for="(col, index) in structureTable" :key="index">{{col.Field}}</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(data, col) in Data" :key="col">
          <td v-for="(colonne, index) in structureTable" :key="index">{{data[colonne.Field]}}</td>
          <td>
            <button class="btn btn-info">Modification</button>
            <button class="btn btn-danger" @click="suppressionData(data)">Suppression</button>
          </td>
        </tr>
      </tbody>
    </table>

    <AjoutDonnees :arrayCleEtranger="arrayCleEtranger" :structureTable="structureTable"></AjoutDonnees>
  </div>
</template>


<script>
import store from "./../store/store.js";
import AjoutDonnees from "./AjoutDonnees.vue";
export default {
  store,
  data() {
    return {
      Data: [],
      structureTable: [],
      arrayCleEtranger: [],
      controller: "TableController"
    };
  },
  components: {
    AjoutDonnees
  },
  mounted() {
    this.chargementStructureTable();
    this.chargementDonneesTable();
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
          this.Data = response.data;
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
          this.Data.splice(this.Data.indexOf(colonne), 1);
        })
        .catch(error => {
          this.$parent.messageErreur = error.body;
        });
    }
  }
};
</script>

<style lang="fr">
</style>