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
        <div class="form-group">
          <label for="title">Titulo</label>
          <input
            type="text"
            class="form-control"
            id="title"
            v-model="content.title"
          />
        </div>
        <div class="form-group">
          <label for="descripcion">Descripción</label>
          <textarea
            class="form-control"
            id="descripcion"
            v-model="content.description"
            rows="3"
            style="resize: none"
          ></textarea>
        </div>
        <dropzone-update
          v-on:obtain-files="onFiles"
          v-on:delete-files="onDeleteFiles"
          ref="dropzoneUpdate"
        />
        <button
          type="submit"
          class="btn btn-color-form btn-lg btn-block mt-3"
          id="btn-enviar"
          style="height: 50px"
        >
          <span v-if="!sending">Subir</span>
          <span v-if="sending">
            Subiendo...
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
  props: ["user_id", "instanceDataTable"],
  data() {
    return {
      errors: [],
      content: {
        id: "",
        title: "",
        description: "",
        file: [],
      },
      sending: false,
      loading: true,
    };
  },
  methods: {
    onFiles(files) {
      this.content.file.push(files);
    },
    onDeleteFiles(file) {
      let index = this.content.file.indexOf(file);
      this.content.file.splice(index, 1);
    },
    getContent(id) {
      this.loading = true;
      this.content.file = [];
      axios
        .get(`/content/${id}/edit`)
        .then((res) => {
          this.content.id = res.data.id;
          this.content.title = res.data.title;
          this.content.description = res.data.description;
          this.$refs.dropzoneUpdate.setFiles(res.data.images);
          this.loading = false;
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    cleanContent() {
      this.cleanForm();
      let title = document.getElementById("title");
      let description = document.getElementById("descripcion");
      let file = document.getElementById("dropzoneUpdate");
      this.removeClassBorder(title);
      this.removeClassBorder(description);
      this.removeClassBorder(file);
    },
    validateForm() {
      this.errors = [];
      let title = document.getElementById("title");
      let description = document.getElementById("descripcion");
      let file = document.getElementById("dropzone");
      if (!this.content.title) {
        this.errors.push("Ingrese el titulo.");
        title.className += " border-danger";
      } else {
        this.removeClassBorder(title);
      }
      if (!this.content.description) {
        this.errors.push("Ingrese la descripción.");
        description.className += " border-danger";
      } else {
        this.removeClassBorder(description);
      }
      if (!this.content.file.length) {
        this.errors.push("Suba almenos una foto.");
        file.className += " border-danger";
      } else {
        this.removeClassBorder(file);
      }

      if (
        this.content.title &&
        this.content.description &&
        this.content.file.length
      ) {
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
      document.getElementById("btn-enviar").disabled = true;
      this.sending = true;
    },
    sendForm() {
      const config = { headers: { "Content-Type": "multipart/form-data" } };

      let formData = new FormData();
      formData.append("_method", "PUT");
      formData.append("title", this.content.title);
      formData.append("description", this.content.description);
      formData.append("user_id", this.user_id);

      //Es necesario hacer el recorrido para que nuestro formData no convierta el array en string
      for (let i in this.content.file) {
        if (this.content.file[i].imageExistent) {
          formData.append("preserveImage[]", this.content.file[i].name);
        } else {
          formData.append("path[]", this.content.file[i]);
        }
      }
      axios
        .post(`/content/${this.content.id}`, formData, config)
        .then((res) => {
          this.cleanForm();
          this.finishLoading();
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
      this.content.title = "";
      this.content.description = "";
      this.content.file = [];
      this.$refs.dropzoneUpdate.removeAllDropzone();
    },
    finishLoading() {
      document.getElementById("btn-enviar").disabled = false;
      this.sending = false;
      Swal.fire({
        title: "Editado",
        text: "Contenido editado.",
        showConfirmButton: true,
      });
    },
  },
};
</script>


