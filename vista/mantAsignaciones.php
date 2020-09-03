<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
include "../modelo/clsAsignacion.php";

$id = '';
$idtecnico = '';
$numero = '';
$idtecnico = '';
$fechaentrega = '';
$asignacion = new Asignacion();

$tecnicos = $asignacion->obtenertecnicos($_SESSION['idempresa']);
$equipos = $asignacion->obtenerequiposseriados($_SESSION['idempresa']);

if ($_GET['accion'] == 'modificar') {    
    $rs      = $asignacion->cargardatosasignacion($_GET['id'], $_SESSION['idempresa']);
	foreach ($rs as $dato) {
        $id     = $dato[0];  
        $numero = $dato[3]; 
        $idtecnico = $dato[1];     
        $fechaentrega = $dato[2];     
    }

    echo '<script>obtenerDetallesAsignacion(' . $_GET['id'] . ')</script>';
} else {
    $iid = 1;
	$rs      = $asignacion->cargardatoslastasignacion($_SESSION['idempresa']);
    if($rs->rowCount() == 0) {
    	$numero = 'AS1';
    } else {
    	foreach ($rs as $dato) {
	        $iid++;	        
	    }
        $numero = 'AS' . $iid;
    }
    date_default_timezone_set('America/Lima');
    $fechaentrega = date('Y-m-j');
}
?>
<script>
    $(document).ready(function() {
        $('#equipo').chosen({
            width: "100%",
        });
	    obtenerseries($('#equipo').val());	
        $('#stockproducto').html($('#equipo option:selected').data('cantidad')); 
        <?php if($_GET['accion'] == 'modificar') { ?>
            $('#tecnico').val('<?php echo $idtecnico; ?>');
        <?php } ?>        
	});

    $(document).on('change', '#equipo', function() {
        $('#alertaExisteProducto').html('');
        obtenerseries($(this).val());
        $('#stockproducto').html($('#equipo option:selected').data('cantidad')); 
    });	
</script>

<div class="col-lg-12">
    <div class="col-lg-3">
        <div class="text-center">
            <br><br><img class="img img-responsive img-thumbnail" src="../assets/img/asignacion.jpg" alt="">
        </div>
    </div>
    <div class="col-lg-6">
        <h4 class="titulo" style="color: blue"><b><i class="icon-user-md"></i> &nbsp;<?php echo ucwords($_GET['accion']); ?></b></h4>
        <hr>
        <form role="form" id="formulario" onsubmit="return false;">
        	<div class="row">
	        	<div class="col-md-6">
	            	<div class="form-group input-group">
		                <span class="input-group-addon">Número</span>
		                <input type="text" class="form-control input-sm" readonly="readonly" name="numero" id="numero" maxlength="180" value="<?php echo $numero; ?>">
		            </div>
	            </div>
	            <div class="col-md-6">
	            	<div class="form-group input-group">
		                <span class="input-group-addon">Fecha</span>
		                <input type="text" class="form-control input-sm" readonly="readonly" name="fechaentrega" id="fechaentrega" maxlength="180" value="<?php echo $fechaentrega; ?>">
		            </div>
	            </div>
	        </div>
            <div class="form-group input-group">
                <span class="input-group-addon">Técnico</span>
                <select name="tecnico" id="tecnico" class="form-control input-sm" value="<?php echo $idtecnico; ?>">
                    <?php  
                        if($tecnicos->rowCount() != 0) {
                            foreach ($tecnicos as $tecnico) {
                                echo '<option value="' . $tecnico['id'] . '">' . $tecnico['nombre'] . '</option>';
                            }
                        } else {
                            echo '<option value="0">No hay técnicos disponibles</option>';
                        }
                    ?>
                </select>
            </div>
        	<div class="form-group input-group">
                <span class="input-group-addon">Equipo/Material</span>
                <div id="divequipos">
                    <select name="equipo" id="equipo" class="chos form-control input-sm chzn-select">
                        <?php 
                            if($equipos->rowCount() != 0) {
                                foreach ($equipos as $equipo) {
                                    echo '<option id="O' . $equipo['id'] . '" data-cantidad="' . $equipo['stock'] . '" value="' . $equipo['id'] . '">' . $equipo['descripcion'] . '</option>';
                                }
                            } else {
                                echo '<option value="0">No hay Equipos/Materiales disponibles</option>';
                            }
                        ?>
                    </select>
                </div>
                <span class="input-group-btn">
                    <span class="btn btn-default input-sm" id="stockproducto"></span>
                </span>
            </div>
            <div class="form-group input-group" id="cargarparametro"></div>
            
            <div class="form-group input-group">
                <span class="input-group-btn">
                    <button class="btn btn-success input-sm">Serie (Código de Barras)</button>
                </span>
                <input name="serieBarra" id="serieBarra" class="form-control input-sm">
            </div>

            <div class="row">
                <div class="col-md-5">
                    <h5 style="color: green"><b><i class="glyphicon glyphicon-wrench"></i> &nbsp;Materiales</h5>
                </div>
                <div class="col-md-7"><h5 id="alertaExisteProducto"></h5></div>
            </div>            
                <table class="table table-sm table-striped table-bordered table-hover table-responsive" id="tablaProductos">
                    <thead>
                        <tr>
                            <th width="5%">CANT.</th>
                            <th width="30%">SERIE</th>
                            <th width="55%">DESCRIPCION</th>
                            <th width="10%">MANT.</th>
                        </tr>
                    </thead>
                    <tbody></tbody>                    
                </table>
                <input name="idasignacion" id="idasignacion" value="<?php echo $id; ?>" type="hidden">
            <div class="row">
                <div class="col-lg-5"></div>
                <div class="col-lg-1">
                    <a href="#" onclick="registrarAsignacion()" class="grabar btn btn-success" data-accion="<?php echo $_GET['accion']; ?>"><i class="icon-save"></i> Grabar</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-3">
        <h4 class="titulo" style="color: #1FA463"><b><i class="icon-warning-sign"></i> &nbsp;Mensajes</b></h4>
        <hr>
        <div id="mensajes">
            <p style="color: blue;"><i class="icon-check"></i> Ingresa los datos requeridos.</p>
            <p style="color: blue;"><i class="icon-check"></i> Los datos no se guardarán en la base de datos a menos que des click en "Grabar".</p>
        </div>
    </div>
    <script src="../assets/js/ordenarTablas.js"></script>
</div>