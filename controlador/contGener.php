<?php
include "../modelo/clsPersonal.php";

$persona = new Personal();

session_start();
$idpersona = $_SESSION['id'];
$idempresa = $_SESSION['idempresa'];

$estado = $persona->gener($idpersona, $idempresa);

if($estado == '0') {
	session_unset();
	session_destroy();
	header("Pragma: no-cache");
	echo "<script>window.open('frmLogin.php','_parent');</script>";
}
?>
