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
						<h1 style="font-family: 'Boogaloo', cursive;">SISCALL - GARZASOFT</h1>
						<h5 class="text-justify" style="font-family: 'Arima Madurai', cursive;">
							Sistema para Registro de llamadas.
						</h5>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-12">
						<div class="row">
		    				<div class="col-sm-12">
		    					<h1 style="font-family: 'Boogaloo', cursive;">SISCALL - GARZASOFT</h1>
		        				<h5 class="text-justify" style="font-family: 'Arima Madurai', cursive;">
		        					Sistema para registro de llamadas.
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
