<template>
  <div id="Tables" class="col-12">
    <div class="col-12">
      <div class="form-inline">
        <div class="form-group col-6">
          <input
            class="form-control col-12"
            type="text"
            v-model="nameAddTable"
            placeholder="Ajouter une table"
          >
        </div>
        <button type="button" class="btn btn-primary" v-on:click="addTable()">Ajouter une table</button>
      </div>
    </div>
    <div class="col-12">
      <MessageErreur :message="messageErreur"></MessageErreur>
    </div>
    <table class="table" v-if="lodding == false">
      <thead>
        <tr>
          <td>id</td>
          <td>nom de la table</td>
          <td>action</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="table in tables">
          <td>{{table.id}}</td>
          <td>{{table.table}}</td>
          <td>
            <router-link
              class="btn btn-info"
              :to="{name: 'infoTable', params: {table : table.table}}"
              tag="button"
            >info table</router-link>
            <button class="btn btn-warning" @click="deleteTable(table)">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="lodding" class="text-center">
      <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script>
import MessageErreur from "./MessageErreur.vue";
import store from "./../store/store.js";
import vuex from "vuex";
export default {
  store,
  name: "Tables",
  components: { MessageErreur },
  computed: {
    ...vuex.mapGetters(["lodding"])
  },
  data() {
    return {
      controller: "TableController",
      tables: [],
      nameAddTable: "",
      messageErreur: ""
    };
  },
  mounted() {
    store.commit("changeLodding", true);
    this.$http
      .get(this.$urlApi + "?controller=" + this.controller + "&f=getAllTable")
      .then(response => {
        this.tables = response.data;
        store.commit("changeLodding", false);
      })
      .catch(error => {
        alert(error);
      });
  },
  methods: {
    addTable() {
      let data = new FormData();
      data.append("table", this.nameAddTable);
      this.appelAjax(
        this.$urlApi + "?controller=" + this.controller + "&f=addTable",
        data
      );
    },
    appelAjax(url, data) {
      this.$http
        .post(url, data, {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
          }
        })
        .then(response => {
          this.tables.push(response.data);
        })
        .catch(error => {
          this.messageErreur = error.body;
        })
        .then(() => {
          this.nameAddTable = "";
        });
    },
    deleteTable(table) {
      let data = new FormData();
      data.append("table", table.table);
      let url =
        this.$urlApi + "?controller=" + this.controller + "&f=deleteTable";
      this.$http
        .post(url, data, {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
          }
        })
        .then(() => {
          this.tables.splice(this.tables.indexOf(table), 1);
        })
        .catch(error => {
          this.messageErreur = error.body;
        });
    }
  }
};
</script>

<style >
button {
  margin: 0.9em;
  margin-top: 1em;
}

.form-inline {
  margin-bottom: 2em;
}
</style>