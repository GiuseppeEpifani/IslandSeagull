<template>
  <div>
    <button
      type="button"
      class="btn btn-color"
      data-toggle="modal"
      data-target="#modalAdd"
    >
      Nuevo <i class="fas fa-plus-circle"></i>
    </button>

    <div
      class="modal fade"
      id="modalAdd"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nuevo Contenido</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="() => this.$refs.addContent.cleanContent()"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <new-content
              ref="addContent"
              :user_id="user_id"
              :instanceDataTable="table"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Edit -->
    <div
      class="modal fade"
      id="modalEdit"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar Contenido</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="() => this.$refs.updatContent.cleanContent()"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <update-content
              ref="updatContent"
              :user_id="user_id"
              :instanceDataTable="table"
            />
          </div>
        </div>
      </div>
    </div>

    <hr />

    <table id="table_content" class="table" width="100%">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Titulo</th>
          <th scope="col">Descripción</th>
          <th scope="col">Imagenes</th>
          <th scope="col" data-priority="1">Estado</th>
          <th scope="col">Publicado</th>
          <th scope="col">Usuario</th>
          <th scope="col" data-priority="1">Acciones</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import Configuration from "../../const/configurationDataTableContent";
import ConfigurationAdmin from "../../const/configurationDataTableContentAdmin";
export default {
  props: ["user_id", "role"],
  data() {
    return {
      loading: true,
      contents: null,
      table: null,
    };
  },
  mounted() {
    this.myTable();
  },
  methods: {
    //el jquery debemos de llamarlo siempre al mounted
    myTable() {
      let table;
      if (this.role == "Administrador") {
        table = $("#table_content").DataTable(ConfigurationAdmin);
      } else {
        table = $("#table_content").DataTable(Configuration);
      }
      this.table = table;
      //obtenemos la isntancia de vue para llamarla dentro de nuestra funcion jquery
      const instanceVue = this;
      $("#table_content tbody").on("click", ".btnEdit", function () {
        //debemos de pasar obligadamente el table ya que si no no podemos hacer al ajax.reload()
        let data = table.row($(this).parents("tr")).data();
        instanceVue.updateContent(data.id);
      });

      $("#table_content tbody").on("click", ".btnDelete", function () {
        let data = table.row($(this).parents("tr")).data();
        instanceVue.askDeleteContent(data.id);
      });

      $("#table_content tbody").on("click", ".btnActiveContent", function () {
        let data = table.row($(this).parents("tr")).data();
        instanceVue.askActiveContent(data.id);
      });
    },
    askDeleteContent(id) {
      Swal.fire({
        title: "¿Estas seguro?",
        text: "Vas a eliminar el contenido seleccionado.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar",
        cancelButtonText: "Cancelar",
      }).then((res) => {
        if (res.isConfirmed) {
          this.deleteContent(id);
          Swal.fire({ title: "Eliminado.", showConfirmButton: true });
        }
      });
    },
    deleteContent(id) {
      axios
        .delete(`/content/${id}`)
        .then((res) => {
          this.table.ajax.reload();
        })
        .catch(function (err) {
          alert("Ocurrio un error " + err);
        });
    },
    updateContent(id) {
      this.$refs.updatContent.getContent(id);
    },
    askActiveContent(id) {
      Swal.fire({
        title: "¿Estas seguro?",
        text: "Vas a activar el contenido seleccionado.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Activar",
        cancelButtonText: "Cancelar",
      }).then((res) => {
        if (res.isConfirmed) {
          this.activeContent(id);
          Swal.fire({ title: "Activado.", showConfirmButton: true });
        }
      });
    },
    activeContent(id) {
      axios
        .put(`/activeContent/${id}`)
        .then((res) => {
          this.table.ajax.reload();
        })
        .catch(function (err) {
          alert("Ocurrio un error " + err);
        });
    },
  },
};
</script>
