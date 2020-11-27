<template>
  <div>
    <div class="container">
      <form @submit.prevent="validateForm">
        <error :errors="errors" ref="listError" />
        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="name">Tipo</label>
            <input type="text" class="form-control" id="name" v-model="kind" />
          </div>
        </div>
        <button
          type="submit"
          class="btn btn-color-form btn-lg btn-block mt-3"
          id="btn-enviar-add-kind"
          style="height: 50px"
        >
          <span v-if="!sending">Agregar</span>
          <span v-if="sending">
            Agregando...
            <div class="spinner-border" role="status"></div>
          </span>
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import EventBus from "../../bus";
export default {
  props: ["instanceDataTable"],
  data() {
    return {
      errors: [],
      kind: "",
      sending: false,
    };
  },
  methods: {
    emitKindAdded(kind) {
      EventBus.$emit("ADDED_KIND", kind);
    },
    cleanField() {
      this.cleanForm();
      let name = document.getElementById("name");
      this.removeClassBorder(name);
    },
    validateForm(e) {
      this.errors = [];
      let name = document.getElementById("name");
      if (!this.kind) {
        this.errors.push("Ingrese el tipo.");
        name.className += " border-danger";
      } else {
        this.removeClassBorder(name);
        this.loadingForm();
        this.sendForm();
        return true;
      }
    },
    removeClassBorder(nameClass) {
      nameClass.classList.remove("border-danger");
    },
    loadingForm() {
      this.errors = [];
      document.getElementById("btn-enviar-add-kind").disabled = true;
      this.sending = true;
    },

    sendForm() {
      let formData = new FormData();
      formData.append("name", this.kind);
      this.cleanForm();
      axios
        .post("/kind", formData)
        .then((res) => {
          this.finishLoading(res.data);
          if (res.data != false) {
            this.emitKindAdded(res.data);
            this.instanceDataTable.ajax.reload();
          }
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    cleanForm() {
      this.errors = [];
      this.$refs.listError.cleanError();
      this.selectedKind = "";
      this.kind = "";
    },
    finishLoading(res) {
      document.getElementById("btn-enviar-add-kind").disabled = false;
      this.sending = false;
      if (res === false) {
        Swal.fire({
          title: "Tipo existente",
          text: "El tipo ya esta registrado.",
          icon: "error",
          confirmButtonText: "Esta bien",
        });
      } else {
        Swal.fire({
          title: "Publicado",
          text: "Tipo pulicada.",
          icon: "success",
          confirmButtonText: "Esta bien",
        });
      }
    },
  },
};
</script>

<style>
</style>