<?php
    include("keys.php");
?>

<html lang="es">
    <head> 
        <title>SisCall | GetCode</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <link rel="icon" type="image/png" href="../assets/img/logo2.png" />
        <link rel="stylesheet" href="css/estilos.css">        
        <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="../assets/css/login.css" />
        <link rel="stylesheet" href="../assets/plugins/magic/magic.css" />
        <link rel="stylesheet" href="../assets/css/theme.css">
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:700i" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="text-center">
                <img src="../assets/img/logo2.png" class="mx-auto d-block img img-responsive img-thumbnail" alt=" Logo"/>
            </div>
        </header>
        <hr>
        <section class="container">
            <form  class="form-signin" method="post" id="loginForm" onsubmit="return false;" style="background-color: white; border-radius: 20px;">
                <div class="form-group">
                    <label for="codigo">Código</label>
                    <hr>
                    <input type="codigo" class="form-control" id="codigo" name="codigo" maxlength="6" placeholder="Ingresa tu código de cliente">
                </div>
                <br>
                <input type="hidden" name="google-response-token" id="google-response-token">
                <button type="button" id="btnSubmit" onclick="enviarForm();" class="btn btn-primary form-control" >Enviar</button>
            </form>
            <br>
            <div id="message" class="text-center form-signin"></div>
        </section>
    </body>
</html>

<script src="../assets/plugins/jquery-2.0.3.min.js"></script>
<script src="../assets/plugins/bootstrap/js/bootstrap.js"></script>
<script src="../assets/js/login.js"></script>
<script src='https://www.google.com/recaptcha/api.js?render=<?php echo SITE_KEY; ?>'></script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#codigo").focus();
    });
    var imgCargando = "<img src='../assets/img/cargando.gif' width='100' height='100'/>";
    function enviarForm() {
        if($("#codigo").val().length < 6) {
            $("#message").html("<div class='alert alert-danger'> El código debe tener 6 caracteres. </div>");
            $("#codigo").focus();
        } else {
            enviarForm2();
        }
    }

    function enviarForm2() {
        var form = $('#loginForm');
        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: 'recaptcha.php',
            data: form.serialize(),
            beforeSend: function() {
                $('#message').html(imgCargando);
                $('#btnSubmit').attr("disabled", true);
                $('#codigo').attr("readonly", true);
            },
            success: function(data) {
                $('#message').empty();
                $('#message').html(data);
                //setTimeout(recargar, 2500);
            }
        });
    }

    function recargar() {
        window.location.reload();
    }
    grecaptcha.ready(function() {
    grecaptcha.execute('<?php echo SITE_KEY; ?>', {action: 'homepage'})
        .then(function(token) {            
            $('#google-response-token').val(token);
        });
    });
</script>