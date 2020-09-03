function buscarLiquidacion() {
    var liquidacion = $('#txtLiquidacion').val();
    if (liquidacion == '') {
        $('#mensajeDetalleLiquidacion').css('color', 'red').html('Ingresa una Orden');
        $('#txtLiquidacion').val('').focus();
        $('#mensajeDetalleLiquidacion').html(a.mensaje);
        $('#fechaLi').html('');
        $('#observacionLi').html('');
        $('#actividadLi').html('');
        $('#otLi').html('');
        $('#tec1Li').html('');
        $('#tec2Li').html('');
        $('#tec3Li').html('');
        $('#prefijoLi').html('');
        $('#telefonoLi').html('');
        $('#detallesLi').html('');
        return false;
    }
    $.ajax({
        url: '../controlador/contInstalacion.php?accion=buscarLiquidacion&liquidacion=' + liquidacion,
        dataType: 'JSON',
        success: function(a) {
            $('#mensajeDetalleLiquidacion').html(a.mensaje);
            $('#fechaLi').html(a.fecha);
            $('#observacionLi').html(a.observacion);
            $('#actividadLi').html(a.actividad);
            $('#otLi').html(a.ot);
            $('#tec1Li').html(a.tec1);
            $('#tec2Li').html(a.tec2);
            $('#tec3Li').html(a.tec3);
            $('#prefijoLi').html(a.prefijo);
            $('#telefonoLi').html(a.telefono);
            $('#detallesLi').html(a.detalles);
        },
    })
}