<?php
include "../modelo/clsTelefono.php";

$accion   = $_GET['accion'];
$otelefono = new Telefono();

if ($accion == "comprobarExistenciaCelular") {
    $telefono = $_GET["numero"];
    try {
        $rs = $otelefono->comprobarNumero($telefono);
        if (!$rs) {
        	echo json_encode("1");
        } else {
            echo json_encode("2");
        }
    } catch (Exception $e) {
        echo json_encode("3");
    }
}