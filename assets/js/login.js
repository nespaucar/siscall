$(function() {
    $('.list-inline li > a').click(function() {
        var activeForm = $(this).attr('href') + ' > form';
        $(activeForm).addClass('magictime swap');
        setTimeout(function() {
            $(activeForm).removeClass('magictime swap');
        }, 1000);
    });
});

function submitLogin() {
    if ($('#nombre').val() == '') {
        $('.mensajeLogin').removeClass('hide').html('<strong>Mensaje: </strong> Debes Ingresar un Usuario.');
        $('#nombre').focus();
        return false;
    }
    if ($('#pass').val() == '') {
        $('.mensajeLogin').removeClass('hide').html('<strong>Mensaje: </strong> Debes Ingresar una Cotraseña.');
        $('#pass').focus();
        return false;
    }
    $.ajax({
        url: "../controlador/contPersonal.php?accion=login",
        type: 'POST',
        data: $('#formLogin').serialize(),
        beforeSend: function() {
            $(".mensajeLogin").html("<center><img src='../assets/img/cargando.gif' width='50' height='50' /></center>");
        },
        success: function(a) {
            if (a == '1') {
                $('.mensajeLogin').removeClass('alert-danger').addClass('alert-success').removeClass('hide').html('<strong>Mensaje: </strong> Autenticación Correcta.');
                window.location.href = "../vista/frmGeneral.php";
            } else if (a == '2') {
                $('.mensajeLogin').removeClass('alert-success').addClass('alert-danger').removeClass('hide').html('<strong>Mensaje: </strong> No estás Habilitado.')
            } else {
                $('.mensajeLogin').removeClass('alert-success').addClass('alert-danger').removeClass('hide').html('<strong>Mensaje: </strong> Autenticación Incorrecta.')
            }
        }
    });
};
$(document).on('click', '#btnRecoverPass', function() {
    //var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    if ($('#name').val() == '') {
        $('.mensajeLogin').removeClass('hide').html('<strong>Mensaje: </strong> Debes Ingresar tu Clave.');
        $('#name').focus();
        return false;
    }
    if (($('#name').val()).length !== 6) {
        $('.mensajeLogin').removeClass('hide').html('<strong>Mensaje: </strong> Tu clave debe tener 6 caracteres.');
        $('#name').focus();
        return false;
    }
    /*if (caract.test($('#name').val()) == false) {
        $('.mensajeLogin').removeClass('hide').html('<strong>Mensaje: </strong> Debes Ingresar un formato de Correo.');
        $('#name').focus();
        return false;
    }*/
    $.ajax({
        url: "../controlador/contPersonal.php?accion=recoverPass",
        type: 'POST',
        data: $('#formRecoverPass').serialize(),
        beforeSend: function() {
            $(".mensajeLogin").html("<center><img src='../assets/img/cargando.gif' width='50' height='50' /></center>");
        },
        success: function(a) {
            eval(a);
            if (respuesta == '1') {
                $('.mensajeLogin').removeClass('alert-danger').addClass('alert-success').removeClass('hide').html('<strong>Mensaje: </strong> Se envió correctamente tu contraseña a tu número de celular.');
            } else if (respuesta == '2') {
                $('.mensajeLogin').removeClass('alert-success').addClass('alert-danger').removeClass('hide').html('<strong>Mensaje: </strong> No te encuentras registrado.');
            } else if (respuesta == '4') {
                $('.mensajeLogin').removeClass('alert-success').addClass('alert-danger').removeClass('hide').html('<strong>Mensaje: </strong> No tienes números de celulares registrados.');
            } else if (respuesta == '3') {
                $('.mensajeLogin').removeClass('alert-success').addClass('alert-danger').removeClass('hide').html('<strong>Mensaje: </strong> Ocurrió un error, vuelve a intentarlo.');
            }
            $('#name').val("");
            $('#name').focus();
        }
    });
});
$(document).on('click', '.linkLogin', function() {
    $('.mensajeLogin').addClass('hide');
});