$(document).on('click', '#aceptarFileExcel', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var datitos = $('#formularionuevosproductos').serialize();
    $('#mensajeFinalFileExcel').html('');
    var estado = $(this).data('estado');
    var fileExcel = booleanFileExcel();
    var file = $('#fileExcel').val();
    var filesplit = file.split('\\');
    var nomarchivo = filesplit[filesplit.length - 1];
    var tabla = $('#btnCargarExcel').data('tabla');
    if (fileExcel) {
        retornoTabla(tabla, nomarchivo, estado, datitos);
    }
});
$(document).on('change', '#fileExcel', function() {
    booleanFileExcel();
    $('#mensajeFinalFileExcel').html('');
});
$(document).on('click', '#btnCargarExcel', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    $('#mensajeFileExcel').html('');
    $('#fileExcel').val('');
    $('#mensajeFinalFileExcel').html('');
    $('#beanFileExcel').html($(this).data('bean'));
    $('#aceptarFileExcel').data('estado', '0');
});

function booleanFileExcel() {
    var file = $('#fileExcel').val();
    var mensaje = $('#mensajeFileExcel');
    if (!file) {
        mensaje.css('color', 'red').html('* Debes seleccionar un archivo.');
        return false;
    }
    var ext = file.substring(file.lastIndexOf("."));
    if (ext != ".xlsx") {
        mensaje.css('color', 'red').html('* Debes seleccionar una extensión correcta.');
        return false;
    } else {
        mensaje.css('color', 'green').html('Elegiste una extensión correcta.');
        return true;
    }
};

function retornoTabla(tabla, nomarchivo, estado, datitos) {
    var form = $('#formularionuevosproductos')[0];
    var data = new FormData(form);
    var accion = '';
    if (tabla == 'Instalaciones') {
        accion = 'previo';
        if (estado == '1') {
            datitos = '&' + datitos;
            accion = 'registro';
        }
    }
    $.ajax({
        url: '../controlador/contImportes.php?accion=' + accion + tabla + '&file=' + nomarchivo + datitos,
        type: 'POST',
        data: data,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        beforeSend: function() {
            $("#mensajeFinalFileExcel").html(imgCargando);
        },
        success: function(result) {
            $("#mensajeFinalFileExcel").html(result);
            $("#mensajeFileExcel").html('');
            if (tabla != 'Instalaciones') {
                if (result.indexOf('Registrar Nuevos Productos') == -1) {
                    $('#fileExcel').val('');
                }
                link('frm' + tabla + '.php');
            } else {
                if (accion == 'registro') {
                    $('#fileExcel').val('');
                    link('frm' + tabla + '.php');
                    $('#aceptarFileExcel').data('estado', '0');
                } else {
                    $('#aceptarFileExcel').data('estado', '1');
                }
            }
        }
    });
}
$(document).on('click', '.detalleguiaremision', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var idguiaremision = $(this).data('id');
    var numeroguiaremision = $(this).data('numero');
    $('#beanGuiaRemision').html(numeroguiaremision);
    $.ajax({
        url: '../controlador/contGuiaRemision.php?accion=ListaDetallesGuiaRemision&idguiaremision=' + idguiaremision,
        type: 'GET',
        beforeSend: function() {
            $("#mensajeFinalFileExcel").html(imgCargando);
        },
        success: function(result) {
            $("#mens_detalleGuiaRemision").html(result);
        }
    });
});
$(document).on('change', '#fileExcel', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    $('#aceptarFileExcel').data('estado', '0');
});