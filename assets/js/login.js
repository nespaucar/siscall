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
    var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    if ($('#email').val() == '') {
        $('.mensajeLogin').removeClass('hide').html('<strong>Mensaje: </strong> Debes Ingresar un Correo.');
        $('#email').focus();
        return false;
    }
    if (caract.test($('#email').val()) == false) {
        $('.mensajeLogin').removeClass('hide').html('<strong>Mensaje: </strong> Debes Ingresar un formato de Correo.');
        $('#email').focus();
        return false;
    }
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
                $('.mensajeLogin').removeClass('alert-danger').addClass('alert-success').removeClass('hide').html('<strong>Mensaje: </strong> Se envió correctamente tu contraseña a tu correo. Revisa tu SPAM.');
            } else {
                $('.mensajeLogin').removeClass('alert-success').addClass('alert-danger').removeClass('hide').html('<strong>Mensaje: </strong> Tu correo no coincide con ningún Registro.');
            }
        }
    });
});
$(document).on('click', '.linkLogin', function() {
    $('.mensajeLogin').addClass('hide');
});