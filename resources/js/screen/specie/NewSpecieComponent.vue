<template>
  <div>
    <div class="container">
      <form @submit.prevent="validateForm">
        <error :errors="errors" ref="listError" />
        <div class="form-row">
          <div class="form-group col-md-12">
            <label for="nameAdd">Nombre</label>
            <input
              type="text"
              class="form-control"
              id="nameAdd"
              v-model="content.name"
            />
          </div>
          <div class="form-group col-md-12">
            <label for="descripcionAdd">Descripción</label>
            <textarea
              class="form-control"
              id="descripcionAdd"
              v-model="content.description"
              rows="3"
              style="resize: none"
            ></textarea>
          </div>
          <div class="form-group col-md-8">
            <label for="kindsAdd">Tipos</label>
            <select v-model="selectedKind" id="kindsAdd" class="form-control">
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
import EventBus from "../../bus";
export default {
  props: ["user_id", "instanceDataTable"],
  data() {
    return {
      totalsize: 0,
      errors: [],
      content: {
        name: null,
        description: null,
        file: [],
        kindsId: [],
      },
      kinds: [],
      selectedKind: "Selecciona los tipos",
      optionsKinds: [],
      sending: false,
    };
  },
  created() {
    this.getKinds();
  },
  mounted() {
    EventBus.$on("ADDED_KIND", (kind) => {
      this.optionsKinds.push(kind);
    });
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
    cleanField() {
      this.cleanForm();
      let name = document.getElementById("nameAdd");
      let description = document.getElementById("descripcionAdd");
      let kinds = document.getElementById("kindsAdd");
      let file = document.getElementById("dropzone");
      this.removeClassBorder(name);
      this.removeClassBorder(description);
      this.removeClassBorder(kinds);
      this.removeClassBorder(file);
    },
    validateForm(e) {
      this.errors = [];
      let name = document.getElementById("nameAdd");
      let description = document.getElementById("descripcionAdd");
      let kinds = document.getElementById("kindsAdd");
      let file = document.getElementById("dropzone");
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
      document.getElementById("btn-enviar-add").disabled = true;
      this.sending = true;
    },

    sendForm() {
      const config = { headers: { "Content-Type": "multipart/form-data" } };

      let formData = new FormData();
      formData.append("name", this.content.name);
      formData.append("description", this.content.description);
      for (let i in this.content.kindsId) {
        formData.append("kinds[]", this.content.kindsId[i]);
      }
      for (let i in this.content.file) {
        formData.append("path[]", this.content.file[i]);
      }
      this.cleanForm();

      axios
        .post("/specie", formData, config)
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
      this.selectedKind = "Selecciona los tipos";
      this.content.name = "";
      this.content.description = "";
      this.content.file = [];
      this.content.kindsId = [];
      this.kinds = [];
      this.$refs.dropzoneAdd.removeAllDropzone();
    },
    finishLoading() {
      document.getElementById("btn-enviar-add").disabled = false;
      this.sending = false;
      Swal.fire({
        title: "Publicado",
        text: "Especie pulicada.",
        icon: "success",
        confirmButtonText: "Esta bien",
      });
    },
  },
};
</script>

<style>
.tag-Kind {
  background: #2d95ea;
  color: #ffffff;
  border-radius: 10px;
  padding-left: 5px;
  padding-right: 5px;
  margin-right: 3px;
  margin-left: 3px;
}

.tag-kind-remove {
  color: #ffffff;
  margin-left: 5px;
  font-weight: bold;
}
.tag-kind-remove:hover {
  cursor: pointer;
}
</style>


