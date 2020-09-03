<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="titulo"><b><i class="icon-home"></i> &nbsp;Presentación </b></h3>
    </div>
</div>
<div class="row">
	<div class="col-sm-6">
		<div class="text-center">
			<img src="../assets/img/<?php echo $_SESSION['logo'];?>" alt="" class="img img-responsive img-thumbnail" width="50%" height="50%">
		</div>
	</div>
	<div class="col-sm-6">
		<div class="text-center">
			<img src="../assets/img/<?php echo $_SESSION['logo2'];?>" alt="" class="img img-responsive img-thumbnail" width="50%" height="50%">
		</div>
	</div>
</div>
<br>
<div class="col-sm-12">
	<div class="panel panel-success">
        <div class="panel-body" style="padding: 0; margin:0">
        	<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<h1 style="font-family: 'Boogaloo', cursive;">DESCRIPCIÓN..</h1>
						<h5 class="text-justify" style="font-family: 'Arima Madurai', cursive;">
							<?php echo $_SESSION['descripcion'];?>
						</h5>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-12 text-center">
						<h6><?php echo $_SESSION['direccion'];?></h6>
						<h6>Sucursal: <?php echo $_SESSION['sucursal'];?></h6>
						<h6><a target="_blank" href="<?php echo $_SESSION['paginaweb'];?>"><?php echo $_SESSION['paginaweb'];?> </a></h6>
						<h6><?php echo $_SESSION['correo'];?> Teléfono: <?php echo $_SESSION['telefono'];?></h6>
						<h6>Copyright © 2009 All Rights Reserved <?php echo $_SESSION['nombreempresa'];?></h6>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<div class="row">
		    				<div class="col-sm-12">
		    					<h1 style="font-family: 'Boogaloo', cursive;">MISIÓN..</h1>
		        				<h5 class="text-justify" style="font-family: 'Arima Madurai', cursive;">
		        					<?php echo $_SESSION['mision'];?>
		        				</h5>
		        			</div>
		        			<div class="col-sm-12">
		    					<h1 style="font-family: 'Boogaloo', cursive;">VISIÓN..</h1>
		        				<h5 class="text-justify" style="font-family: 'Arima Madurai', cursive;">
									<?php echo $_SESSION['vision'];?>
		        				</h5>
		        			</div>
		    			</div>
					</div>
				</div>
			</div>
			<br>
        </div>
	</div>	
</div>
