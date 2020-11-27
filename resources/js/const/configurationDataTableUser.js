export default {
    responsive: true,
    "processing": true,
    "serverSide": true,
    "ajax": "/user",
    "columns": [
        { data: 'id', className: "align-middle" },
        { data: 'name', className: "align-middle" },
        { data: 'email', className: "align-middle" },
        { data: 'created_at', className: "align-middle" },
        {
            data: 'state', render: function (data, type, row) {
                return type === 'display' && data == 'false' ?
                    '<button class="btn btn-primary btn-sm btnActiveUser"  title="Activar usuario"> Activar </button> ' : '<button class="btn btn-danger btn-sm btnDeactiveUser"  title="Desactivar usuario"> Desactivar</button> ';
            }, "orderable": false, "searchable": false, className: "align-middle"
        },
        {
            "targets": -1,
            "data": null,
            "defaultContent": '<button class="btn btn-danger btn-sm btnDelete"  title="Eliminar"> <i class="fas fa-trash-alt"></i></button> '
            , "orderable": false, "searchable": false, className: "align-middle"
        },


    ],
    language: {
        searchPlaceholder: "Busqueda Filtrada",
        info: "_TOTAL_ Registro(s) encontrado(s)",
        search: '<i class="fas fa-search" style=" font-size:22px;"></i>',
        paginate: {
            next: "Siguiente",
            previous: "Anterior",
        },
        lengthMenu:
            "<select>" +
            '<option value="10">Mostrar de 10 Registros</option>' +
            '<option value="30">Mostrar de 30 Registros</option>' +
            '<option value="50">Mostrar de 50 Registros</option>' +
            '<option value="-1">Mostrar todos los Registros</option>' +
            "</select>",
        loadingRecords: "Cargando...",
        processing: "Buscando...",
        emptyTable: "No hay registros",
        zeroRecords: "No hay coincidencias",
        infoEmpty: "",
        infoFiltered: "",
    },
}