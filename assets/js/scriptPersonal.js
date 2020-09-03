$(document).on('click', '.eliminarBean', function() {
    var nombre = $('#nombre').val();
    var id = $('#id').val();
    if (nombre == undefined) {
        nombre = $(this).data('nombre');
        id = $(this).data('id');
    }
    var clase = $(this).data('clase');
    var table = $(this).data('table');
    $('#table').html(table);
    $('#nombre_cc').html(nombre);
    $('#elimina2').data('id', id);
    $('#elimina2').data('clase', clase);
    if (table == 'la ASIGNACIÓN') {
        $('#elimina2').data('idpersona', $(this).data('idpersona'));
    }
    $('#mens_resultado').addClass('hidden');
    $('#mens_resultado').html('');
    $('#mens_alerta').removeClass('hidden');
    $('#elimina2').removeClass('hidden');
});
$(document).on('click', '.btnPropUsuario', function() {
    $('#propusupropio').addClass('hide');
    $('#propuustecnicos').removeClass('hide');
    var nombre = $(this).data('nombre');
    var estado = $(this).data('estado');
    var id = $(this).data('id');
    var btn = 'danger';
    var txt = 'Deshabilitado'
    $('#usuario').html(nombre);
    $('#mensajeResetClave').html('');
    if (estado == '1') {
        btn = 'success';
        txt = 'Habilitado'
    }
    $('#divestadousuario').html('<a data-id="' + id + '" data-nombre="' + nombre + '" data-estado="' + estado + '" class="btnEstadoUsuario btn btn-' + btn + ' btn-sm">' + txt + '</i></a>');
    $('#divresetearclave').html('<a id="resetearclave" data-id="' + id + '" class="btn btn-danger btn-sm btn-grad btn-rect">Resetear</a>');
})
$(document).on("click", ".btnEstadoUsuario", function(e) {
    var id = $(this).data('id');
    var estado = $(this).data('estado');
    var nombre = $(this).data('nombre');
    var route = '../controlador/contPersonal.php?accion=cambiarestadousuario&id=' + id + '&estado=' + estado + '&nombre=' + nombre;
    $.ajax({
        url: route,
        type: 'GET',
        beforeSend: function() {
            $("divestadousuario").html(imgCargando);
        },
        success: function(result) {
            eval(result);
            $('#divestadousuario').html(btn);
            $('#us' + id).html(btn2);
        }
    });
});
$(document).on("click", "#resetearclave", function(e) {
    var id = $(this).data('id');
    var route = '../controlador/contPersonal.php?accion=resetearclave&id=' + id;
    $.ajax({
        url: route,
        type: 'GET',
        beforeSend: function() {
            $("mensajeResetClave").html(imgCargando);
        },
        success: function(result) {
            $('#mensajeResetClave').html(result);
        }
    });
});
$(document).on('click', '#btnPropUsuarioPropio', function() {
    $('#propuustecnicos').addClass('hide');
    $('#propusupropio').removeClass('hide');
    $('#spanclaveactual').html('');
    $('#spanclavenueva').css('color', 'red').html('');
})