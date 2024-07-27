$(document).ready(function () {
    var tablaPersonas = $("#tablaPersonas").DataTable({
        
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

    //Validacion

    // Aplicar máscara de entrada para el campo de teléfono
    $('#telefono').inputmask({
        mask: "999999999",
        placeholder: "987654321",
        clearMaskOnLostFocus: true
    });

    // Validar longitud del teléfono según el prefijo
    $('#prefijo_telefono').on('change', function () {
        var prefijo = $(this).val();
        if (prefijo === '+51') {
            $('#telefono').inputmask({
                mask: "999999999",
                placeholder: "987654321",
                clearMaskOnLostFocus: true
            });
        } else {
            $('#telefono').inputmask("remove"); // Remover máscara si no es +51
        }
    });

    // Manejar la combinación de prefijo y teléfono al enviar el formulario
    $('form').on('submit', function (e) {
        var prefijo = $('#prefijo_telefono').val();
        var telefono = $('#telefono').val();

        // Validar la longitud del teléfono si el prefijo es +51
        if (prefijo === '+51' && telefono.length !== 9) {
            alert('El número de teléfono para el prefijo +51 debe tener 9 dígitos.');
            e.preventDefault(); // Prevenir envío del formulario
            return false;
        }

    });


});
