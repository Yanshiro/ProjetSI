<template>
  <div id="MessageErreur">
    <div
      class="alert alert-danger alert-dismissible fade show"
      role="alert"
      v-if="typeof(message) != 'undefined' && message != ''"
    >
      <strong>{{message}}</strong>
      <button
        v-on:click="suppertionMessage()"
        class="close"
        type="button"
        data-dismiss="alert"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    message: String
  },
  methods: {
    suppertionMessage() {
      this.$parent.messageErreur = "";
    }
  }
};
</script>

<style >
button {
  margin-bottom: 0.5em;
  padding: 0;
}
.alert-dismissible .close {
  padding: 0;
}
</style>