$(document).on('click', '.btnResAsignaciones', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var idtecnico = $(this).data('id');
    var carnet = $(this).data('carnet');
    $('#beanCarnetTecnico').html(carnet);
    $.ajax({
        url: '../controlador/contResAsignacion.php?accion=ListaDetallesResAsignacion&idtecnico=' + idtecnico,
        type: 'GET',
        success: function(result) {
            $("#mens_resAsignacionesModal").html(result);
            $('.item').removeClass('active');
            $('.itemcillo').addClass('active');
            $('.buscal').val('');
        }
    });
});
$(document).on('click', '.btnDetallesResAsignacionxEquipomaterial', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var idtecnico = $(this).data('idtecnico');
    var idequipomaterial = $(this).data('idequipomaterial');
    var codigo = $(this).data('codigo');
    var descripcion = $(this).data('descripcion');
    var tipo = $(this).data('tipo');
    var cantidad = $(this).data('cantidad');
    $('#codigequipomaterial').html(codigo);
    $('#descequipomaterial').html(descripcion);
    $('#tipequipomaterial').html(tipo);
    $('#cantequipomaterial').html(cantidad);
    parametros = {
        'idequipomaterial': idequipomaterial,
        'idtecnico': idtecnico,
        'tipo': tipo
    };
    $.ajax({
        url: '../controlador/contResAsignacion.php?accion=ListaDetallesResAsignacionxEquipomaterial',
        type: 'POST',
        data: parametros,
        success: function(result) {
            $("#TablaDetallesResAsignacionxEquipomaterial").html(result);
            $('.buscal2').val('');
        }
    });
});