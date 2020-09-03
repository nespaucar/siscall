var imgCargando = "<img src='../assets/img/cargando.gif' width='100' height='100'/>";
var loading = '<br><div class="row">' + '<div class="col-lg-12">' + '<div class="progress progress-striped active" rel="tooltip" data-placement="bottom" data-original-title="Total Progress">' + '<div id="total-bar" class="progress-bar progress-bar-primary" style="width: 100%;">100%</div>' + '</div>' + '</div>' + '</div>';

function pulsar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    return (tecla != 13);
}

function link(dir, contenedor = '') {
    if (contenedor === '') {
        contenedor = 'contenedor';
    }
    $.ajax({
        type: "POST",
        url: dir,
        beforeSend: function() {
            $("#" + contenedor).html(loading);
        },
        success: function(a) {
            $('#' + contenedor).html(a);
        }
    });
}

function inicializarFecha() {
    $(".fechita").datepicker({
        dateFormat: 'yy-mm-dd',
        firstDay: 1
    }).datepicker("setDate", new Date());
}

function noduplicidad(palabra, campo, bean, controlador) {
    return JSON.parse($.ajax({
        url: '../controlador/cont' + controlador + '.php?accion=noduplicidad&campo=' + campo + '&palabra=' + palabra + '&bean=' + bean,
        type: 'GET',
        async: false,
        dataType: 'json',
        success: function(result) {
            return result;
        }
    }).responseText);
}

function mantenimiento(accion) {
    var cargando = "";
    $.ajax({
        type: "POST",
        url: $('#formulario').attr('action'),
        data: $('#formulario').serialize(),
        beforeSend: function() {
            $("#mensajes").html(loading);
        },
        success: function(a) {
            $('#mensajes').html(a);
            if (accion == 'nuevo') {
                inicializarFormulario();
            }
        }
    });
}

function llenarTabla(bean, colspan, param = '') {
    $.ajax({
        type: "POST",
        url: "../controlador/cont" + bean + ".php?accion=Lista" + bean + param,
        data: $('#form_search').serialize(),
        dataType: 'JSON',
        beforeSend: function() {
            $("#tabla" + bean).html("<tr colspan='" + colspan + "'>" + imgCargando + "</tr>");
        },
        success: function(a) {
            $('#tabla' + bean).html(a.tabla);
            $('#paginacion' + bean).html(a.paginacion);
        }
    });
}

function inicializarFormulario() {
    $("#formulario")[0].reset();
    $("#formulario input")[0].focus();
}
$(document).on('click', '#nuevo', function() {
    var boton = $(this);
    var accion = 'nuevo';
    cargarmantenimiento(boton, accion);
})
$(document).on('click', '.modificar', function() {
    var boton = $(this);
    var accion = 'modificar';
    cargarmantenimiento(boton, accion);
})

function cargarmantenimiento(boton, accion) {
    var opcion = boton.data('opcion');
    var bean = boton.data('bean');
    var id = '';
    if (accion == 'modificar') {
        id = boton.data('id');
    }
    var remove = 'success';
    var add = 'primary';
    var opcioncambio = '1';
    var html = '<i class="icon-chevron-left"></i> Atrás';
    var direccion = 'mant';
    var div = 'mantenimiento';
    if (opcion == '1') {
        remove = 'primary';
        add = 'success';
        opcioncambio = '0';
        html = '<i class="icon-plus"></i> Crear';
        direccion = 'frm';
        div = 'contenedor';
    }
    $('#nuevo').removeClass('btn-' + remove).addClass('btn-' + add).html(html);
    $('#nuevo').data('opcion', opcioncambio);
    link(direccion + bean + '.php?accion=' + accion + '&id=' + id, div);
}
$('th').click(function() {
    var table = $(this).parents('table').eq(0)
    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
    this.asc = !this.asc
    if (!this.asc) {
        rows = rows.reverse()
    }
    for (var i = 0; i < rows.length; i++) {
        table.append(rows[i])
    }
    setIcon($(this), this.asc);
})

function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index),
            valB = getCellValue(b, index)
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
    }
}

function getCellValue(row, index) {
    return $(row).children('td').eq(index).html()
}

function setIcon(element, asc) {
    $("th").each(function(index) {
        $(this).removeClass("sorting");
        $(this).removeClass("asc");
        $(this).removeClass("desc");
    });
    element.addClass("sorting");
    if (asc) element.addClass("asc");
    else element.addClass("desc");
}
$(document).on('click', '#elimina2', function() {
    var id = $(this).data('id');
    var par = '';
    var clase = $(this).data('clase');
    if (clase == 'Asignacion') {
        var par = '&idpersona=' + $(this).data('idpersona');
    }
    var route = '../controlador/cont' + clase + '.php?accion=eliminar&id=' + id + par;
    $.ajax({
        url: route,
        type: 'GET',
        success: function(a) {
            $('#mens_resultado').html(a);
            $('#mens_resultado').removeClass('hidden');
            $('#mens_alerta').addClass('hidden');
            $('#' + id).fadeOut("normal", function() {
                $(this).remove();
            });
            $('#cantfilas').html($('#cantfilas').html() - 1);
            $('#elimina2').addClass('hidden');
        }
    });
});

function gener() {
    $.ajax({
        url: "../controlador/contGener.php",
        success: function(a) {
            $("#gener").html(a);
        }
    });
}
setInterval(gener, 1000);
