<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
include "../modelo/clsTelefono.php";
include "../modelo/clsPersonal.php";

$otelefono = new Telefono();
$opersona = new Personal();

$personas = $opersona->obtenerpersonas($_SESSION['idempresa']);
?>
<script>
    $(document).ready(function() {
        $('#persona').chosen({
            width: "100%",
        });
        <?php if($_GET['accion'] === 'modificar') { ?>
            cargarDatosPersona('<?php echo $_GET["id"]; ?>');
        <?php } ?>        
	});

    $(document).on('change', '#persona', function(e) {
    	e.preventDefault();
    	e.stopImmediatePropagation();
    	if($(this).val() !== "") {
    		cargarDatosPersona($(this).val());
    	}        
    });

    function cargarDatosPersona(id) {
    	$.ajax({
	        url: '../controlador/contPersonal.php?accion=cargarDatosPersona&id=' + id,
	        type: 'GET',
	        dataType: 'JSON',
	        success: function(a) {
	            $('#codigo').val(a.codigo);
	            $('#tipo').val(a.tipo);
	            $('#celular').focus();
	            cargarNumeros(id);
	        }
	    });
    }

    function cargarNumeros(id) {
    	$.ajax({
	        url: '../controlador/contTelefonos.php?accion=cargarNumeros&id=' + id,
	        type: 'GET',
	        dataType: 'JSON',
	        success: function(a) {
	            $('#cuerpoTelefonos').html(a.tabla);
	        }
	    });
    }

    function anadirCelular() {
    	var celular = $("#celular").val();
    	var mensaje = "";
    	if($("#persona").val() === "") {
    		mensaje += '<p style="color: red;"><i class="icon-check"></i> Debes escoger a una persona primero.</p>';
	    	$('#mensajes').html(mensaje);
    	} else {
    		if(celular.length == 0) {
	    		mensaje += '<p style="color: red;"><i class="icon-check"></i> No puedes registrar un número vacío.</p>';
	    		$('#mensajes').html(mensaje);
	    		$('#celular').focus();
	    	} else if(celular.length < 9) {
	    		mensaje += '<p style="color: red;"><i class="icon-check"></i> El número debe tener 9 dígitos.</p>';
	    		$('#mensajes').html(mensaje);
	    		$('#celular').focus();
	    	} else if(celular.length == 9) {
	    		anadirCelular2(celular);
	    	}
    	}	    	    	
    }

    function anadirCelular2(celular) {
    	$.ajax({
	        url: '../controlador/contTelefonos.php?accion=anadirCelular&telefono=' + celular + '&idpersona=' + $("#persona").val(),
	        type: 'GET',
	        success: function(a) {
    			$('#mensajes').html(a);
	            cargarNumeros($("#persona").val());
	            $('#celular').val("");
	            $('#celular').focus();
	        }
	    });
    }
</script>

<div class="col-lg-12">
    <div class="col-lg-3">
        <div class="text-center">
            <br><br><img class="img img-responsive img-thumbnail" src="../assets/img/telefono.png" alt="">
        </div>
    </div>
    <div class="col-lg-6">
        <h4 class="titulo" style="color: blue"><b><i class="icon-user-md"></i> &nbsp;<?php echo ucwords($_GET['accion']); ?></b></h4>
        <hr>
        <form role="form" id="formulario" onsubmit="return false;">
        	<div class="form-group input-group">
                <span class="input-group-addon">Persona</span>
                <select name="persona" id="persona" class="form-control input-sm chzn-select">
                	<option value="">Selecciona una persona...</option>
                    <?php  
                        if($personas->rowCount() != 0) {
                            foreach ($personas as $persona) {
                                if($_GET['id'] == $persona['id']) { 
                                    echo '<option selected value="' . $persona['id'] . '">' . $persona['nombre'] . '</option>';
                                } else {
                                    echo '<option value="' . $persona['id'] . '">' . $persona['nombre'] . '</option>';
                                }
                            }
                        } else {
                            echo '<option value="0">No hay personas registradas.</option>';
                        }
                    ?>
                </select>
                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
            </div>
        	<div class="row">
	        	<div class="col-md-6">
	            	<div class="form-group input-group">
		                <span class="input-group-addon">Código</span>
		                <input type="text" class="form-control input-sm" readonly="readonly" name="codigo" id="codigo" maxlength="180" value="">
		            </div>
	            </div>
	            <div class="col-md-6">
	            	<div class="form-group input-group">
		                <span class="input-group-addon">Tipo</span>
		                <input type="text" class="form-control input-sm" readonly="readonly" name="tipo" id="tipo" maxlength="180" value="">
		            </div>
	            </div>
	        </div>            
            <div class="form-group input-group">
                <span class="input-group-addon">Celular</span>
                <input name="celular" id="celular" maxlength="9" class="form-control input-sm">
                <span class="input-group-addon" style="cursor: pointer; background-color: green; color: white;" onclick="anadirCelular();">+ Añadir</span>
            </div>

            <div class="row">
                <div class="col-md-5">
                    <h5 style="color: green"><b><i class="glyphicon glyphicon-phone"></i> &nbsp;Lista de Números</h5>
                </div>
                <div class="col-md-7"><h5 id="alertaExisteTelefono"></h5></div>
            </div>            
            <table class="table table-sm table-striped table-bordered table-hover table-responsive" id="tablaTelefonos">
                <thead>
                    <tr>
                        <th width="80%">NÚMERO</th>
                        <th width="20%">ELIMINAR</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTelefonos"></tbody>                    
            </table>
        </form>
    </div>
    <div class="col-lg-3">
        <h4 class="titulo" style="color: #1FA463"><b><i class="icon-warning-sign"></i> &nbsp;Mensajes</b></h4>
        <hr>
        <div id="mensajes">
            <p style="color: blue;"><i class="icon-check"></i> Consulta números de celulares.</p>
            <p style="color: blue;"><i class="icon-check"></i> Puedes agregar o eliminar números.</p>
        </div>
    </div>
    <script src="../assets/js/ordenarTablas.js"></script>
</div>