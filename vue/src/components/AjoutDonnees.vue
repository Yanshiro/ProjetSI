<template>
  <div id="AjoutDonnees">
    <h2>Ajouter des données {{this.$route.params.table}}</h2>

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
      <div class="form-group" v-for="(col) in structureTable">
        <label>{{col.Field}}</label>
        <input type="text" :placeholder="col.Field" class="form-control">
      </div>
    </form>
    <button class="btn btn-success">Ajouter les données</button>
  </div>
</template>

<script>
export default {
  props: {
    structureTable: Array
  },
  data() {
    return {
      // Nombre de données a afficher
      nbLigne: 1,
      arrayForm: []
    };
  },
  methods: {
    addForm(nbLigne) {
      this.arrayForm = [];
      for (var i = 0; i < nbLigne; i++) {
        this.arrayForm.push("");
      }
    }
  }
};
</script>

<style>
.DivNbDataAouter {
  margin-top: 2em;
}
</style>