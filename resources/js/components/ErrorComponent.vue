<template>
  <div>
    <div v-if="errors.length" class="alert alert-danger" role="alert">
      <ul>
        <li v-for="error in errors" :key="error">{{ error }}</li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ["errors"],
  methods: {
    cleanError() {
      this.error = [];
    },
  },
};
</script>
