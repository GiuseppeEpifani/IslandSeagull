<template>
  <div>
    <div class="container">
      <hr />
      <div class="row mx-auto">
        <div class="col-lg-6 col-md-12">
          <div class="row">
            <div class="col-lg-12 col-md-12 d-flex justify-content-center">
              <p class="h5 font-weight-bold">Usuario {{ role }}</p>
            </div>
            <div class="col-lg-12 col-md-12 d-flex justify-content-center">
              <img
                width="300px"
                height="300px"
                src="/images/user/default.png"
                alt="profile"
                class="rounded-circle shadow"
              />
            </div>
            <div class="col-lg-12 col-md-12 d-flex justify-content-center">
              <button class="btn btn-outline-primary mt-3 mb-3">
                Cambiar Foto
              </button>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 px-4 mt-5">
          <form @submit.prevent="validateForm">
            <error :errors="errors" ref="listError" />
            <div class="form-group">
              <label for="nameUser">Nombre</label>
              <input
                disabled
                type="text"
                class="form-control"
                id="nameUser"
                v-model="content.nameUser"
              />
            </div>
            <div class="form-group">
              <label for="pass">Nueva Clave</label>
              <input
                disabled
                type="password"
                class="form-control"
                id="pass"
                v-model="content.pass"
              />
            </div>
            <div class="form-group">
              <label for="repPass">Repita Clave</label>
              <input
                disabled
                type="password"
                class="form-control"
                id="repPass"
                v-model="content.repPass"
              />
            </div>
            <button
              v-if="edit"
              type="submit"
              class="btn btn-color-form btn-lg btn-block mt-3 mb-3"
              id="btn-enviar"
              style="height: 50px"
            >
              <span v-if="!sending">Listo</span>
              <span v-if="sending">
                Subiendo...
                <div class="spinner-border" role="status"></div>
              </span>
            </button>
            <button
              v-if="!edit"
              type="button"
              class="btn btn-color-form btn-lg btn-block"
              @click="editUser"
            >
              Editar
            </button>
            <button
              v-if="edit"
              type="button"
              class="btn btn-primary btn-lg btn-block"
              @click="cancelEdit"
            >
              Cancelar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user_id", "role", "name"],
  data() {
    return {
      errors: [],
      content: {
        nameUser: this.name,
        pass: "",
        repPass: "",
      },
      sending: false,
      edit: false,
    };
  },
  methods: {
    async getUser() {
      axios
        .get(`/user/${this.user_id}`)
        .then((res) => {
          this.content.name = res.data.name;
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    validateForm() {
      this.errors = [];
      let name = document.getElementById("nameUser");
      let pass = document.getElementById("pass");
      let repPass = document.getElementById("repPass");
      if (!this.content.nameUser) {
        this.errors.push("Ingrese el nombre.");
        name.className += " border-danger";
      } else {
        this.removeClassBorder(name);
      }
      if (this.content.pass !== this.content.repPass) {
        this.errors.push("Las claves no coinciden.");
        pass.className += " border-danger";
        repPass.className += " border-danger";
      } else {
        this.removeClassBorder(pass);
        this.removeClassBorder(repPass);
        return;
      }
      if (!this.content.pass) {
        this.errors.push("Ingrese la clave.");
        pass.className += " border-danger";
      } else {
        this.removeClassBorder(pass);
      }
      if (!this.content.repPass) {
        this.errors.push("Repita la clave.");
        repPass.className += " border-danger";
      } else {
        this.removeClassBorder(repPass);
      }

      if (this.content.name && this.content.pass && this.content.repPass) {
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
      let formData = new FormData();
      formData.append("_method", "PUT");
      formData.append("name", this.content.title);
      formData.append("repPass", this.content.description);
      axios
        .post(`/content/${this.user_id}`, formData)
        .then(() => {
          this.finishLoading();
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    finishLoading() {
      this.$refs.listError.cleanError();
      document.getElementById("btn-enviar").disabled = false;
      this.sending = false;
      Swal.fire({
        title: "Editado",
        text: "Listo.",
        showConfirmButton: true,
      });
    },
    editUser() {
      document.getElementById("nameUser").disabled = false;
      document.getElementById("pass").disabled = false;
      document.getElementById("repPass").disabled = false;
      this.edit = !this.edit;
    },
    cancelEdit() {
      this.$refs.listError.cleanError();
      this.content.pass = "";
      this.content.repPass = "";
      this.content.nameUser = this.name;
      document.getElementById("nameUser").disabled = true;
      document.getElementById("pass").disabled = true;
      document.getElementById("repPass").disabled = true;
      this.edit = !this.edit;
    },
  },
};
</script>

<style>
</style>