export default {
    responsive: true,
    "processing": true,
    "serverSide": true,
    "ajax": "/content",
    "columns": [
        { data: 'id', className: "align-middle" },
        { data: 'title', className: "align-middle" },
        { data: 'description', className: "text-suspencivo align-middle" },
        {
            data: 'images', render: function (data, type, row) {
                return type === 'display' ?
                    '<img src="images/' + data[0].path + '" width="80px" height="80px" class="rounded-circle" alt=""/>' : data;
            }, "orderable": false, "searchable": false, className: "align-middle"
        },
        {
            data: 'state', render: function (data, type, row) {
                return type === 'display' && data == 'false' ?
                    ' <div class="bg-danger text-white text-center rounded">' + data + '</div>' : ' <div class="bg-primary text-white text-center rounded">' + data + '</div>';
            }, className: "align-middle"
        },
        { data: 'created_at', className: "align-middle" },
        { data: 'user.email', className: "align-middle" },
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