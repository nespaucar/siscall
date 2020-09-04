$(document).on('click', '.grabar', function() {
    var bean = $(this).data('bean');
    var accion = $(this).data('accion');
    switch (bean) {
        case 'Personas':
            var tipo = $(this).data('tipo');
            validarUsuarios(accion, tipo);
            break;
        case 'CambioClave':
            validarCambioClave();
            break;
        case 'Telefonos':
            validarTelefonos(accion);
            break;
    }
});

function solonumero(numero) {
    if (!/^([0-9])*$/.test(numero)) {
        return false;
    }
    return true;
}

function solodecimal(numero) {
    var RE = /^\d*\.?\d*$/;
    if (RE.test(numero)) {
        return true;
    } else {
        return false;
    }
}

function formatoemail(email) {
    if (!/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email)) {
        return false;
    }
    return true;
}

function validarUsuarios(accion, tipo) {
    var nombres = $('#nombres');
    var apellidos = $('#apellidos');
    var id_AB = $('#id_AB');
    var direccion = $('#direccion');
    var mensaje = '';
    if (!nombres.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Nombres Requeridos.</p>';
    }
    if (!apellidos.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Apellidos Requeridos.</p>';
    }
    if (!id_AB.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Código Requerida.</p>';
    }
    if ((id_AB.val()).length != 6) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Código Requiere 6 caracteres.</p>';
    } else {
        if (accion == 'modificar') {
            if (id_AB.val() != $('#id_AB_anterior').val()) {
                if (noduplicidad(id_AB.val(), 'id_AB', 'persona', 'Personal') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El Código ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(id_AB.val(), 'id_AB', 'persona', 'Personal') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> El Código ya existe.</p>';
            }
        }
    }
    $('#mensajes').html(mensaje);
    if (!nombres.val()) {
        nombres.focus();
        return false;
    }
    if (!apellidos.val()) {
        apellidos.focus();
        return false;
    }
    if (!id_AB.val() || (id_AB.val()).length != 6) {
        id_AB.focus();
        return false;
    } else {
        if (accion == 'modificar') {
            if (id_AB.val() != $('#id_AB_anterior').val()) {
                if (noduplicidad(id_AB.val(), 'id_AB', 'persona', 'Personal') == 'true') {
                    id_AB.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(id_AB.val(), 'id_AB', 'persona', 'Personal') == 'true') {
                id_AB.focus();
                return false;
            }
        }
    }
    mantenimiento(accion);
    if (accion !== 'modificar') {
        setTimeout(generarClave, 1000);
    }
}

function generarClave() {
    $.ajax({
        type: "GET",
        url: '../controlador/contPersonal.php?accion=generarClave',
        beforeSend: function() {
            $('#id_AB').val("Cargando...");
            $("#grabar").attr("disabled", true);
        },
        success: function(a) {
            $('#id_AB').val(a);
            $("#grabar").attr("disabled", false);
        }
    });
}

function validarCambioClave() {
    var claveactual = $('#claveactual');
    var clavenueva1 = $('#clavenueva1');
    var clavenueva2 = $('#clavenueva2');
    var spanclaveactual = $('#spanclaveactual');
    var spanclavenueva = $('#spanclavenueva');
    var clavenueva2 = $('#clavenueva2');
    if (!claveactual.val()) {
        spanclavenueva.html('');
        spanclaveactual.html('Debes digitar la clave actual.');
        claveactual.focus();
        return false;
    } else {
        if (noduplicidad(claveactual.val(), 'pass', 'usuario', 'Personal') == 'false') {
            spanclavenueva.html('');
            spanclaveactual.html('No coincide con tu clave actual.');
            claveactual.focus();
            return false;
        }
    }
    if (!clavenueva1.val()) {
        spanclaveactual.html('');
        spanclavenueva.html('Debes digitar la clave actual.');
        clavenueva1.focus();
        return false;
    }
    if (!clavenueva2.val()) {
        spanclaveactual.html('');
        spanclavenueva.html('Debes digitar la clave actual.');
        clavenueva2.focus();
        return false;
    }
    if (clavenueva1.val() != clavenueva2.val()) {
        spanclaveactual.html('');
        $('#spanclavenueva').html('Las Claves no Coinciden.');
        clavenueva2.val('');
        clavenueva1.focus();
        return false;
    } else {
        $('#spanclavenueva').html('');
    }
    $.ajax({
        type: "GET",
        url: '../controlador/contPersonal.php?accion=cambiarclave&clavenueva=' + clavenueva1.val(),
        success: function(a) {
            $('#spanclavenueva').css('color', 'green').html(a);
            $('#formularioCambioClave')[0].reset();
            $('#formularioCambioClave input')[0].focus();
        }
    })
}

function validarTelefonos(accion) {
    var codigo = $('#codigo');
    var descripcion = $('#descripcion');
    var mensaje = '';
    if (!codigo.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Código Requerido.</p>';
    }
    if ((codigo.val()).length != 10) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Código requiere 10 caracteres.</p>';
    } else {
        if (accion == 'modificar') {
            if (codigo.val() != $('#codigo_anterior').val()) {
                if (noduplicidad(codigo.val(), 'codigo', 'telefono', 'Telefono') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El código ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(codigo.val(), 'codigo', 'telefono', 'Telefono') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> El código ya existe.</p>';
            }
        }
    }
    if (!descripcion.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Descripción Requerida.</p>';
    }
    if ((descripcion.val()).length > 100) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> La descripción requiere como máximo 100 caracteres.</p>';
    } else {
        if (accion == 'modificar') {
            if (descripcion.val() != $('#descripcion_anterior').val()) {
                if (noduplicidad(descripcion.val(), 'descripcion', 'telefono', 'Telefono') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> La descripción ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(descripcion.val(), 'descripcion', 'telefono', 'Telefono') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> La descripción ya existe.</p>';
            }
        }
    }
    $('#mensajes').html(mensaje);
    if (!codigo.val()) {
        codigo.focus();
        return false;
    }
    if (!codigo.val() || (codigo.val()).length != 10) {
        codigo.focus();
        return false;
    } else {
        if (accion == 'modificar') {
            if (codigo.val() != $('#codigo_anterior').val()) {
                if (noduplicidad(codigo.val(), 'codigo', 'telefono', 'Telefono') == 'true') {
                    codigo.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(codigo.val(), 'codigo', 'telefono', 'Telefono') == 'true') {
                codigo.focus();
                return false;
            }
        }
    }
    if (!descripcion.val()) {
        descripcion.focus();
        return false;
    }
    if (!descripcion.val() || (descripcion.val()).length > 100) {
        descripcion.focus();
        return false;
    } else {
        if (accion == 'modificar') {
            if (descripcion.val() != $('#descripcion_anterior').val()) {
                if (noduplicidad(descripcion.val(), 'descripcion', 'telefono', 'Telefono') == 'true') {
                    descripcion.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(descripcion.val(), 'descripcion', 'telefono', 'Telefono') == 'true') {
                descripcion.focus();
                return false;
            }
        }
    }
    mantenimiento(accion);
    $('#codigo_anterior').val(codigo.val());
    $('#descripcion_anterior').val(descripcion.val());
}