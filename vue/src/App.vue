<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin-bottom: 2em;">
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <router-link class="nav-link" :to="{name: 'Listetable'}">Liste des tables</router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Ajouter des données</a>
          </li>
        </ul>
        <form class="form-inline">
          <input
            class="form-control mr-sm-2"
            type="search"
            placeholder="Recherche"
            aria-label="Search"
            v-model="rechercheTable"
          >
          <router-link
            class="btn btn-outline-info my-2 my-sm-0"
            tag="button"
            :to="{name: 'infoTable', params: {table : rechercheTable}}"
            v-on:click.native="resetRecherche()"
          >Rechercher une table</router-link>
        </form>
      </div>
    </nav>
    <div class="container">
      <div class="row">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "app",
  data() {
    return {
      rechercheTable: ""
    };
  },
  components: {},
  methods: {
    resetRecherche() {
      this.rechercheTable = "";
    }
  }
};
</script>