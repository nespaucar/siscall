$(document).on('click', '.listarDetallesEquipoMaterial', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var idequipomaterial = $(this).data('id');
    var codigo = $(this).data('codigo');
    $('#beanDetallesEquipoMaterial').html(codigo);
    $.ajax({
        url: '../controlador/contEquipoMaterial.php?accion=ListaDetallesEquipoMaterial&idequipomaterial=' + idequipomaterial,
        type: 'GET',
        success: function(result) {
            $("#mens_detalleDetallesEquipoMaterial").html(result);
            $(".buscal").val('');
        }
    });
});
$(document).on('click', '#CargarNuevosProductos', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    $.ajax({
        url: '../controlador/contEquipoMaterial.php?accion=CargarNuevosProductos',
        type: 'POST',
        data: $('#formularionuevosproductos').serialize(),
        success: function(result) {
            $("#mensajeFinalFileExcel").html(result);
        }
    });
});
$(document).on('dblclick', '.mostrarstockequipomaterial', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var id = $(this).data('id');
    var stock = $(this).data('stock');
    var valstock = $(this).html();
    $('#mostrarstockequipomaterial' + id).hide();
    var input = '<input type="text" name="stocknuevo" data-stockanterior="' + stock + '" data-id="' + id + '" id="inputstockequipomaterial' + id + '" class="inputstockequipomaterial form-control input-sm">';
    $('#editarstockequipomaterial' + id).html(input).show();
    $('#inputstockequipomaterial' + id).val(valstock).focus();
});
$(document).on('keyup', '.inputstockequipomaterial', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var stocknuevo = $(this).val();
    var stockanterior = $(this).data('stockanterior');
    var id = $(this).data('id');
    if (e.which == 13) {
        if (solodecimal(stocknuevo) || solonumero(stocknuevo)) {
            modificarstock(stocknuevo, id);
        } else {
            $('#mostrarstockequipomaterial' + id).html(stockanterior).data('stockanterior', stockanterior);
            $('.mostrarstockequipomaterial').show();
            $('.editarstockequipomaterial').hide();
        }
    }
    if (e.which == 27) {
        $('#mostrarstockequipomaterial' + id).html(stockanterior).data('stockanterior', stockanterior);
        $('.mostrarstockequipomaterial').show();
        $('.editarstockequipomaterial').hide();
    }
});

function modificarstock(stocknuevo, id) {
    $.ajax({
        url: '../controlador/contEquipoMaterial.php?accion=modificarstock&id=' + id + '&stocknuevo=' + stocknuevo,
        type: 'GET',
        success: function() {
            $('.mostrarstockequipomaterial').show();
            $('.editarstockequipomaterial').hide();
            $('#mostrarstockequipomaterial' + id).html(parseFloat(stocknuevo));
            if (parseFloat(stocknuevo) == 0) {
                $('#mostrarstockequipomaterial' + id).css('color', 'red');
            } else {
                $('#mostrarstockequipomaterial' + id).css('color', 'green');
            }
            $('#inputstockequipomaterial' + id).data('stockanterior', stocknuevo);
        }
    }).fail(function() {
        alert('NO SE PUDO MODIFICAR STOCK');
    });
}