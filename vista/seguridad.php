<?php
	session_name();
    date_default_timezone_set("America/Lima");
    $nombre = $_SESSION['nombre'];
    $tipo = $_SESSION['tipo'];
    
	if (!isset($_SESSION["nombre"])){
    	session_start();
		session_unset();
		session_destroy();
		header("Pragma: no-cache");
		echo "<script>window.open('frmLogin.php','_parent');</script>";
    }
?>
