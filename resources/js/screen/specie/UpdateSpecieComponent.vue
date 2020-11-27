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
            <label for="nameSpecie">Titulo</label>
            <input
              type="text"
              class="form-control"
              id="nameSpecie"
              v-model="content.name"
            />
          </div>
          <div class="form-group col-md-12">
            <label for="descripcion">Descripción</label>
            <textarea
              class="form-control"
              id="descripcion"
              v-model="content.description"
              rows="3"
              style="resize: none"
            ></textarea>
          </div>
          <div class="form-group col-md-8">
            <label for="kindsUpdate">Tipos</label>
            <select
              v-model="selectedKind"
              id="kindsUpdate"
              class="form-control"
            >
              <option>Selecciona los tipos</option>
              <option
                :value="option.id"
                v-for="option in optionsKindsSelected"
                :key="option.id"
              >
                {{ option.name }}
              </option>
            </select>
          </div>
          <div class="form-group col-md-4" style="margin-top: 31px">
            <button type="button" class="btn btn-color" @click="addKind">
              Añadir
            </button>
          </div>
          <div class="form-group col-md-12">
            <span
              v-for="nameKind in nameKindsSelected"
              :key="nameKind"
              class="tag-Kind m-2"
            >
              {{ nameKind }}
              <span @click="deleteKind(nameKind)" class="tag-kind-remove"
                >x
              </span>
            </span>
          </div>
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
        name: "",
        description: "",
        file: [],
        kindsId: [],
      },
      kinds: [],
      selectedKind: "Selecciona los tipos",
      optionsKinds: [],
      sending: false,
      loading: true,
    };
  },
  created() {
    this.getKinds();
  },
  computed: {
    nameKindsSelected() {
      return this.kinds;
    },
    optionsKindsSelected() {
      return this.optionsKinds;
    },
  },
  methods: {
    getKinds() {
      axios
        .get("/showKinds")
        .then((res) => {
          this.optionsKinds = res.data;
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    onFiles(files) {
      this.content.file.push(files);
    },
    onDeleteFiles(file) {
      let index = this.content.file.indexOf(file);
      this.content.file.splice(index, 1);
    },
    addKind() {
      if (this.selectedKind != "Selecciona los tipos") {
        let objKind = this.optionsKinds.find(
          (kind) => kind.id == this.selectedKind
        );
        let index = this.kinds.indexOf(objKind.name);
        index >= 0
          ? ""
          : this.kinds.push(objKind.name) &&
            this.content.kindsId.push(objKind.id);
      }
    },
    deleteKind(nameKind) {
      let objKind = this.optionsKinds.find((kind) => kind.name == nameKind);
      let index;
      index = this.content.kindsId.indexOf(objKind.id);
      this.content.kindsId.splice(index, 1);
      index = this.kinds.indexOf(nameKind);
      this.kinds.splice(index, 1);
    },
    getSpecie(id) {
      this.loading = true;
      this.content.file = [];
      axios
        .get(`/specie/${id}/edit`)
        .then((res) => {
          this.content.id = res.data.id;
          this.content.name = res.data.name;
          this.content.description = res.data.description;
          for (let i in res.data.kinds) {
            this.content.kindsId.push(res.data.kinds[i].id);
            this.kinds.push(res.data.kinds[i].name);
          }
          this.$refs.dropzoneUpdate.setFiles(res.data.images);
          this.loading = false;
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    cleanField() {
      this.cleanForm();
      let name = document.getElementById("nameSpecie");
      let description = document.getElementById("descripcion");
      let kinds = document.getElementById("kindsUpdate");
      let file = document.getElementById("dropzone");
      this.removeClassBorder(name);
      this.removeClassBorder(description);
      this.removeClassBorder(file);
    },
    validateForm() {
      this.errors = [];
      let name = document.getElementById("nameSpecie");
      let description = document.getElementById("descripcion");
      let kinds = document.getElementById("kindsUpdate");
      let file = document.getElementById("dropzoneUpdate");
      if (!this.content.name) {
        this.errors.push("Ingrese el titulo.");
        name.className += " border-danger";
      } else {
        this.removeClassBorder(name);
      }
      if (!this.content.description) {
        this.errors.push("Ingrese la descripción.");
        description.className += " border-danger";
      } else {
        this.removeClassBorder(description);
      }
      if (!this.content.kindsId.length) {
        this.errors.push("Seleccione almenos un tipo.");
        kinds.className += " border-danger";
      } else {
        this.removeClassBorder(kinds);
      }
      if (!this.content.file.length) {
        this.errors.push("Suba almenos una foto.");
        file.className += " border-danger";
      } else {
        this.removeClassBorder(file);
      }

      if (
        this.content.name &&
        this.content.description &&
        this.content.file.length &&
        this.content.kindsId.length
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
      formData.append("name", this.content.name);
      formData.append("description", this.content.description);
      formData.append("user_id", this.user_id);
      for (let i in this.content.kindsId) {
        formData.append("kinds[]", this.content.kindsId[i]);
      }
      //Es necesario hacer el recorrido para que nuestro formData no convierta el array en string
      for (let i in this.content.file) {
        if (this.content.file[i].imageExistent) {
          formData.append("preserveImage[]", this.content.file[i].name);
        } else {
          formData.append("path[]", this.content.file[i]);
        }
      }

      axios
        .post(`/specie/${this.content.id}`, formData, config)
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
      this.selectedKind = "Selecciona los tipos";
      this.content.id = "";
      this.content.name = "";
      this.content.description = "";
      this.content.file = [];
      this.content.kindsId = [];
      this.kinds = [];
      this.$refs.dropzoneUpdate.removeAllDropzone();
    },
    finishLoading() {
      document.getElementById("btn-enviar").disabled = false;
      this.sending = false;
      Swal.fire({
        title: "Editado",
        text: "Especie editado.",
        showConfirmButton: true,
      });
    },
  },
};
</script>
