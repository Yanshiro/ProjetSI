<template>
  <div id="StructureTable">
    <modal :name="nameModal" height="80%" width="70%">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h2>Nom du champs : {{nameAddTable}}</h2>
          </div>
          <form action class="col-12">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Default" name="Default">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Extra" name="Extra">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="column" name="column">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Nullable" name="Nullable">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Key" name="Key">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Type" name="Type">
            </div>
            <button class="btn btn-success" @click="hideModal()">Valider</button>
            <button class="btn btn-danger" type="reset" @click="hideModal()">Annuler</button>
          </form>
        </div>
      </div>
    </modal>

    <div class="col-12">
      <div class="form-inline">
        <div class="form-group col-6">
          <input
            class="form-control col-12"
            type="search"
            v-model="nameAddTable"
            placeholder="Ajouter un champs"
          >
        </div>
        <button type="button" class="btn btn-primary" v-on:click="addChamps()">Ajouter un champs</button>
      </div>
    </div>
    <table class="table center">
      <thead>
        <tr>
          <th>Default</th>
          <th>Extra</th>
          <th>Nom de la column</th>
          <th>Nullable</th>
          <th>Key</th>
          <th>Type</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="col in structureTable">
          <td>{{col.Default}}</td>
          <td>{{col.Extra}}</td>
          <td>{{col.Field}}</td>
          <td>{{col.Key}}</td>
          <td>{{col.Null}}</td>
          <td>{{col.Type}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import store from "./../store/store.js";
import vuex from "vuex";

export default {
  store,
  components: {
    lodding: true
  },
  data() {
    return {
      controller: "TableController",
      structureTable: [],
      nameAddTable: "",
      nameModal: "addChampsTable"
    };
  },
  props: {
    table: String
  },
  mounted() {
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
  methods: {
    addChamps() {
      this.$modal.show(this.nameModal);
    },
    hideModal() {
      this.nameModal = null;
      this.$modal.hide(this.nameModal);
    }
  }
};
</script>