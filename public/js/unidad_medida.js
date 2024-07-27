$(document).ready(function () {
    var tablaUnidadMedida = $("#tablaUnidadMedida").DataTable({
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn-circle btn-primary btnEditarUnidadMedida'><i class='fa-solid fa-pencil'></i></button>&nbsp;<button class='btn-circle  btn-danger btnBorrarUnidadMedida'><i class='fa-solid fa-trash'></i></button></div></div>"
        }],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        }
    });

    $("#btnNuevo").click(function () {
        $("#formnuevoUnidadMedida").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $(".modal-title").text("Nuevo Registro");
        $("#modalCRUDUnidadMedida").modal("show");
    });

    var fila; //capturar la fila para editar o borrar el registro

    //botón EDITAR  
    $(document).on("click", ".btnEditarUnidadMedida", function () {
        fila = $(this).closest("tr");
        var medidaid = parseInt(fila.find('td:eq(0)').text());

        // Obtener los datos del producto usando AJAX
        $.ajax({
            url: "unidadmedidas/" + medidaid + "/edit",
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data) {
                    // Llenar el formulario de edición con los datos
                    var form = $('#formEditarUnidadMedida');
                    form.attr('action', `/unidadmedidas/${medidaid}`);
                    form.find('#descripcion').val(data.descripcion);
                    form.find('#abreviacion').val(data.abreviacion);
                    form.find('#created_at').val(data.created_at);
                    form.find('#updated_at').val(data.updated_at);
                    $(".modal-header").css("background-color", "#4e73df");
                    $(".modal-header").css("color", "white");
                    $(".modal-title").text("Editar Unidad de Medida");
                    $("#modalEditarUnidadMedida").modal("show");
                } else {
                    alert("No se encontraron datos para la unidad de medida seleccionada.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error al obtener los datos de la unidad de medida: ", error);
                alert("Ocurrió un error al obtener los datos de la unidad de medida. Por favor, inténtelo de nuevo.");
            }
        });
    });

    // Eliminar producto
    $(document).on("click", ".btnBorrarUnidadMedida", function () {
        fila = $(this);
        var id = parseInt($(this).closest("tr").find('td:eq(0)').text());
        Swal.fire({
            title: '¿Está seguro de eliminar la unidad de medida?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/unidadmedidas/${id}`,
                    type: "DELETE",
                    dataType: "json",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function () {
                        tablaUnidadMedida.row(fila.parents('tr')).remove().draw();
                        alert("Unidad de medida eliminada correctamente.");
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al eliminar la unidad de medida: ", error);
                        alert("Ocurrió un error al eliminar la unidad de medida. Por favor, inténtelo de nuevo.");
                    }
    
                });
            }
        });
        
    });

    // Cerrar el modal al hacer clic en el botón de cancelar o en la 'X'
    $("#modalCRUDUnidadMedida").on('hidden.bs.modal', function () {
        $("#formnuevoUnidadMedida").trigger("reset");
    });

    $(".close, .btn-secondary").click(function () {
        $("#modalCRUDUnidadMedida").modal("hide");
    });

    // Cerrar el modal al hacer clic en el botón de cancelar o en la 'X'
    $("#modalEditarUnidadMedida").on('hidden.bs.modal', function () {
        $("#formEditarUnidadMedida").trigger("reset");
    });

    $(".close, .btn-secondary").click(function () {
        $("#modalEditarUnidadMedida").modal("hide");
    });

});
