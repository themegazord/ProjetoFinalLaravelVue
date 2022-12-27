<template>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col" v-for="t, key in titulos" :key="key">{{t}}</th>
        <th v-if="visualizar.visivel || deletar.visivel || atualizar.visivel"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="m in data" :key="m.id">
        <td scope="row">{{m.id}}</td>
        <td>{{m.nome}}</td>
        <td><img :src="'/storage/' + m.imagem" alt="{{m.nome}}" style="width: 50px; height: 50px;"></td>
        <td v-if="visualizar.visivel || deletar.visivel || atualizar.visivel">
            <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm me-md-2" :data-bs-toggle="visualizar.dataToggle" :data-bs-target="visualizar.dataTarget" @click="setStore(m)">Visualizar</button>
            <button v-if="atualizar.visivel" class="btn btn-outline-primary btn-sm me-md-2" :data-bs-toggle="atualizar.dataToggle" :data-bs-target="atualizar.dataTarget" @click="setStore(m)">Atualizar</button>
            <button v-if="deletar.visivel" class="btn btn-outline-danger btn-sm me-md-2" :data-bs-toggle="deletar.dataToggle" :data-bs-target="deletar.dataTarget" @click="setStore(m)">Remover</button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  props: ["data", "titulos", "atualizar", 'visualizar', 'deletar'],
  methods: {
    setStore(obj) {
        this.$store.state.transacao.status = '';
        this.$store.state.transacao.mensagem = '';
        this.$store.state.item = obj
    }
  }
};
</script>

<style>
</style>
