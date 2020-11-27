export default {
    responsive: true,
    "processing": true,
    "serverSide": true,
    "ajax": "/kind",
    "columns": [
        { data: 'id', className: "align-middle" },
        { data: 'name', className: "align-middle" },
        { data: 'created_at', className: "align-middle" },
        {
            "targets": -1,
            "data": null,
            "defaultContent": '<button class="btn btn-primary btn-sm btnEdit" title="Editar" data-toggle="modal" data-target="#modalEdit"><i class="fas fa-pen-square"></i></button> <button class="btn btn-danger btn-sm btnDelete"  title="Eliminar"> <i class="fas fa-trash-alt"></i></button>'
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