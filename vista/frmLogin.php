<?php
session_start();
if (isset($_SESSION['nombre'])) {
    header("Location: frmGeneral.php");
    exit();
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>WPeru | Ingreso</title>
  <link rel="icon" type="image/png" href="../assets/img/logo2.png" />
  <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.css" />
  <link rel="stylesheet" href="../assets/css/login.css" />
  <link rel="stylesheet" href="../assets/plugins/magic/magic.css" />
  <link rel="stylesheet" href="../assets/css/theme.css">
  <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:700i" rel="stylesheet">
  <style>
    h3 {
      font-family: 'Merriweather Sans';
      color: red;
    }
    body {
      background-image: url('../assets/img/fondo.jpg');
      background-size: cover;
      background-attachment: fixed;
      -moz-background-size: cover;
      -webkit-background-size: cover;
      -o-background-size: cover;
    }
  </style>  
</head>
<body>
    <div class="container">
    <div class="text-center">
      <div class="text-center">
        <img src="../assets/img/logo2.png" class="mx-auto d-block" alt=" Logo" height="150" width="350" />
      </div>
    </div>
    <div class="tab-content">
        <div id="login" class="tab-pane active">
            <form class="form-signin" id="formLogin" method="POST" onsubmit="submitLogin();return false;">
              <div class="alert alert-danger hide mensajeLogin"></div>
                <h3 class="text-center">IDENTIFÍCATE</h3>
                <hr>
                <input type="text" placeholder="Usuario" name="nombre" id="nombre" class="form-control" />
                <input type="password" placeholder="Contraseña" name="pass" id="pass" class="form-control" />
                <br/>
                <button class="btn text-muted text-center btn-success" type="submit" onclick="submitLogin()">Ingresar</button>
            </form>
        </div>
        <div id="forgot" class="tab-pane">
            <form class="form-signin" id="formRecoverPass" method="POST">
              <div class="alert alert-danger hide mensajeLogin"></div>
                <h3 class="text-center">¿Olvidaste tu contraseña?</h3>
                <hr>
                <input type="email" required="required" placeholder="Tu E-mail" name="email" id="email" class="form-control" />
                <br />
                <button class="btn text-muted text-center btn-success" id="btnRecoverPass" type="button">Enviar Contraseña</button>                
            </form>
        </div>
    </div>
    <div class="text-center">
        <ul class="list-inline">
            <li><a class="text-muted linkLogin" href="#login" data-toggle="tab">Ingresar</a></li>
            <li><a class="text-muted linkLogin" href="#forgot" data-toggle="tab">¿Olvidaste tu contraseña?</a></li>
        </ul>
    </div>
</div>
<script src="../assets/plugins/jquery-2.0.3.min.js"></script>
<script src="../assets/plugins/bootstrap/js/bootstrap.js"></script>
<script src="../assets/js/login.js"></script>
</body>
<script>
    $(document).ready(function() {
      $('#nombre').focus();
    });
  </script>
</html>
<?php } ?>