function buscarSerie() {
    var serie = $('#txtSerie').val();
    if (serie == '') {
        $('#mensajeDetalleSerie').css('color', 'red').html('Ingresa una Serie');
        $('#detalleSerie').html("");
        $('#txtSerie').val('').focus();
        return false;
    }
    $.ajax({
        url: '../controlador/contEquipoMaterial.php?accion=buscarSerie&serie=' + serie,
        success: function(a) {
            $('#detalleSerie').html(a);
            $('#mensajeDetalleSerie').css('color', 'green').html('Información de la Serie');
        },
    })
}