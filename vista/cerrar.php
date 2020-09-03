<?php
	session_start();
	session_unset();
	session_destroy();
	header("Pragma: no-cache");
	echo "<script>window.open('frmLogin.php','_parent');</script>";
?>