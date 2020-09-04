<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="UTF-8" />
	    <title>Bienvenido | WPERU</title>
	    <meta content="width=device-width, initial-scale=1.0" name="viewport">
	    <link rel="stylesheet" href="../assets/plugins/bootstrap/css/bootstrap.css">
	    <link rel="stylesheet" href="../assets/css/chosen.min.css">
	    <link rel="stylesheet" href="../assets/css/main.css">
	    <link rel="stylesheet" href="../assets/css/theme.css">
	    <link rel="stylesheet" href="../assets/css/MoneAdmin.css">
	    <link rel="stylesheet" href="../assets/plugins/Font-Awesome/css/font-awesome.css">
	    <link rel="stylesheet" href="../assets/css/dataTables.bootstrap.min.css">
	    <link rel="stylesheet" href="../assets/css/ordernarTablas.css">
	    <link rel="stylesheet" href="../assets/css/jquery-ui.css">
	    <link rel="icon" type="image/png" href="../assets/img/<?php echo $_SESSION['logo2'];?>">
	    <link href="https://fonts.googleapis.com/css?family=Arima+Madurai|Boogaloo|Carter+One|Oswald|Montserrat|Merriweather+Sans:700i" rel="stylesheet">
	    <style>
	    	.select {
	    		color: blue;
	    	}
	    	.cuerpolargo {
	    		height: 400px;
	    		overflow: auto;
	    	}
	    	#contenedor {
		      background-image: url('../assets/img/background.jpg');
		      background-size: cover;
		      background-attachment: fixed;
		      -moz-background-size: cover;
		      -webkit-background-size: cover;
		      -o-background-size: cover;
		    }
		    .panel-heading {
		    	padding-top: 0;
		    	padding-bottom: 0;
		    	margin-top: 0;
		    	margin-bottom: 0;
		    }
		    .pager {
		    	padding-top: 0;
		    	padding-bottom: 0;
		    	margin-top: 0;
		    	margin-bottom: 0;
		    }
		    .panel {
				opacity: 0.9;
		    }
		    .panel-body {
		    	padding-bottom: 0;
		    	margin-bottom: 0;
		    }
		    .modalChico {
		        width: 25% !important;
		    }
		    .modalMedio {
		        width: 80% !important;
		    }
	    </style>	    
	</head>
	<body class="padTop53">
	    <div id="wrap">
	        <div id="top">
	            <nav class="navbar navbar-inverse navbar-fixed-top " style="padding-top: 10px;">
	                <a data-original-title="Show/Hide Menu" data-placement="bottom" data-tooltip="tooltip" class="accordion-toggle btn btn-primary btn-sm visible-xs" data-toggle="collapse" href="#menu" id="menu-toggle">
	                    <i class="icon-align-justify"></i>
	                </a>
	                <ul class="nav navbar-top-links navbar-right">
	                    <!--ADMIN SETTINGS SECTIONS -->
	                    <li class="dropdown">
	                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
	                            <i class="icon-user "></i>&nbsp; <?php echo $_SESSION['nombre'] . ' / ' . $_SESSION['tipo'] . ' / ' . $_SESSION['nombreempresa']; ?>&nbsp;<i class="icon-chevron-down "></i>
	                        </a>
	                        <ul class="dropdown-menu dropdown-user">
	                            <li><a href="#" id="btnPropUsuarioPropio" data-toggle="modal" data-target="#propUsuModal"><i class="icon-eye-close"></i> Cambiar Clave </a>
	                            </li>
	                            <li class="divider"></li>
	                            <li><a href="cerrar.php"><i class="icon-signout"></i> Cerrar Sesión </a>
	                            </li>
	                        </ul>
	                    </li>
	                    <!--END ADMIN SETTINGS -->
	                </ul>
	            </nav>
	        </div>
	        <!-- END HEADER SECTION -->

	        <!-- MENU SECTION -->
	       <div id="left" style="position: fixed;">
	            <div class="media user-media well-small">
	                <a class="user-link" href="javascript:void(0)" onclick="link('frmPresentacion.php')">
	                    <img height="96" width="196" class="media-object img-thumbnail user-img" alt="User Picture" src="../assets/img/<?php echo $_SESSION['logo'];?>"/>
	                </a>
	            </div>

	            <ul id="menu" class="collapse">
	                <li class="panel">
	                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#mantenimientos">
	                        <i class="icon-tasks"> </i> Mantenimientos
	                        <span class="pull-right">
	                          <i class="icon-angle-left"></i>
	                        </span>&nbsp;<span class="label label-success">3</span>&nbsp;
	                    </a>
	                    <ul class="collapse" id="mantenimientos">
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmUsuarios.php')">
	                        		<i class="icon-user-md"></i> Usuarios
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmPersonas.php')">
	                        		<i class="glyphicon glyphicon-eye-open"></i> Personas
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmCelulares.php')">
	                        		<i class="glyphicon glyphicon-wrench"></i> Celulares
	                        	</a>
	                        </li>
	                    </ul>
	                </li>
	            </ul>
	        </div>

			
	        <!--END MENU SECTION -->

	        <!--PAGE CONTENT -->
	        <div id="content">
	            <div class="inner" id="contenedor"></div>
	        </div>
	        <!--END PAGE CONTENT -->
	    </div>

	    <div id="footer" style="width: 100%; position: absolute; bottom: 0; position: fixed">
	        <p>&copy;  GarzaSoft2020 &nbsp; 922179451&nbsp;</p>
	    </div>

	    <div id="gener"></div>

	    <script src="../assets/plugins/jquery-2.0.3.min.js"></script>
	    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	    <script src="../assets/js/jquery.inputlimiter.1.3.1.min.js"></script>
	    <script src="../assets/js/chosen.jquery.js"></script>
	    <script src="../assets/js/jquery.tagsinput.min.js"></script>
	    <script src="../assets/js/jquery.autosize.min.js"></script>
	    <script src="../assets/js/formsInit.js"></script>
	    <script src="../assets/plugins/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	    <script src="../assets/js/general.js"></script>
	    <script src="../assets/js/validacion.js"></script>
	    <script src="../assets/js/notifications.js"></script>
	    <script src="../assets/js/scriptPersonal.js"></script>
	    <script src="../assets/js/ordenarTablas.js"></script>
	    <script src="../assets/js/jquery-ui.min.js"></script>

		<script>
			$(document).ready(function() {
				$('.carousel').carousel({
			      	pause: true,
			      	interval: false,      
			  	});
			    link('frmPresentacion.php');
			});	    	
	    </script>
		<?php include "frmModales.php";?>
	</body>
</html>