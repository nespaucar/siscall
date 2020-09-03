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
	                        </span>&nbsp;<span class="label label-default">4</span>&nbsp;
	                    </a>
	                    <ul class="collapse" id="mantenimientos">
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmTecnicos.php')">
	                        		<i class="icon-user-md"></i> Técnicos
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmAdministrativos.php')">
	                        		<i class="glyphicon glyphicon-eye-open"></i> Administrativos
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmEquiposMateriales.php')">
	                        		<i class="glyphicon glyphicon-wrench"></i> Equipos y Materiales
	                        	</a>
	                        </li>
	                        <li class="lista" style="display:none;">
	                        	<a href="javascript:void(0)" onclick="link('frmServiciosPaquetes.php')">
	                        		<i class="icon-male"> </i>
	                        		<i class="icon-archive"></i> Servicios/Paquetes
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmPuntosBarema.php')">
	                        		<i class="glyphicon glyphicon-signal"></i> Puntos Barema
	                        	</a>
	                        </li>
	                    </ul>
	                </li>
	                
	                <li class="panel ">
	                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle collapsed" data-target="#movimientos">
	                        <i class="icon-pencil"></i> Movimientos
	                        <span class="pull-right">
	                            <i class="icon-angle-left"></i>
	                        </span>&nbsp;<span class="label label-success">5</span>&nbsp;
	                    </a>
	                    <ul class="collapse" id="movimientos">
	                    	<li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmGuiasRemision.php')">
	                        		<i class="glyphicon glyphicon-list-alt"></i> Guías de Remisión 
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmAsignaciones.php')">
	                        		<i class="glyphicon glyphicon-list"></i> Asignación a Técnicos 
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmResAsignaciones.php')">
	                        		<i class="glyphicon glyphicon-th"></i> Resumen Asignaciones 
	                        	</a>
	                        </li> 
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmInstalaciones.php')">
	                        		<i class="glyphicon glyphicon-check"></i> Instalaciones 
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmDevoluciones.php')">
	                        		<i class="glyphicon glyphicon-share-alt"></i> Devoluciones 
	                        	</a>
	                        </li>
	                    </ul>
	                </li>
	                <li class="panel">
	                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#contSeries">
	                        <i class="icon-ticket"></i> Control de Series
	                        <span class="pull-right">
	                            <i class="icon-angle-left"></i>
	                        </span>&nbsp;<span class="label label-primary">1</span>&nbsp;
	                    </a>
	                    <ul class="collapse" id="contSeries">	                    	
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmGestionSeries.php')">
	                        		<i class="glyphicon glyphicon-qrcode"></i> Gestión de Series
	                        	</a>
	                        </li>
	                    </ul>
	                </li>
	                <li class="panel">
	                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#contLiquidaciones">
	                        <i class="glyphicon glyphicon-thumbs-up"></i> Control de Liquid.
	                        <span class="pull-right">
	                            <i class="icon-angle-left"></i>
	                        </span>&nbsp;<span class="label label-info">1</span>&nbsp;
	                    </a>
	                    <ul class="collapse" id="contLiquidaciones">	                    	
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmGestionLiquidaciones.php')">
	                        		<i class="glyphicon glyphicon-screenshot"></i> Gestión de Liquid.
	                        	</a>
	                        </li>
	                    </ul>
	                </li>
	                <li class="panel">
	                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#almacen">
	                        <i class="icon-table"></i> Control de Almacén
	                        <span class="pull-right">
	                            <i class="icon-angle-left"></i>
	                        </span>&nbsp;<span class="label label-danger">2</span>&nbsp;
	                    </a>
	                    <ul class="collapse" id="almacen">	                    	
	                        <!--
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmGuiasRemision.php')">
	                        		<i class="glyphicon glyphicon-shopping-cart"></i> Stock 
	                        	</a>
	                        </li>
	                        -->
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmMaterialesSeriados.php')">
	                        		<i class="glyphicon glyphicon-log-in"></i> Equipos (Seriados) 
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmMaterialesNoSeriados.php')">
	                        		<i class="glyphicon glyphicon-compressed"></i> Materiales (No Seriados) 
	                        	</a>
	                        </li>
	                    </ul>
	                </li>
	                <li class="panel">
	                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#reportes">
	                        <i class="glyphicon glyphicon-folder-open"></i> Reportes 
	                        <span class="pull-right">
	                            <i class="icon-angle-left"></i>
	                        </span>&nbsp;<span class="label label-warning">3</span>&nbsp;
	                    </a>
	                    <ul class="collapse" id="reportes">	                    	
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmProduccion.php')">
	                        		<i class="icon-bar-chart"></i> Producción 
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmSeriesTecnico.php')">
	                        		<i class="glyphicon glyphicon-compressed"></i> Series en Técnico 
	                        	</a>
	                        </li>
	                        <li class="lista">
	                        	<a href="javascript:void(0)" onclick="link('frmLiquidaciones.php')">
	                        		<i class="glyphicon glyphicon-thumbs-up"></i> Liquidaciones 
	                        	</a>
	                        </li>
	                    </ul>
	                </li>
	            </ul>
	        </div>

			
	        <!--END MENU SECTION -->

	        <!--PAGE CONTENT -->
	        <div id="content">
	            <div class="inner" id="contenedor" style="min-height:538px;"></div>
	        </div>
	        <!--END PAGE CONTENT -->
	    </div>

	    <div id="footer" style="width: 100%; position: absolute; bottom: 0; position: fixed">
	        <p>&copy;  NesPaucar2018 &nbsp; 922179451&nbsp;</p>
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
	    <!--<script src="../assets/js/scriptServicioPaquete.js"></script>-->
	    <script src="../assets/js/scriptAsignacion.js"></script>
	    <script src="../assets/js/scriptInstalacion.js"></script>
	    <script src="../assets/js/scriptEquipoMaterial.js"></script>
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