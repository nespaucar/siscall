$(function() {
    $.ajax({
        url: '../controlador/contPuntosBarema.php?accion=llenarTablas&tabla=prefijo',
        type: 'GET',
        success: function(e) {
            $('#divprefijo').append(e);
        },
    }).fail(function() {
        alert('OCURRIÓ UN ERROR');
    });
    $.ajax({
        url: '../controlador/contPuntosBarema.php?accion=llenarTablas&tabla=actividad',
        type: 'GET',
        success: function(e) {
            $('#divactividad').append(e);
        },
    }).fail(function() {
        alert('OCURRIÓ UN ERROR');
    });
    $.ajax({
        url: '../controlador/contPuntosBarema.php?accion=llenarTablas&tabla=baremo',
        type: 'GET',
        success: function(e) {
            $('#divbaremo').append(e);
        },
    }).fail(function() {
        alert('OCURRIÓ UN ERROR');
    });
});
$(document).on('click', '.new', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var id = $(this).attr('id');
    var cantFilas = $('#div' + id + ' > tr').length + 1;
    var newFila = '<tr><td class="num">' + cantFilas.toString() + '</td>';
    if (id != 'baremo') {
        newFila += '<td class="inpt"><div class="form-group input-group" style="margin: 0 auto;"><input type="text" class="form-control input-sm" id="input' + id + cantFilas.toString() + '"><span class="input-group-btn"><button class="btn btn-success input-sm pluss" type="button" data-tabla="' + id + '">+</button><button data-bean="serie" class="btn btn-danger input-sm minuss" type="button" data-tabla="' + id + '">-</button></span></div></td>';
    } else {
        var nombre = '';
        var ids = '';
        //select prefijos
        newFila += '<td class="tdprefijo"><select class="puntprefijo form-control input-sm">';
        newFila += retornoopciones('prefijo');
        newFila += '</select></td>';
        //select actividades
        newFila += '<td class="tdactividad"><select class="puntactividad form-control input-sm">';
        newFila += retornoopciones('actividad');
        newFila += '</select></td>';
        //input puntaje
        newFila += '<td class="tdpuntaje"><div class="form-group input-group" style="margin: 0 auto;"><input type="text" class="form-control input-sm" id="input' + id + cantFilas.toString() + '"><span class="input-group-btn"><button class="btn btn-success input-sm pluss" type="button" data-tabla="' + id + '">+</button><button data-bean="serie" class="btn btn-danger input-sm minuss" type="button" data-tabla="' + id + '">-</button></span></div></td>';
    }
    newFila += '</tr>';
    $('#div' + id).append(newFila);
    $('#input' + id + cantFilas.toString()).focus();
});
$(document).on('click', '.minuss', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    $(this).parent().parent().parent().parent().remove();
    var tabla = $(this).data('tabla');
    reordenarIndexTabla(tabla);
});

function reordenarIndexTabla(tabla) {
    var i = 1;
    $("#div" + tabla + " tr").each(function() {
        $(this).find('.num').html(i);
        if (tabla != 'baremo') {
            $(this).find('.inpt').find('.input-group').find('.input-sm').attr('id', 'input' + tabla + i.toString());
        }
        i++;
    });
}
$(document).on('click', '.pluss', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var text = $(this).parent().parent().find('.form-control').val();
    var newdiv = $(this).parent().parent();
    var tabla = $(this).data('tabla');
    var nomprefijo = '';
    var idprefijo = '';
    var nomactividad = '';
    var idactividad = '';
    if (tabla == 'baremo') {
        nomprefijo = $(this).parent().parent().parent().parent().find('.tdprefijo').find('.puntprefijo option:selected').html();
        idprefijo = $(this).parent().parent().parent().parent().find('.tdprefijo').find('.puntprefijo').val();
        nomactividad = $(this).parent().parent().parent().parent().find('.tdactividad').find('.puntactividad option:selected').html();
        idactividad = $(this).parent().parent().parent().parent().find('.tdactividad').find('.puntactividad').val();
    }
    if (text == '') {
        $(this).parent().parent().find('.form-control').focus();
        return 0;
    } else {
        if (tabla == 'baremo') {
            if (!solonumero(text)) {
                if (!solodecimal(text)) {
                    $(this).parent().parent().find('.form-control').val('').focus();
                    return 0;
                }
            }
        }
    }
    $.ajax({
        url: '../controlador/contPuntosBarema.php?accion=new&nombre=' + text + '&tabla=' + tabla + '&idprefijo=' + idprefijo + '&idactividad=' + idactividad,
        type: 'GET',
        success: function(e) {
            if (e != '0') {
                if (tabla != 'baremo') {
                    newdiv.parent().html('<center class="objectId"><div class="col-md-9">' + text + '</div><div class="col-md-3"><button class="label label-danger removepb" data-tabla="' + tabla + '" data-id="' + e + '">x</button></div></center>');
                    $('.puntprefijo').html(retornoopciones('prefijo'));
                    $('.puntactividad').html(retornoopciones('actividad'));
                } else {
                    var divito = '<tr><td class="num"></td><td class="tdprefijo" data-id="' + idprefijo + '"><center class="objectId"><div class="col-md-9">' + nomprefijo + '</div></center></td><td class="tdactividad" data-id="' + idactividad + '"><center class="objectId"><div class="col-md-9">' + nomactividad + '</div></center></td><td><center class="objectId"><div class="col-md-9">' + text + '</div><div class="col-md-3"><button class="label label-danger removepb" data-tabla="baremo" data-id="' + e + '">x</button></div></center></td></tr>';
                    newdiv.parent().parent().remove();
                    $('#div' + tabla).append(divito);
                    reordenarIndexTabla(tabla);
                }
            } else {
                alert('No debes tener duplicados.');
                newdiv.find('.form-control').focus();
            }
        },
    }).fail(function() {
        alert('OCURRIÓ UN ERROR');
    });
});
$(document).on('click', '.removepb', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var id = $(this).data('id');
    var tabla = $(this).data('tabla');
    var fila = $(this).parent().parent().parent().parent();
    $.ajax({
        url: '../controlador/contPuntosBarema.php?accion=eliminar&id=' + id + '&tabla=' + tabla,
        type: 'GET',
        success: function(e) {
            if (e == '1') {
                alert('Eliminado Correctamente.');
                fila.remove();
                reordenarIndexTabla(tabla);
                $('.puntprefijo').html(retornoopciones('prefijo'));
                $('.puntactividad').html(retornoopciones('actividad'));
            } else {
                alert('Tienes registros en Baremo, no puedes eliminar.');
            }
        },
    }).fail(function() {
        alert('OCURRIÓ UN ERROR');
    });
});

function retornoopciones(tabla) {
    newFila = '';
    $("#div" + tabla + " .objectId").each(function() {
        nombre = $(this).find('.col-md-9').html();
        ids = $(this).find('.col-md-3 .removepb').data('id');
        newFila += '<option value="' + ids + '">' + nombre + '</option>';
    });
    return newFila;
}