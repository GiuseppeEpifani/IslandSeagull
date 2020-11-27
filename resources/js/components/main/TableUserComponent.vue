<template>
  <div>
    <hr />
    <table id="table_user" class="table" width="100%">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nombre</th>
          <th scope="col">Correo</th>
          <th scope="col">Creado</th>
          <th scope="col">Estado</th>
          <th scope="col" data-priority="1">Acción</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import Configuration from "../../const/configurationDataTableUser";
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
      const table = $("#table_user").DataTable(Configuration);
      this.table = table;
      //obtenemos la isntancia de vue para llamarla dentro de nuestra funcion jquery
      const instanceVue = this;
      $("#table_user tbody").on("click", ".btnDelete", function () {
        let data = table.row($(this).parents("tr")).data();
        instanceVue.askDeleteUser(data.id);
      });

      $("#table_user tbody").on("click", ".btnActiveUser", function () {
        let data = table.row($(this).parents("tr")).data();
        instanceVue.activeUser(data.id);
      });

      $("#table_user tbody").on("click", ".btnDeactiveUser", function () {
        let data = table.row($(this).parents("tr")).data();
        instanceVue.deactiveUser(data.id);
      });
    },
    askDeleteUser(id) {
      Swal.fire({
        title: "¿Estas seguro?",
        text: "Vas a eliminar al usuario seleccionado.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar",
        cancelButtonText: "Cancelar",
      }).then((res) => {
        if (res.isConfirmed) {
          this.deleteUser(id);
          Swal.fire({ title: "Eliminado.", showConfirmButton: true });
        }
      });
    },
    deleteUser(id) {
      axios
        .delete(`/user/${id}`)
        .then((res) => {
          this.table.ajax.reload();
        })
        .catch(function (err) {
          alert("Ocurrio un error " + err);
        });
    },
    activeUser(id) {
      axios
        .put(`/activeUser/${id}`)
        .then((res) => {
          this.table.ajax.reload();
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
    deactiveUser(id) {
      axios
        .put(`/deactiveUser/${id}`)
        .then((res) => {
          this.table.ajax.reload();
        })
        .catch(function (err) {
          alert("Ocurrio un error  " + err);
        });
    },
  },
};
</script>
