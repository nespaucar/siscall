<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
date_default_timezone_set('America/Lima');
$sdate = date("d") . "-" . date("m") . "-" . date("Y");
?>
<script>
    $(document).ready(function(){
        llenarTabla('Personal', '7', '&tipo=1');
    });
    $( function() {
        $( "#dia" ).datepicker();
        $( "#di" ).datepicker();
        $( "#df" ).datepicker();
    } );
</script>
<script src="../assets/js/ordenarTablas.js"></script>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-11">
                        <h4><i class="glyphicon glyphicon-eye-open"></i> &nbsp;Administrativos</h4>
                    </div>
                    <div class="col-lg-1">
                        <h4><a href="#" class="btn btn-success btn-sm btn-line btn-rect" id="nuevo" data-opcion="0" data-bean="Administrativos"><i class="icon-plus"></i> Crear</a></h4>
                    </div>
                </div>
            </div>
            <div class="panel-body" id="mantenimiento">
                <div class="dataTables_wrapper form-inline" role="grid">
                    <form id="form_search" method="POST" onsubmit="return false;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_length">
                                    <label>
                                        Mostrar <select name="cboCantidadPersonal" aria-controls="tablaPersonal" class="form-control input-sm" onchange="llenarTabla('Personal', '7', '&tipo=1');">
                                            <option value="9">9</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> Filas
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter">
                                    <label>Buscar:
                                        <input type="search" name="txtFiltroPersonal" class="form-control input-sm" aria-controls="tablaPersonal" onkeyup="llenarTabla('Personal', '7', '&tipo=1');">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="30%">Nombres</th>
                                <th width="5%">DNI</th>
                                <th width="5%">Carnet</th>
                                <th width="20%">Dirección</th>
                                <th width="10%">Teléfono</th>
                                <th width="20%">Correo</th>
                                <th width="10%">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaPersonal"></tbody>
                    </table>                   
                </div>
            </div>
            <div class="panel-footer">
                 <ul class="pager">
                    <li class="next">
                        <a href="javascript:" id="paginacionPersonal"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
