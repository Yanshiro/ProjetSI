<template>
  <div id="InfoTable" class="col-12">
    <div class="navInfoTable">
      <h2>Info sur la table : {{$route.params.table}}</h2>
      <MessageErreur :message="messageErreur"></MessageErreur>
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
          <a
            :class="structureActive"
            href="#"
            @click="afficheStructureTable()"
          >Structure de la table</a>
        </li>
        <li class="nav-item">
          <a :class="DonneesActive" href="#" @click="afficheDonnees()">Données de la table</a>
        </li>
      </ul>
    </div>
    <div v-if="lodding" class="text-center">
      <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
    <StructureTable v-if="structureActive == navActive" :table="$route.params.table"></StructureTable>
    <DonneesTable v-if="DonneesActive == navActive"></DonneesTable>
  </div>
</template>


<script>
import MessageErreur from "./MessageErreur.vue";
import store from "./../store/store.js";
import vuex from "vuex";
import StructureTable from "./StructureTable.vue";
import DonneesTable from "./DonneesTable";
export default {
  store,
  components: {
    MessageErreur,
    StructureTable,
    DonneesTable
  },
  computed: {
    ...vuex.mapGetters(["lodding"])
  },
  data() {
    return {
      nameModal: "add_contenue_tab",
      navDesactive: "nav-link",
      navActive: "nav-link active",
      structureActive: "nav-link active",
      DonneesActive: "nav-link",
      messageErreur: ""
    };
  },
  props: {
    structureTable: Object
  },
  methods: {
    afficheStructureTable() {
      this.DonneesActive = this.navDesactive;
      this.structureActive = this.navActive;
    },
    afficheDonnees() {
      this.DonneesActive = this.navActive;
      this.structureActive = this.navDesactive;
    }
  }
};
</script>

<style>
.navInfoTable {
  margin-bottom: 4em;
}
.titreModal {
  margin-top: 1em;
  margin-bottom: 1em;
}
</style>