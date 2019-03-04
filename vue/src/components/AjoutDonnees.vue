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
      <div class="form-group" v-for="(col, index) in structureTable" :key="index">
        <label>
          {{col.Field}}
          <p v-if="col.Null == 'NO'">(Champ obligatoire{{col.Extra == "" ? "" : ", " + col.Extra}})</p>
          <p v-else>(Champs pas obligatoire {{col.Extra == "" ? "jnlk" : col.Extra}})</p>
        </label>
        <input
          v-if="col.Extra != 'auto_increment'"
          :type="verifType(col.Type)"
          :placeholder="col.Field"
          class="form-control"
          @input="loadDataInForm(index, col.Field, $event.target.value)"
        >
        <input
          v-else
          disabled
          :type="verifType(col.Type)"
          name
          :placeholder="col.Field"
          class="form-control"
        >
      </div>
      <button class="btn btn-success">Ajouter les données</button>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    structureTable: Array,
    arrayCleEtranger: Array
  },
  data() {
    return {
      // Nombre de données a afficher
      nbLigne: 1,
      arrayForm: []
    };
  },
  mounted() {
    for (var e in this.arrayCleEtranger) {
      for (var i in this.structureTable) {
        if (e.colonne == i.Field) {
          this.structureTable.splice(i, 1);
        }
      }
    }
  },
  methods: {
    addForm(nbLigne) {
      this.arrayForm = [];
      for (var i = 0; i < nbLigne; i++) {
        this.arrayForm.push({});
      }
    },
    loadDataInForm(index, colonne, value) {
      let obj = this.arrayForm[index];
      obj[colonne] = value;
      this.arrayForm[index] = obj;
    },
    verifType(type) {
      var int = /[int]/;
      if (type.match(int)) {
        return "number";
      }
      return "text";
    }
  }
};
</script>

<style>
.DivNbDataAouter {
  margin-top: 2em;
}
</style>