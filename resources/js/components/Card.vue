<template>
  <div class="card mb-5">
    <div class="card-header">{{ titulo }}</div>
    <div class="card-body">
      <slot name="conteudo">

      </slot>
    </div>

    <div class="card-footer d-grid gap-2 d-flex justify-content-between">
      <slot name="rodape">
      </slot>
    </div>
  </div>
</template>

<script>
export default {
  props: ["titulo"],
};
</script>

<style>
</style>
