$(document).on('click', '.listarDetallesInstalacion', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var idinstalacion = $(this).data('id');
    var numeroinstalacion = $(this).data('orden');
    $('#beanInstalacion').html(numeroinstalacion);
    $.ajax({
        url: '../controlador/contInstalacion.php?accion=ListaDetallesInstalacion&idinstalacion=' + idinstalacion,
        type: 'GET',
        success: function(result) {
            $("#mens_detalleInstalacion").html(result);
        }
    });
});