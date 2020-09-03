$(document).on('click', '.grabar', function() {
    var bean = $(this).data('bean');
    var accion = $(this).data('accion');
    switch (bean) {
        case 'Tecnicos':
            validarUsuarios(accion, '2');
            break;
        case 'Administrativos':
            validarUsuarios(accion, '1');
            break;
        case 'CambioClave':
            validarCambioClave();
            break;
        case 'Clientes':
            validarClientes(accion);
            break;
        case 'EquiposMateriales':
            validarEquiposMateriales(accion);
            break;
        case 'Herramientas':
            validarHerramientas(accion);
            break;
        case 'Telefonos':
            validarTelefonos(accion);
            break;
        case 'ServiciosPaquetes':
            validarServiciosPaquetes(accion);
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
    var DNI = $('#DNI');
    var direccion = $('#direccion');
    var telefono = $('#telefono');
    var email = $('#email');
    var mensaje = '';
    if (!nombres.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Nombres Requeridos.</p>';
    }
    if (!apellidos.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Apellidos Requeridos.</p>';
    }
    if (!id_AB.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Número de Carnet Requerida.</p>';
    }
    if ((id_AB.val()).length != 6) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Número de Carnet Requiere 6 caracteres.</p>';
    } else {
        if (accion == 'modificar') {
            if (id_AB.val() != $('#id_AB_anterior').val()) {
                if (noduplicidad(id_AB.val(), 'id_AB', 'persona', 'Personal') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El Número de Carnet ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(id_AB.val(), 'id_AB', 'persona', 'Personal') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> El Número de Carnet ya existe.</p>';
            }
        }
    }
    if (!DNI.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> DNI Requerido.</p>';
    }
    if ((DNI.val()).length != 8) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> DNI Requiere 8 caracteres.</p>';
    } else {
        if (!solonumero(DNI.val())) {
            mensaje += '<p style="color: red;"><i class="icon-check"></i> Formato no válido de DNI.</p>';
        }
        if (accion == 'modificar') {
            if (DNI.val() != $('#DNI_anterior').val()) {
                if (noduplicidad(DNI.val(), 'DNI', 'persona', 'Personal') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El DNI ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(DNI.val(), 'DNI', 'persona', 'Personal') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> El DNI ya existe.</p>';
            }
        }
    }
    if (!direccion.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Dirección Requerida.</p>';
    }
    if (!telefono.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Teléfono Requerido.</p>';
    } else {
        if (!solonumero(telefono.val())) {
            mensaje += '<p style="color: red;"><i class="icon-check"></i> Formato no válido de Teléfono.</p>';
        }
    }
    if (!email.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Correo Requerido.</p>';
    } else {
        if (!formatoemail(email.val())) {
            mensaje += '<p style="color: red;"><i class="icon-check"></i> Formato incorrecto de Correo.</p>';
        }
        if (accion == 'modificar') {
            if (email.val() != $('#correo_anterior').val()) {
                if (noduplicidad(email.val(), 'email', 'usuario', 'Personal') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El correo ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(email.val(), 'email', 'usuario', 'Personal') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> El correo ya existe.</p>';
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
    if (!DNI.val() || (DNI.val()).length != 8) {
        DNI.focus();
        return false;
    } else {
        if (!solonumero(DNI.val())) {
            DNI.focus();
            return false;
        }
        if (accion == 'modificar') {
            if (DNI.val() != $('#DNI_anterior').val()) {
                if (noduplicidad(DNI.val(), 'DNI', 'persona', 'Personal') == 'true') {
                    DNI.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(DNI.val(), 'DNI', 'persona', 'Personal') == 'true') {
                DNI.focus();
                return false;
            }
        }
    }
    if (!direccion.val()) {
        direccion.focus();
        return false;
    }
    if (!telefono.val()) {
        telefono.focus();
        return false;
    } else {
        if (!solonumero(telefono.val())) {
            telefono.focus();
            return false;
        }
    }
    if (!email.val()) {
        email.focus();
        return false;
    } else {
        if (!formatoemail(email.val())) {
            email.focus();
            return false;
        }
        if (accion == 'modificar') {
            if (email.val() != $('#correo_anterior').val()) {
                if (noduplicidad(email.val(), 'email', 'usuario', 'Personal') == 'true') {
                    email.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(email.val(), 'email', 'usuario', 'Personal') == 'true') {
                email.focus();
                return false;
            }
        }
    }
    mantenimiento(accion);
    $('#id_AB_anterior').val(id_AB.val());
    $('#DNI_anterior').val(DNI.val());
    $('#correo_anterior').val(email.val());
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

function validarClientes(accion) {
    var nombre = $('#nombre');
    var nrodocumento = $('#nrodocumento');
    var direccion = $('#direccion');
    var telefono = $('#telefono');
    var correo = $('#correo');
    var mensaje = '';
    if (!nombre.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Nombres Requeridos.</p>';
    }
    if (!nrodocumento.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Nro. Documento Requerido.</p>';
    }
    if ((nrodocumento.val()).length != 20) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Nro. Documento Requiere 8 caracteres.</p>';
    } else {
        if (!solonumero(nrodocumento.val())) {
            mensaje += '<p style="color: red;"><i class="icon-check"></i> Formato no válido de Nro. Documento.</p>';
        }
        if (accion == 'modificar') {
            if (nrodocumento.val() != $('#nrodocumento_anterior').val()) {
                if (noduplicidad(nrodocumento.val(), 'nrodocumento', 'cliente', 'Cliente') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El Nro. Documento ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(nrodocumento.val(), 'nrodocumento', 'cliente', 'Cliente') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> El Nro. Documento ya existe.</p>';
            }
        }
    }
    if (!direccion.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Dirección Requerida.</p>';
    }
    if (!telefono.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Teléfono Requerido.</p>';
    } else {
        if (!solonumero(telefono.val())) {
            mensaje += '<p style="color: red;"><i class="icon-check"></i> Formato no válido de Teléfono.</p>';
        }
    }
    if (!correo.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Correo Requerido.</p>';
    } else {
        if (!formatoemail(correo.val())) {
            mensaje += '<p style="color: red;"><i class="icon-check"></i> Formato incorrecto de Correo.</p>';
        }
        if (accion == 'modificar') {
            if (correo.val() != $('#correo_anterior').val()) {
                if (noduplicidad(correo.val(), 'correo', 'cliente', 'Cliente') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El correo ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(correo.val(), 'correo', 'cliente', 'Cliente') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> El correo ya existe.</p>';
            }
        }
    }
    $('#mensajes').html(mensaje);
    if (!nombre.val()) {
        nombre.focus();
        return false;
    }
    if (!nrodocumento.val() || (nrodocumento.val()).length != 8) {
        nrodocumento.focus();
        return false;
    } else {
        if (!solonumero(nrodocumento.val())) {
            nrodocumento.focus();
            return false;
        }
        if (accion == 'modificar') {
            if (nrodocumento.val() != $('#nrodocumento_anterior').val()) {
                if (noduplicidad(nrodocumento.val(), 'nrodocumento', 'cliente', 'Cliente') == 'true') {
                    nrodocumento.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(nrodocumento.val(), 'nrodocumento', 'cliente', 'Cliente') == 'true') {
                nrodocumento.focus();
                return false;
            }
        }
    }
    if (!direccion.val()) {
        direccion.focus();
        return false;
    }
    if (!telefono.val()) {
        telefono.focus();
        return false;
    } else {
        if (!solonumero(telefono.val())) {
            telefono.focus();
            return false;
        }
    }
    if (!correo.val()) {
        correo.focus();
        return false;
    } else {
        if (!formatoemail(correo.val())) {
            correo.focus();
            return false;
        }
        if (accion == 'modificar') {
            if (correo.val() != $('#correo_anterior').val()) {
                if (noduplicidad(correo.val(), 'correo', 'cliente', 'Cliente') == 'true') {
                    correo.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(correo.val(), 'correo', 'cliente', 'Cliente') == 'true') {
                correo.focus();
                return false;
            }
        }
    }
    mantenimiento(accion);
    $('#nrodocumento_anterior').val(nrodocumento.val());
    $('#correo_anterior').val(correo.val());
}

function validarEquiposMateriales(accion) {
    var codigo = $('#codigo');
    var descripcion = $('#descripcion');
    var mensaje = '';
    if (!codigo.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Código Requerido.</p>';
    }
    if ((codigo.val()).length != 11) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Código requiere 11 caracteres.</p>';
    } else {
        if (accion == 'modificar') {
            if (codigo.val() != $('#codigo_anterior').val()) {
                if (noduplicidad(codigo.val(), 'codigo', 'equipomaterial', 'EquipoMaterial') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El código ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(codigo.val(), 'codigo', 'equipomaterial', 'EquipoMaterial') == 'true') {
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
                if (noduplicidad(descripcion.val(), 'descripcion', 'equipomaterial', 'EquipoMaterial') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> La descripción ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(descripcion.val(), 'descripcion', 'equipomaterial', 'EquipoMaterial') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> La descripción ya existe.</p>';
            }
        }
    }
    $('#mensajes').html(mensaje);
    if (!codigo.val()) {
        codigo.focus();
        return false;
    }
    if (!codigo.val() || (codigo.val()).length != 11) {
        codigo.focus();
        return false;
    } else {
        if (accion == 'modificar') {
            if (codigo.val() != $('#codigo_anterior').val()) {
                if (noduplicidad(codigo.val(), 'codigo', 'equipomaterial', 'EquipoMaterial') == 'true') {
                    codigo.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(codigo.val(), 'codigo', 'equipomaterial', 'EquipoMaterial') == 'true') {
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
                if (noduplicidad(descripcion.val(), 'descripcion', 'equipomaterial', 'EquipoMaterial') == 'true') {
                    descripcion.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(descripcion.val(), 'descripcion', 'equipomaterial', 'EquipoMaterial') == 'true') {
                descripcion.focus();
                return false;
            }
        }
    }
    mantenimiento(accion);
    $('#codigo_anterior').val(codigo.val());
    $('#descripcion_anterior').val(descripcion.val());
}

function validarHerramientas(accion) {
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
                if (noduplicidad(codigo.val(), 'codigo', 'herramienta', 'Herramienta') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El código ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(codigo.val(), 'codigo', 'herramienta', 'Herramienta') == 'true') {
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
                if (noduplicidad(descripcion.val(), 'descripcion', 'herramienta', 'Herramienta') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> La descripción ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(descripcion.val(), 'descripcion', 'herramienta', 'Herramienta') == 'true') {
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
                if (noduplicidad(codigo.val(), 'codigo', 'herramienta', 'Herramienta') == 'true') {
                    codigo.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(codigo.val(), 'codigo', 'herramienta', 'Herramienta') == 'true') {
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
                if (noduplicidad(descripcion.val(), 'descripcion', 'herramienta', 'Herramienta') == 'true') {
                    descripcion.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(descripcion.val(), 'descripcion', 'herramienta', 'Herramienta') == 'true') {
                descripcion.focus();
                return false;
            }
        }
    }
    mantenimiento(accion);
    $('#codigo_anterior').val(codigo.val());
    $('#descripcion_anterior').val(descripcion.val());
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

function validarServiciosPaquetes(accion) {
    var codigo = $('#codigo');
    var descripcion = $('#descripcion');
    var mensaje = '';
    if (!codigo.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Código Requerido.</p>';
    }
    if ((codigo.val()).length != 4) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Código requiere 4 caracteres.</p>';
    } else {
        if (accion == 'modificar') {
            if (codigo.val() != $('#codigo_anterior').val()) {
                if (noduplicidad(codigo.val(), 'codigo', 'serviciopaquete', 'ServicioPaquete') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> El código ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(codigo.val(), 'codigo', 'serviciopaquete', 'ServicioPaquete') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> El código ya existe.</p>';
            }
        }
    }
    if (!descripcion.val()) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> Descripción Requerida.</p>';
    }
    if ((descripcion.val()).length > 150) {
        mensaje += '<p style="color: red;"><i class="icon-check"></i> La descripción requiere como máximo 150 caracteres.</p>';
    } else {
        if (accion == 'modificar') {
            if (descripcion.val() != $('#descripcion_anterior').val()) {
                if (noduplicidad(descripcion.val(), 'descripcion', 'serviciopaquete', 'ServicioPaquete') == 'true') {
                    mensaje += '<p style="color: red;"><i class="icon-check"></i> La descripción ya existe.</p>';
                }
            }
        } else {
            if (noduplicidad(descripcion.val(), 'descripcion', 'serviciopaquete', 'ServicioPaquete') == 'true') {
                mensaje += '<p style="color: red;"><i class="icon-check"></i> La descripción ya existe.</p>';
            }
        }
    }
    $('#mensajes').html(mensaje);
    if (!codigo.val()) {
        codigo.focus();
        return false;
    }
    if (!codigo.val() || (codigo.val()).length != 4) {
        codigo.focus();
        return false;
    } else {
        if (accion == 'modificar') {
            if (codigo.val() != $('#codigo_anterior').val()) {
                if (noduplicidad(codigo.val(), 'codigo', 'serviciopaquete', 'ServicioPaquete') == 'true') {
                    codigo.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(codigo.val(), 'codigo', 'serviciopaquete', 'ServicioPaquete') == 'true') {
                codigo.focus();
                return false;
            }
        }
    }
    if (!descripcion.val()) {
        descripcion.focus();
        return false;
    }
    if (!descripcion.val() || (descripcion.val()).length > 150) {
        descripcion.focus();
        return false;
    } else {
        if (accion == 'modificar') {
            if (descripcion.val() != $('#descripcion_anterior').val()) {
                if (noduplicidad(descripcion.val(), 'descripcion', 'serviciopaquete', 'ServicioPaquete') == 'true') {
                    descripcion.focus();
                    return false;
                }
            }
        } else {
            if (noduplicidad(descripcion.val(), 'descripcion', 'serviciopaquete', 'ServicioPaquete') == 'true') {
                descripcion.focus();
                return false;
            }
        }
    }
    mantenimiento(accion);
    $('#codigo_anterior').val(codigo.val());
    $('#descripcion_anterior').val(descripcion.val());
}