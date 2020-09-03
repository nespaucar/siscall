$(document).on('keyup', 'input[class=fechita]', function() {
    $(this).focus().val('');
});
$(document).ready(function() {
    $(function() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: false,
            changeMonth: false,
            changeYear: false,
            inline: true
        });
    });
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $.ajax({
        url: '../controlador/contPersonal.php?accion=obtenerTecnicos',
        type: 'GET',
        success: function(a) {
            $('#cboTecnicos').html(a).chosen({
                width: "100%",
            });
        },
    });
    $.ajax({
        url: '../controlador/contEquipoMaterial.php?accion=obtenerProductos',
        type: 'GET',
        success: function(a) {
            $('#cboProductos').append(a).chosen({
                width: "100%",
            });
        },
    });
});

function dias_entre(date1, date2) {
    if (date1.indexOf("-") != -1) {
        date1 = date1.split("-");
    } else {
        return 0;
    }
    if (date2.indexOf("-") != -1) {
        date2 = date2.split("-");
    } else {
        return 0;
    }
    if (parseInt(date1[0], 10) >= 1000) {
        var sDate = new Date(date1[0] + "/" + date1[1] + "/" + date1[2]);
    } else {
        return 0;
    }
    if (parseInt(date2[0], 10) >= 1000) {
        var eDate = new Date(date2[0] + "/" + date2[1] + "/" + date2[2]);
    } else {
        return 0;
    }
    var one_day = 1000 * 60 * 60 * 24;
    var daysApart = Math.ceil((sDate.getTime() - eDate.getTime()) / one_day);
    return daysApart;
}

function daysInMonth(humanMonth, year) {
    return new Date(year || new Date().getFullYear(), humanMonth, 0).getDate();
}

function hide() {
    $('#_dia').addClass('hide');
    $('#rango_dia').addClass('hide');
    $('#_mes').addClass('hide');
    $('#rango_mes').addClass('hide');
    $('#_ano').addClass('hide');
    $('#rango_ano').addClass('hide');
    $('#mensajeReporte').html('');
}

function opcionreporte(id1, id2, id3) {
    $("#" + id2).attr('style', 'background-color: #D5D5D5');
    $("#" + id1).removeAttr('style');
    $("#" + id3).removeAttr('style');
    if (id2 == 'pccyf') {
        $("#opcion_concepto_cc").removeClass('hide');
        $("#opcion_fecha").addClass('hide');
    } else {
        $("#opcion_concepto_cc").addClass('hide');
        $("#opcion_fecha").removeClass('hide');
        if (id2 == 'pf') {
            $('.btnR').removeAttr('form').attr('form', '_dia');
            $('#tipo').val('D');
            $('#intervalo').val('N');
            $("#_dia").removeClass('hide');
            $("#_mes").addClass('hide');
            $("#_ano").addClass('hide');
        }
    }
    $('div[id=ap_intervalo]').show();
    $('div[id=ap_incluir]').show();
}

function success(type, route, corr) {
    $("#mensajeReporte").css('color', 'red').html('Cargando...');
    var idtecnico = $('#cboTecnicos').val();
    var idproducto = $('#cboProductos').val();
    route += '&idtecnico=' + idtecnico + '&idproducto=' + idproducto;
    setTimeout(function() {
        if (type == '1') {
            window.open(route, null, 'height=500,width=700,status=yes,toolbar=no,menubar=no,location=no,titlebar=no');
        } else {
            location.href = route;
        }
        $('#mensajeReporte').css('color', 'green').html(corr);
    }, 1500);
}
$(document).ready(function() {
    hide();
    $('#_dia').removeClass('hide');
    $('select[id=intervalo]').change(function() {
        hide();
        if ($(this).val() == 'N') {
            if ($('select[id=tipo]').val() == 'D') {
                $('#_dia').removeClass('hide');
                $('.btnR').removeAttr('form').attr('form', '_dia');
            } else if ($('select[id=tipo]').val() == 'M') {
                $('#_mes').removeClass('hide');
                $('.btnR').removeAttr('form').attr('form', '_mes');
            } else {
                $('#_ano').removeClass('hide');
                $('.btnR').removeAttr('form').attr('form', '_ano');
            }
        } else {
            if ($('select[id=tipo]').val() == 'D') {
                $('#rango_dia').removeClass('hide');
                $('.btnR').removeAttr('form').attr('form', 'rango_dia');
            } else if ($('select[id=tipo]').val() == 'M') {
                $('#rango_mes').removeClass('hide');
                $('.btnR').removeAttr('form').attr('form', 'rango_mes');
            } else {
                $('#rango_ano').removeClass('hide');
                $('.btnR').removeAttr('form').attr('form', 'rango_ano');
            }
        }
    })
    $('select[id=tipo]').change(function() {
        hide();
        if ($(this).val() == 'D') {
            if ($('select[id=intervalo]').val() == 'N') {
                $('#_dia').removeClass('hide');
                if ($('.btnR').attr('form') != 'terceros') {
                    $('.btnR').removeAttr('form').attr('form', '_dia');
                }
            } else {
                $('#rango_dia').removeClass('hide');
                if ($('.btnR').attr('form') != 'terceros') {
                    $('.btnR').removeAttr('form').attr('form', 'rango_dia');
                }
            }
        } else if ($(this).val() == 'M') {
            if ($('select[id=intervalo]').val() == 'N') {
                $('#_mes').removeClass('hide');
                if ($('.btnR').attr('form') != 'terceros') {
                    $('.btnR').removeAttr('form').attr('form', '_mes');
                }
            } else {
                $('#rango_mes').removeClass('hide');
                if ($('.btnR').attr('form') != 'terceros') {
                    $('.btnR').removeAttr('form').attr('form', 'rango_mes');
                }
            }
        } else {
            if ($('select[id=intervalo]').val() == 'N') {
                $('#_ano').removeClass('hide');
                if ($('.btnR').attr('form') != 'terceros') {
                    $('.btnR').removeAttr('form').attr('form', '_ano');
                }
            } else {
                $('#rango_ano').removeClass('hide');
                if ($('.btnR').attr('form') != 'terceros') {
                    $('.btnR').removeAttr('form').attr('form', 'rango_ano');
                }
            }
        }
    })
})
$(document).on('click', '.btnR', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    var dia = $('#dia').val();
    var mes = $('#mes').val();
    var mano = $('#mano').val();
    var ano = $('#ano').val();
    var di = $('#di').val();
    var df = $('#df').val();
    var mi = $('#mi').val();
    var ami = $('#ami').val();
    var mf = $('#mf').val();
    var amf = $('#amf').val();
    var ai = $('#ai').val();
    var af = $('#af').val();
    var intervalo = $('#intervalo').val();
    var tipo = $('#tipo').val();
    var corr = 'Reporte Generado Correctamente';
    var incorr = 'Datos Incorrectos';
    var incorrecto = 'Selecciona una fecha anterior';
    var raizroute = '../controlador/contLiquidacion.php?accion=';
    if ($(this).val() == 'btnPDF') {
        var type = 2;
    } else if ($(this).val() == 'btnEXC') {
        var type = 3;
    } else {
        var type = 1;
    }
    var hoy = new Date();
    if ($(this).attr('form') == '_dia') {
        if (dia) {
            var fech = dia.split('-');
            var fech1 = new Date(fech[0], fech[1] - 1, fech[2]);
            if (hoy < fech1) {
                $('#mensajeReporte').css('color', 'orange').html(incorrecto);
                $('#dia').focus();
            } else {
                var route = raizroute + 'reporteLiquidacion&tipo=' + type + '&fecha1=' + dia + '&fecha2=' + dia + '&rango=DIARIO';
                success(type, route, corr);
            }
        } else {
            $('#mensajeReporte').css('color', 'red').html(incorr);
            $('#dia').focus();
        }
    } else if ($(this).attr('form') == '_mes') {
        var fech = new Date(mano, mes, 1);
        if (hoy < fech) {
            $('#mensajeReporte').css('color', 'orange').html(incorrecto);
        } else {
            var route = raizroute + 'reporteLiquidacion&tipo=' + type + '&fecha1=' + mano + '-' + mes + '-01&fecha2=' + mano + '-' + mes + '-31&rango=MENSUAL';
            success(type, route, corr);
        }
    } else if ($(this).attr('form') == '_ano') {
        var anno = hoy.getFullYear();
        if (anno < parseInt(ano)) {
            $('#mensajeReporte').css('color', 'orange').html(incorrecto);
        } else {
            var route = raizroute + 'reporteLiquidacion&tipo=' + type + '&fecha1=' + ano + '-01-01&fecha2=' + ano + '-12-31&rango=ANUAL';
            success(type, route, corr);
        }
    } else if ($(this).attr('form') == 'rango_dia') {
        if (dias_entre(df, di) > 0) {
            var fi = di.split('-');
            var ff = df.split('-');
            var fech = new Date(fi[0], fi[1] - 1, fi[2]);
            var fech1 = new Date(ff[0], ff[1] - 1, ff[2]);
            if (fech > hoy) {
                $('#di').focus();
                $('#df').val('');
                $('#mensajeReporte').css('color', 'orange').html(incorrecto);
                return false;
            } else {
                var route = raizroute + 'reporteLiquidacion&tipo=' + type + '&fecha1=' + di + '&fecha2=' + df + '&rango=DIARIO';
                success(type, route, corr);
            }
        } else {
            $('#di').focus();
            $('#df').val('');
            $('#mensajeReporte').css('color', 'red').html(incorr);
        }
    } else if ($(this).attr('form') == 'rango_mes') {
        var fech1 = new Date(ami, mi, 1);
        var fech2 = new Date(amf, mf, 1);
        if (hoy < fech1) {
            $('#mensajeReporte').css('color', 'orange').html(incorrecto);
        } else {
            if ((parseInt(amf) - parseInt(ami) > 0) || (parseInt(amf) - parseInt(ami) == 0 && parseInt(mf) - parseInt(mi) > 0)) {
                var route = raizroute + 'reporteLiquidacion&tipo=' + type + '&fecha1=' + ami + '-' + mi + '-01&fecha2=' + amf + '-' + mf + '-31&rango=MENSUAL';
                success(type, route, corr);
            } else {
                $('#mensajeReporte').css('color', 'red').html(incorr);
            }
        }
    } else if ($(this).attr('form') == 'rango_ano') {
        var anno = hoy.getFullYear();
        if (anno < parseInt(ai)) {
            $('#mensajeReporte').css('color', 'orange').html(incorrecto);
        } else {
            if (parseInt(af) - parseInt(ai) > 0) {
                var route = raizroute + 'reporteLiquidacion&tipo=' + type + '&fecha1=' + ai + '-01-01&fecha2=' + af + '-12-31&rango=ANUAL';
                success(type, route, corr);
            } else {
                $('#mensajeReporte').css('color', 'red').html(incorr);
            }
        }
    }
})