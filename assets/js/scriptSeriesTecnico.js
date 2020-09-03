$(document).ready(function() {
    $.ajax({
        url: '../controlador/contPersonal.php?accion=obtenerTecnicos',
        type: 'GET',
        success: function(a) {
            $('#cboTecnicos').html(a);
        },
    });
});

function success(type, route, corr) {
    $("#mensajeReporte").css('color', 'red').html('Cargando...');
    setTimeout(function() {
        if (type == '1') {
            window.open(route, null, 'height=500,width=700,status=yes,toolbar=no,menubar=no,location=no,titlebar=no');
        } else {
            location.href = route;
        }
        $('#mensajeReporte').css('color', 'green').html(corr);
    }, 1500);
}
$(document).on('click', '.btnR', function() {
    var corr = 'Reporte Generado Correctamente';
    var nombretecnico = $('#idtecnico').find('option:selected').html();
    var idtecnico = $('#idtecnico').val();
    var raizroute = '../controlador/contSeriesTecnico.php?accion=repSeriesTecnico';
    var type = 1;
    if ($(this).val() == 'btnPDF') {
        type = 2;
    } else if ($(this).val() == 'btnEXC') {
        type = 3;
    }
    var route = raizroute + '&nombretecnico=' + nombretecnico + '&type=' + type + '&idtecnico=' + idtecnico;
    success(type, route, corr);
})