<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
include "../modelo/clsTelefono.php";
include "../modelo/clsPersonal.php";
include("keys.php");
require '../twilio-php-main/src/Twilio/autoload.php';
use Twilio\Rest\Client;

$oPersona = new Personal();
$oTelefono = new Telefono();
$client = new Client(TWILIO_ID, TWILIO_TOKEN);

if($_POST['google-response-token']) {
  $googleToken = $_POST['google-response-token'];
  $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_KEY."&response={$googleToken}"); 
  $response = json_decode($response);
  $response = (array) $response;
  
  if($response['success'] && ($response['score'] && $response['score'] > 0.5)) {
    $envio = 1;
    $mensaje = '';
    //COMPRUEBO EXISTENCIA DE CÓDIGO
    $codigo = $_POST['codigo'];
    try {
      $rs = $oPersona->comprobarExistenciaCodigo($codigo);
      if ($rs->rowCount() > 0) {
        //ENVÍO LOS MENSAJES
        $idpersona = 0;
        $nombre = '';
        foreach ($rs as $row) {
          $idpersona = $row['id'];
          $nombre = $row['nombre'];
        }
        $celulares = $oTelefono->cargarNumeros($idpersona);
        if ($celulares->rowCount() > 0) {
          //ENVÍO MENSAJE A LOS NÚMEROS AFILIADOS A ESTE CLIENTE CON TWILIO
          foreach ($celulares as $row) {
            $estado = "No Enviado";
            $numero = $row['numero'];
            $mensTwilio = $oTelefono->obtenerConfiguracionMensaje();
            $messageTwilio = $client->messages->create(
                // the number you'd like to send the message to
                '+51' . $numero,
                //'+51956930067',
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => '+15124563240',
                    // the body of the text message you'd like to send
                    'body' => $mensTwilio
                ]
            );
            $estado = $messageTwilio->status;
            //REGISTRO EN BASE DE DATOS EL ENVIO DEL MENSAJE
            $nuevomensaje = $oTelefono->nuevoMensaje($idpersona, $nombre, $mensTwilio, $numero, $estado);
          } 
        } else {
          //NO EXISTEN CELULARES PARA ESTE CLIENTE
          $envio = 4;
        }
      } else {
        $envio = 2;
      }
    } catch (Exception $e) {
      $envio = 3;
    }

    if($envio === 1) {
      $mensaje = "<div class='alert alert-success'> Se enviaron los mensajes correctamente. </div>";
    } else if($envio === 2) {
      $mensaje = "<div class='alert alert-danger'> Código no se encuentra en nuestros registros. </div>";
    } else if($envio === 3) {
      $mensaje = "<div class='alert alert-danger'> Ocurrió un error, vuelva a intentar. </div>";
    } else if($envio === 4) {
      $mensaje = "<div class='alert alert-danger'> Este cliente no tiene teléfonos registrados. </div>";
    }

    echo $mensaje;

  } else {
    echo "<div class='alert alert-danger'> Ocurrió un error, vuelva a intentar. </div>";
  }
}