function obtenerseries(idequipomaterial) {
    $.ajax({
        url: '../controlador/contAsignacion.php?accion=obtenerseries&idequipomaterial=' + idequipomaterial,
        type: 'GET',
        success: function(result) {
            $('#cargarparametro').html(result);
            if ($("#serie")[0]) {
                $('#serie').chosen({
                    width: "100%",
                });
            } else {
                $('#cantidad').focus();
            }
        }
    }).fail(function() {
        alert('ALGO SALIÓ MAL');
    });
}

function obtenerequiposseriados() {
    $.ajax({
        url: '../controlador/contAsignacion.php?accion=obtenerequiposseriados',
        type: 'GET',
        success: function(result) {
            var anteslastcantidad = $('#equipo option:selected').attr('id');
            var anteslastval = $('#equipo').val();
            $('#divequipos').html(result);
            var lastcantidad = $('#' + anteslastcantidad).data('cantidad');
            $('#equipo').val(anteslastval);
            $('#stockproducto').html(lastcantidad);
            $('#equipo').chosen({
                width: "100%",
            });
        }
    }).fail(function() {
        alert('ALGO SALIÓ MAL');
    });
}
$(document).on('click', '#adddetalleasignacion', function() {
    var elemento = $('#adddetalleasignacion').data('bean');
    var idelemento = 0;
    var letra = 'E';
    var cantidad = 1;
    //Recuperando valores de producto y serie
    var textoproducto = $("#equipo option:selected").text();
    var array = textoproducto.split('(0)');
    if (array.length == 2) {
        $('#alertaExisteProducto').css('color', 'red').html('<b>No hay Stock de este producto.</b>');
        return false;
    }
    var textoserie = '-';
    //
    if (elemento == 'equipo') {
        if (!$('#cantidad').val() || $('#cantidad').val() == 0) {
            $('#cantidad').focus();
            $('#cantidad').val('');
            $('#alertaExisteProducto').css('color', 'red').html('<b>Ingresa una cantidad.</b>');
            return false;
        } else {
            if (!solonumero($('#cantidad').val())) {
                if (!solodecimal($('#cantidad').val())) {
                    $('#cantidad').focus();
                    $('#cantidad').val('');
                    $('#alertaExisteProducto').css('color', 'red').html('<b>Ingresa una cantidad.</b>');
                    return false;
                }
            }
        }
        cantidad = $('#cantidad').val();
        //cantidadreal = $('#equipo option:selected').data('cantidad');
        //if (cantidad > cantidadreal) {
        //$('#cantidad').focus();
        //$('#cantidad').val('');
        //$('#alertaExisteProducto').css('color', 'red').html('<b>Ingresa una cantidad <= al Stock.</b>');
        //return false;
        //}
        idelemento = $('#equipo').val();
    } else {
        idelemento = $('#serie').val();
        letra = 'S';
        textoserie = $("#serie option:selected").text();
    }
    var apendice = letra + idelemento;
    if ($('#' + apendice)[0]) {
        $('#alertaExisteProducto').css('color', 'red').html('<b>Ya agregaste este producto.</b>');
        return false;
    }
    var nFilas = $('#tablaProductos tbody tr').length + 1;
    var valor = $('#tablaProductos tbody').append('<tr id="' + apendice + '" data-id="' + apendice + '"><td>' + cantidad + '</td><td>' + textoserie + '</td><td>' + textoproducto + '</td><td><a class="label label-danger removeProductoAsignacion" href="javascript:void(0)">X</a></td></tr>');
    $('#alertaExisteProducto').css('color', 'green').html('<b>' + nFilas + ' Productos agregados Correctamente.</b>');
    $('#cantidad').val('');
});
$(document).on('click', '.removeProductoAsignacion', function() {
    $(this).parent('td').parent('tr').remove();
    $('#alertaExisteProducto').html('');
});

function registrarAsignacion() {
    var opcion = $('.grabar').data('accion');
    var tecnico = $('#tecnico').val();
    var nomtecnico = $('#tecnico option:selected').text();
    if (tecnico == '0') {
        $('#mensajes').html('<p style="color: red;"><i class="icon-check"></i> No se ha elegido ningún técnico.</p>');
        return false;
    }
    var nFilas = $('#tablaProductos tbody tr').length;
    if (nFilas == 0) {
        $('#mensajes').html('<p style="color: red;"><i class="icon-check"></i> No hay ningún producto para asignar. Llena la tabla con al menos un producto.</p>');
        return false;
    } else if (nFilas > 250) {
        $('#mensajes').html('<p style="color: red;"><i class="icon-check"></i> No puedes agregar más de 30 items. Relizar asignación aparte.</p>');
        return false;
    } else {
        var bloque = '';
        //var bloqueimpresion = '';
        var idasignacion = $('#idasignacion').val();
        var fechaentrega = $('#fechaentrega').val();
        $('#tablaProductos tbody tr').each(function() {
            bloque += $(this).find("td").eq(0).html() + '@';
            bloque += $(this).data("id") + ';';
        });
        /*
        $('#tablaProductos tbody tr').each(function() {
            bloqueimpresion += $(this).find("td").eq(0).html() + '@@';
            bloqueimpresion += $(this).find("td").eq(1).html() + '@@';
            bloqueimpresion += $(this).find("td").eq(2).html() + '@@@';
        });
        */
        //alert(bloque);
        //return false;
        $.ajax({
            url: '../controlador/contAsignacion.php?accion=' + opcion + 'Asignacion&bloque=' + bloque + '&id=' + idasignacion,
            type: 'POST',
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $("#mensajes").html(imgCargando);
            },
            success: function(result) {
                var numero = $('#numero').val();
                //imprimirAsignacion(opcion, bloqueimpresion, nomtecnico, numero, fechaentrega);
                //imprimirAsignacion(opcion, nomtecnico, numero, fechaentrega);
                $("#mensajes").html(result);
                if (opcion == "nuevo") {
                    //imprimirAsignacion(opcion, bloque, nomtecnico, numero, fechaentrega);
                    numero = numero.substring(2);
                    num = parseInt(numero) + 1;
                    $('#numero').val('AS' + num);
                    $('#tablaProductos tbody tr').remove();
                }
                obtenerequiposseriados();
                obtenerseries($('#equipo').val());
            }
        }).fail(function() {
            alert('ALGO SALIÓ MAL');
        });
    }
}

function obtenerDetallesAsignacion(id) {
    $.ajax({
        url: '../controlador/contAsignacion.php?accion=obtenerDetallesAsignacion&id=' + id,
        type: 'GET',
        success: function(result) {
            $("#tablaProductos tbody").append(result);
        }
    }).fail(function() {
        alert('ALGO SALIÓ MAL');
    });
}
$(document).on('click', '.listarDetallesAsignacion', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var idasignacion = $(this).data('id');
    var numeroasignacion = $(this).data('numero');
    $('#beanAsignacion').html(numeroasignacion);
    $.ajax({
        url: '../controlador/contAsignacion.php?accion=ListaDetallesAsignacion&idasignacion=' + idasignacion,
        type: 'GET',
        success: function(result) {
            $("#mens_detalleAsignacion").html(result);
        }
    });
});
$(document).on('keyup', '#serieBarra', function(e) {
    if (e.which == 13) {
        obtenerDetalleEquipoSeriado($(this).val());
        $(this).val('');
    }
});

function obtenerDetalleEquipoSeriado(serie) {
    $.ajax({
        url: '../controlador/contAsignacion.php?accion=obtenerDetalleEquipoSeriado&serie=' + serie,
        type: 'GET',
        dataType: 'JSON',
        success: function(a) {
            if (a.mensaje == '0') {
                $('#alertaExisteProducto').css('color', 'red').html('<b>Equipo no existe.</b>');
            } else {
                if ($('#' + a.idequipomaterial)[0]) {
                    $('#alertaExisteProducto').css('color', 'red').html('<b>Ya agregaste este producto.</b>');
                } else {
                    $('#alertaExisteProducto').css('color', 'green').html('<b>' + a.mensaje + '</b>');
                    $('#tablaProductos tbody').append(a.tabla);
                }
            }
        }
    });
}

//function imprimirAsignacion(opcion, bloqueimpresion, nomtecnico, numero, fechaentrega) {
//function imprimirAsignacion(opcion, nomtecnico, numero, fechaentrega, link) {
function imprimirAsignacion(nomtecnico, numero, fechaentrega, link) {
    //var route = '../ticket/ticket.php';
    var route = '../ticket/ticket2.php';
    var parametros = {
        //'bloqueimpresion': bloqueimpresion,
        'numero': numero,
        'fechaentrega': fechaentrega,
        'nomtecnico': nomtecnico
    };
    $.ajax({
        url: route,
        type: 'POST',
        data: parametros,
        success: function() {
            window.open("../ticket/" + link, null, 'height=500,width=700,status=yes,toolbar=no,menubar=no,location=no,titlebar=no');
        }
    });
}

function filterFloat(evt, input) {
    var key = window.Event ? evt.which : evt.keyCode;
    var chark = String.fromCharCode(key);
    var tempValue = input.value + chark;
    if (key >= 48 && key <= 57) {
        if (filter(tempValue) === false) {
            return false;
        } else {
            return true;
        }
    } else {
        if (key == 8 || key == 13 || key == 0) {
            return true;
        } else if (key == 46) {
            if (filter(tempValue) === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}

function filter(__val__) {
    var preg = /^([0-9]+\.?[0-9]{0,3})$/;
    if (preg.test(__val__) === true) {
        return true;
    } else {
        return false;
    }
}