<template>
  <div id="InfoTable" class="col-12">
    <div>
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
        <li class="nav-item">
          <a :class="ajoutDonneesActive" href="#" @click="ajouterDesDonnees()">Ajouter des données</a>
        </li>
      </ul>
    </div>
    <div v-if="lodding" class="text-center">
      <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
    <StructureTable v-if="structureActive == navActive" :table="$route.params.table"></StructureTable>
  </div>
</template>


<script>
import MessageErreur from "./MessageErreur.vue";
import store from "./../store/store.js";
import vuex from "vuex";
import StructureTable from "./StructureTable.vue";
export default {
  store,
  components: {
    MessageErreur,
    StructureTable
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
      ajoutDonneesActive: "nav-link",
      messageErreur: ""
    };
  },
  props: {
    structureTable: Object
  },
  methods: {
    show() {
      this.$modal.show(this.nameModal);
    },
    hide() {
      this.$modal.hide(this.nameModal);
    },
    ajouterDesDonnees() {
      this.ajoutDonneesActive = this.navActive;
      this.DonneesActive = this.navDesactive;
      this.structureActive = this.navDesactive;
    },
    afficheStructureTable() {
      this.ajoutDonneesActive = this.navDesactive;
      this.DonneesActive = this.navDesactive;
      this.structureActive = this.navActive;
    },
    afficheDonnees() {
      this.ajoutDonneesActive = this.navDesactive;
      this.DonneesActive = this.navActive;
      this.structureActive = this.navDesactive;
    }
  }
};
</script>

<style lang="">
</style>