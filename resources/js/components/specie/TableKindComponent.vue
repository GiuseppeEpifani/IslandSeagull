<template>
  <div>
    <button
      type="button"
      class="btn btn-color"
      data-toggle="modal"
      data-target="#modalAddKind"
    >
      Nuevo <i class="fas fa-plus-circle"></i>
    </button>

    <!-- add kind -->
    <div
      class="modal fade"
      id="modalAddKind"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nuevo Tipo</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="() => this.$refs.addKind.cleanField()"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <new-kind ref="addKind" :instanceDataTable="table" />
          </div>
        </div>
      </div>
    </div>

    <!-- Edit kind -->
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
            <h5 class="modal-title">Editar Tipo</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="() => this.$refs.updatKind.cleanField()"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <update-kind ref="updatKind" :instanceDataTable="table" />
          </div>
        </div>
      </div>
    </div>

    <hr />

    <table id="table_kind" class="table" width="100%">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nombre</th>
          <th scope="col">Creado</th>
          <th scope="col" data-priority="1">Acciones</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import Configuration from "../../const/configurationDataTableKind";
export default {
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
      const table = $("#table_kind").DataTable(Configuration);
      this.table = table;
      //obtenemos la isntancia de vue para llamarla dentro de nuestra funcion jquery
      const instanceVue = this;
      $("#table_kind tbody").on("click", ".btnEdit", function () {
        //debemos de pasar obligadamente el table ya que si no no podemos hacer al ajax.reload()
        let data = table.row($(this).parents("tr")).data();
        instanceVue.updateKind(data.id);
      });

      $("#table_kind tbody").on("click", ".btnDelete", function () {
        let data = table.row($(this).parents("tr")).data();
        instanceVue.askDeleteKind(data.id);
      });
    },
    askDeleteKind(id) {
      Swal.fire({
        title: "¿Estas seguro?",
        text: "Vas a eliminar el tipo seleccionado.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar",
        cancelButtonText: "Cancelar",
      }).then((res) => {
        if (res.isConfirmed) {
          this.deleteKind(id);
          Swal.fire({ title: "Eliminado.", showConfirmButton: true });
        }
      });
    },
    deleteKind(id) {
      axios
        .delete(`/kind/${id}`)
        .then((res) => {
          this.table.ajax.reload();
        })
        .catch(function (err) {
          alert("Ocurrio un error " + err);
        });
    },
    updateKind(id) {
      this.$refs.updatKind.getKind(id);
    },
  },
};
</script>

