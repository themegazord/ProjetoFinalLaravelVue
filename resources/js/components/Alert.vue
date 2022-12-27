<template>
  <div :class="style" role="alert">
    {{ titulo }}
    <hr v-if="responseApi.data.id || responseApi.data.errors">
    <span v-if="responseApi.data.id">{{'ID do Registro: ' + responseApi.data.id}}</span>
    <ul v-if="responseApi.data.errors">
      <ol v-for="(e, key) in responseApi.data.errors" :key="key">
        {{ e[0] }}
      </ol>
    </ul>
  </div>
</template>

<script>
export default {
  props: ["tipo", "responseApi", "titulo"],
  computed: {
    style() {
      return "alert alert-" + this.tipo;
    },
  },
};
</script>

<style>
</style>
