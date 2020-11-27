<template>
  <div>
    <div class="container">
      <form @submit.prevent="validateForm">
        <error :errors="errors" ref="listError" />
        <div class="form-group">
          <label for="titleAdd">Titulo</label>
          <input
            type="text"
            class="form-control"
            id="titleAdd"
            v-model="content.title"
          />
        </div>
        <div class="form-group">
          <label for="descripcionAdd">Descripción</label>
          <textarea
            class="form-control"
            id="descripcionAdd"
            v-model="content.description"
            rows="3"
            style="resize: none"
          ></textarea>
        </div>
        <dropzone-add
          v-on:obtain-files="onFiles"
          v-on:delete-files="onDeleteFiles"
          ref="dropzoneAdd"
        />
        <button
          type="submit"
          class="btn btn-color-form btn-lg btn-block mt-3"
          id="btn-enviar-add"
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
      totalsize: 0,
      errors: [],
      content: {
        title: null,
        description: null,
        file: [],
      },
      sending: false,
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
    cleanContent() {
      this.cleanForm();
      let title = document.getElementById("titleAdd");
      let description = document.getElementById("descripcionAdd");
      let file = document.getElementById("dropzone");
      this.removeClassBorder(title);
      this.removeClassBorder(description);
      this.removeClassBorder(file);
    },
    validateForm(e) {
      this.errors = [];
      let title = document.getElementById("titleAdd");
      let description = document.getElementById("descripcionAdd");
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
      document.getElementById("btn-enviar-add").disabled = true;
      this.sending = true;
    },

    sendForm() {
      const config = { headers: { "Content-Type": "multipart/form-data" } };

      let formData = new FormData();
      formData.append("title", this.content.title);
      formData.append("description", this.content.description);
      formData.append("user_id", this.user_id);
      for (let i in this.content.file) {
        formData.append("path[]", this.content.file[i]);
      }
      this.cleanForm();

      axios
        .post("/content", formData, config)
        .then((res) => {
          this.finishLoading();
          $("#modalAdd").modal("toggle");
          this.instanceDataTable.ajax.reload();
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    cleanForm() {
      this.errors = [];
      this.$refs.listError.cleanError();
      this.content.title = "";
      this.content.description = "";
      this.content.file = [];
      this.$refs.dropzoneAdd.removeAllDropzone();
    },
    finishLoading() {
      document.getElementById("btn-enviar-add").disabled = false;
      this.sending = false;
      Swal.fire({
        title: "Publicado",
        text: "Contenido pulicado espere hasta que sea activado.",
        icon: "success",
        confirmButtonText: "Esta bien",
      });
    },
  },
};
</script>


