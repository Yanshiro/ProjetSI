<template>
  <div id="StructureTable">
    <modal :name="nameModal" height="80%" width="70%">
      <div class="container">
        <div class="row">
          <div class="col-12 titreModal">
            <MessageErreur
              :message="messageErreur"
              v-if="typeof(messageErreur) != 'undefined' && messageErreur != ''"
            ></MessageErreur>
            <h2>Ajouter une colonne</h2>
          </div>
          <form action class="col-12" method="post">
            <div class="form-group">
              <label for>Nom de la colonne</label>
              <input
                v-model="formAddColonne.colonne"
                type="text"
                class="form-control"
                placeholder="Nom de la colonne"
                name="Column"
              >
            </div>
            <div class="form-group">
              <label for>Type autorisé : varchar(255), int, datatype...</label>
              <input
                v-model="formAddColonne.type"
                type="text"
                class="form-control"
                placeholder="Type"
                name="Type"
              >
            </div>
            <div class="form-group">
              <label for>Valeur par default</label>
              <input
                v-model="formAddColonne.default"
                type="text"
                class="form-control"
                placeholder="Default"
                name="Default"
              >
            </div>
            <div class="form-group">
              <label for>Champs nullable</label>
              <select v-model="formAddColonne.nullable" class="form-control" name="Nullable" id>
                <option value="NULL">Autoriser</option>
                <option value="NOT NULL">Interdie</option>
              </select>
            </div>
            <button class="btn btn-success" @click.prevent="ValideForm()">Valider</button>
            <button class="btn btn-danger" type="reset" @click="hideModal()">Annuler</button>
          </form>
        </div>
      </div>
    </modal>

    <table class="table center">
      <thead>
        <tr>
          <th>Nom de la colonne</th>
          <th>Type</th>
          <th>Nullable</th>
          <th>Default</th>
          <th>Key</th>
          <th>Extra</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(col, index) in structureTable" :key="index">
          <td>{{col.Field}}</td>
          <td>{{col.Type}}</td>
          <td>{{col.Null}}</td>
          <td>{{col.Default}}</td>
          <td>{{col.Key}}</td>
          <td>{{col.Extra}}</td>
          <td>
            <button class="btn btn-info">Modification</button>
            <button class="btn btn-danger" @click="suprimerChamps(col)">Suppression</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="col-12">
      <div class="form-inline">
        <button type="button" class="btn btn-primary" v-on:click="addChamps()">Ajouter un champs</button>
      </div>
    </div>
  </div>
</template>

<script>
import store from "./../store/store.js";
import MessageErreur from "./MessageErreur.vue";
export default {
  store,
  components: { MessageErreur },
  data() {
    return {
      controller: "TableController",
      structureTable: [],
      nameModal: "addChampsTable",
      formAddColonne: {
        colonne: "",
        type: "",
        nullable: "",
        default: ""
      }
    };
  },
  props: {
    messageErreur: String,
    table: String
  },
  mounted() {
    this.chargementStructureTable();
  },
  methods: {
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
    addChamps() {
      this.$modal.show(this.nameModal);
    },
    suprimerChamps(champs) {
      let data = new FormData();
      data.append("table", this.$route.params.table);
      data.append("Column", champs.Field);
      let url =
        this.$urlApi + "?controller=" + this.controller + "&f=suprimerColonne";
      this.$http
        .post(url, data, {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
          }
        })
        .then(response => {
          if (response.body == "true")
            this.structureTable.splice(this.structureTable.indexOf(champs), 1);
        })
        .catch(error => {
          this.$parent.messageErreur = error.body;
        });
    },
    ValideForm() {
      let data = new FormData();
      data.append("table", this.$route.params.table);
      data.append("Column", this.formAddColonne.colonne);
      data.append("Type", this.formAddColonne.type);
      data.append("Nullable", this.formAddColonne.nullable);
      data.append("Default", this.formAddColonne.default);
      let url =
        this.$urlApi +
        "?controller=" +
        this.controller +
        "&f=addColonneInTable";
      this.$http
        .post(url, data, {
          headers: {
            "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
          }
        })
        .then(response => {
          this.structureTable.push(response.data);
          this.hideModal();
        })
        .catch(error => {
          this.messageErreur = error.body;
        });
    },
    hideModal() {
      this.formAddColonne.colonne = "";
      this.formAddColonne.type = "";
      this.formAddColonne.nullable = "";
      this.formAddColonne.default = "";
      this.$modal.hide(this.nameModal);
    }
  }
};
</script>