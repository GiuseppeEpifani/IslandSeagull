<template>
  <div>
    <div class="d-flex justify-content-center" v-if="loading">
      <div
        class="spinner-border mt-5 h3"
        style="width: 5rem; height: 5rem"
        role="status"
      ></div>
    </div>
    <div class="container" v-show="!loading">
      <form @submit.prevent="validateForm">
        <error :errors="errors" ref="listError" />
        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="nameKind">Tipo</label>
            <input
              type="text"
              class="form-control"
              id="nameKind"
              v-model="content.kind"
            />
          </div>
        </div>
        <button
          type="submit"
          class="btn btn-color-form btn-lg btn-block mt-3"
          id="btn-enviar-update-kind"
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
export default {
  props: ["instanceDataTable"],
  data() {
    return {
      errors: [],
      content: {
        id: "",
        kind: "",
      },
      sending: false,
      loading: true,
    };
  },
  methods: {
    getKind(id) {
      this.loading = true;
      axios
        .get(`/kind/${id}/edit`)
        .then((res) => {
          this.content.id = res.data.id;
          this.content.kind = res.data.name;
          this.loading = false;
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    cleanField() {
      this.cleanForm();
      let name = document.getElementById("nameKind");
      this.removeClassBorder(name);
    },
    validateForm() {
      this.errors = [];
      let name = document.getElementById("nameKind");
      if (!this.content.kind) {
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
      document.getElementById("btn-enviar-update-kind").disabled = true;
      this.sending = true;
    },
    sendForm() {
      let formData = new FormData();
      formData.append("_method", "PUT");
      formData.append("name", this.content.kind);
      axios
        .post(`/kind/${this.content.id}`, formData)
        .then((res) => {
          this.cleanForm();
          this.finishLoading(res.data);
          $("#modalEdit").modal("toggle");
          this.instanceDataTable.ajax.reload();
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    cleanForm() {
      this.errors = [];
      this.$refs.listError.cleanError();
      this.content.id = "";
      this.content.kind = "";
    },
    finishLoading(res) {
      document.getElementById("btn-enviar-update-kind").disabled = false;
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
          text: "Tipo editado.",
          icon: "success",
          confirmButtonText: "Esta bien",
        });
      }
    },
  },
};
</script>
