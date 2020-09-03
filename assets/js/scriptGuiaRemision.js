$(document).on('click', '#btnAumentarStock', function() {
    $.ajax({
        url: '../controlador/contEquipoMaterial.php?accion=obtenerMateriales',
        type: 'GET',
        success: function(a) {
            $('#divasmaterial').html(a);
            $(".chzn-select").chosen();
            $(".chzn-select-deselect").chosen({
                allow_single_deselect: true
            });
            $('.chosen-container').removeAttr('style').attr({
                style: 'width:100%',
            });
            $('#ascantidad').val('');
            $('#mens_aumentarStock').html('');
            $('#alertaExisteProduct').html('');
        },
    });
});
$(document).on('click', '.aumplus', function() {
    var cantidad = $("#ascantidad").val();
    if (!cantidad || cantidad == 0) {
        $('#ascantidad').focus();
        $('#ascantidad').val('');
        $('#alertaExisteProduct').css('color', 'red').html('Ingresa una cantidad.');
        return false;
    } else {
        if (!solonumero(cantidad)) {
            if (!solodecimal(cantidad)) {
                $('#ascantidad').focus();
                $('#ascantidad').val('');
                $('#alertaExisteProduct').css('color', 'red').html('Ingresa una cantidad.');
                return false;
            }
        }
    }
    var id = $('#asmaterial').val();
    if ($('#' + id)[0]) {
        $('#alertaExisteProduct').css('color', 'red').html('<b>Ya agregaste este producto.</b>');
        $('#ascantidad').val('');
    } else {
        $('#alertaExisteProduct').css('color', 'green').html('Material Agregado Correctamente');
        var descripcion = $("#asmaterial option:selected").text();
        var codigo = $("#asmaterial option:selected").data('codigo');
        var elim = '<a href="#" data-id="' + id + '" class="label label-danger btn-sm asminus"><i class="icon-remove"></i></a>';
        var fila = '<tr data-id="' + id + '" id="' + id + '"><td>' + codigo + '</td><td>' + descripcion + '</td><td>' + cantidad + '</td><td>' + elim + '</td></tr>';
        $('#mens_aumentarStock').append(fila);
    }
    $('#ascantidad').val('');
});
$(document).on('click', '#anguia', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var hayelementos = $('#mens_aumentarStock').html();
    if (hayelementos == '') {
        $('#alertaExisteProduct').css('color', 'red').html('<b>No tienes Elementos en la Tabla.</b>');
        $('#ascantidad').val('').focus();
        return false;
    } else {
        var bloque = '';
        $('#mens_aumentarStock tr').each(function() {
            bloque += $(this).data("id").substr(3) + '@@';
            bloque += $(this).find("td").eq(2).html() + '@@@';
        });
        $.ajax({
            url: '../controlador/contGuiaRemision.php?accion=nuevaGuiaRemisionManual&bloque=' + bloque,
            type: 'GET',
            beforeSend: function() {
                $("#alertaExisteProduct").html(imgCargando);
            },
            success: function(result) {
                $('#alertaExisteProduct').css('color', 'green').html(result);
                $('#mens_aumentarStock').html('');
                $('#ascantidad').val('').focus();
            }
        }).fail(function() {
            alert('ALGO SALIÓ MAL');
        });
    }
});
$(document).on('click', '.asminus', function() {
    var id = $(this).data('id');
    $('#' + id).remove();
});