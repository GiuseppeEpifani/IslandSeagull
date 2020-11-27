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

    <!-- add specie -->
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
            <h5 class="modal-title">Nueva Especie</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="() => this.$refs.addSpecie.cleanField()"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <new-specie ref="addSpecie" :instanceDataTable="table" />
          </div>
        </div>
      </div>
    </div>

    <button
      type="button"
      class="btn btn-color"
      data-toggle="modal"
      data-target="#modalAddKind"
    >
      Tipo <i class="fas fa-plus-circle"></i>
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

    <!-- show Kinds -->
    <div
      class="modal fade"
      id="modalShowKinds"
      data-keyboard="false"
      tabindex="-1"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tipos de esta especie</h5>
          </div>
          <div class="modal-body">
            <div class="container">
              <span
                v-for="nameKind in nameKinds"
                :key="nameKind.id"
                class="tag-Kind"
              >
                {{ nameKind.name }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit specie -->
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
            <h5 class="modal-title">Editar Especie</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="() => this.$refs.updatSpecie.cleanField()"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <update-specie ref="updatSpecie" :instanceDataTable="table" />
          </div>
        </div>
      </div>
    </div>

    <hr />

    <table id="table_specie" class="table" width="100%">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nombre</th>
          <th scope="col">Descripción</th>
          <th scope="col">Imagen</th>
          <th scope="col">Tipo</th>
          <th scope="col" data-priority="1">Acciones</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import Configuration from "../../const/configurationDataTableSpecie";
export default {
  data() {
    return {
      loading: true,
      contents: null,
      table: null,
      kinds: "",
    };
  },
  mounted() {
    this.myTable();
  },
  computed: {
    nameKinds() {
      return this.kinds;
    },
  },
  methods: {
    //el jquery debemos de llamarlo siempre al mounted
    myTable() {
      const table = $("#table_specie").DataTable(Configuration);
      this.table = table;
      //obtenemos la isntancia de vue para llamarla dentro de nuestra funcion jquery
      const instanceVue = this;
      $("#table_specie tbody").on("click", ".btnEdit", function () {
        //debemos de pasar obligadamente el table ya que si no no podemos hacer al ajax.reload()
        let data = table.row($(this).parents("tr")).data();
        instanceVue.updateSpecie(data.id);
      });

      $("#table_specie tbody").on("click", ".btnDelete", function () {
        let data = table.row($(this).parents("tr")).data();
        instanceVue.askDeleteSpecie(data.id);
      });

      $("#table_specie tbody").on("click", ".btnShowKinds", function () {
        let data = table.row($(this).parents("tr")).data();
        instanceVue.showKids(data.kinds);
      });
    },
    askDeleteSpecie(id) {
      Swal.fire({
        title: "¿Estas seguro?",
        text: "Vas a eliminar la especie seleccionada.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar",
        cancelButtonText: "Cancelar",
      }).then((res) => {
        if (res.isConfirmed) {
          this.deleteSpecie(id);
          Swal.fire({ title: "Eliminado.", showConfirmButton: true });
        }
      });
    },
    deleteSpecie(id) {
      axios
        .delete(`/specie/${id}`)
        .then((res) => {
          this.table.ajax.reload();
        })
        .catch(function (err) {
          alert("Ocurrio un error " + err);
        });
    },
    updateSpecie(id) {
      this.$refs.updatSpecie.getSpecie(id);
    },
    showKids(kinds) {
      this.kinds = "";
      this.kinds = kinds;
      $("#modalShowKinds").modal("show");
    },
  },
};
</script>

