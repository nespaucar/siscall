<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function() {
        $('input[name=txtLiquidacion]').focus();
        $('#mensajeDetalleLiquidacion').css('color', 'blue').html('Encuentra una orden.');
    });
</script>
<script src="../assets/js/scriptGestionLiquidacion.js"></script>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-12">
                        <h4><i class="	glyphicon glyphicon-screenshot"></i> &nbsp;Gestión de Liquidaciones</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body" id="mantenimiento">
                <div class="dataTables_wrapper form-inline" role="grid">
                    <form id="form_search" method="POST" onsubmit="buscarLiquidacion(); return false;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_length">
                                    <label>
                                        Buscar Orden: 
                                        <input type="text" id="txtLiquidacion" name="txtLiquidacion" class="form-control" placeholder="Ingrese una orden">
                                        <button class="btn btn-success" type="submit">
                                            <i class="icon-search"></i>
                                        </button>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <hr>
                <center><h4 id="mensajeDetalleLiquidacion"></h4></center>
                <hr>
                <div class="row">
                	<div class="col-md-6">
                		<table class="table table-responsive table-bordered">
                			<thead>
	                			<tr>
	                				<td colspan="4" width="100%">
	                					<center style="color:green;">INFORMACIÓN DE LIQUIDACIÓN</center>
	                				</td>
	                			</tr>
	                		</thead>
	                		<tbody>
	                			
                				<tr>
	                				<td width="20%">
	                					<b><center style="color:red;">F_LIQUID.</center></b>
	                				</td>
	                				<td width="80%" colspan="3">
	                					<center id="fechaLi"></center>
	                				</td>
	                			</tr>
	                			<tr>
	                				<td width="20%">
	                					<b><center style="color:red;">OBSERV.</center></b>
	                				</td>
	                				<td width="80%" colspan="3">
	                					<center id="observacionLi"></center>
	                				</td>
	                			</tr>
	                			<tr>
	                				<td width="10%">
	                					<b><center style="color:red;">ACTIVIDAD</center></b>
	                				</td>
	                				<td width="40%">
	                					<center id="actividadLi"></center>
	                				</td>
	                				<td width="10%">
	                					<b><center style="color:red;">PREFIJO</center></b>
	                				</td>
	                				<td width="40%">
	                					<center id="prefijoLi"></center>
	                				</td>
	                			</tr>
	                			<tr>
	                				<td width="10%">
	                					<b><center style="color:red;">O/T</center></b>
	                				</td>
	                				<td width="40%">
	                					<center id="otLi"></center>
	                				</td>
	                				<td width="10%">
	                					<b><center style="color:red;">TELEFONO</center></b>
	                				</td>
	                				<td width="40%" colspan="3">
	                					<center id="telefonoLi"></center>
	                				</td>
	                			</tr>
	                			<tr>
	                				<td width="20%">
	                					<b><center style="color:red;">TÉCNICO 1</center></b>
	                				</td>
	                				<td width="80%" colspan="3">
	                					<center id="tec1Li"></center>
	                				</td>
	                			</tr>
	                			<tr>
	                				<td width="20%">
	                					<b><center style="color:red;">TÉCNICO 2</center></b>
	                				</td>
	                				<td width="80%" colspan="3">
	                					<center id="tec2Li"></center>
	                				</td>
	                			</tr>
	                			<tr>
	                				<td width="20%">
	                					<b><center style="color:red;">TÉCNICO 3</center></b>
	                				</td>
	                				<td width="80%" colspan="3">
	                					<center id="tec3Li"></center>
	                				</td>
	                			</tr>
	                		</tbody>
                		</table>
                	</div>
                	<div class="col-md-6">
                		<table class="table table-responsive table-bordered">
                			<thead>                			
	                			<tr>
	                				<td colspan="5">
	                					<center style="color:red;">DETALLES DE LIQUIDACIÓN</center>
	                				</td>
	                			</tr>
	                			<tr>
	                				<td style="color:blue;" class="text-center" width="14%">SAP</td>
	                				<td style="color:blue;" class="text-center" width="35%">EQUIPO/MATERIAL</td>
	                				<td style="color:blue;" class="text-center" width="35%">SERIE</td>
	                				<td style="color:blue;" class="text-center" width="8%">CANT.R</td>
	                				<td style="color:blue;" class="text-center" width="8%">CANT.C</td>
	                			</tr>
                			</thead>
                			<tbody id="detallesLi"></tbody>
                		</table>
                	</div>
                </div>                
            </div>
        </div>
    </div>
</div>
