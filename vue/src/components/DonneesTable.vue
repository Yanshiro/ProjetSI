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

    <AjoutDonnees :structureTable="structureTable"></AjoutDonnees>
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
      controller: "TableController"
    };
  },
  components: {
    AjoutDonnees
  },
  mounted() {
    this.chargementDonneesTable();
    this.chargementStructureTable();
  },
  methods: {
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