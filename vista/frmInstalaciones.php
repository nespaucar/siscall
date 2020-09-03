<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function(){
        inicializarFecha();
        llenarTabla('Instalacion', '9');        
    });
</script>
<script src="../assets/js/scriptExcelFile.js"></script>
<script src="../assets/js/ordenarTablas.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>

<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-10">
                        <h4><i class="glyphicon glyphicon-check"></i> &nbsp;Instalaciones</h4>
                    </div>
                    <div class="col-lg-2">
                        <h4><a href="#" class="btn btn-success btn-sm btn-line btn-rect" data-toggle="modal" id="btnCargarExcel" data-target="#cargarExcel" data-bean="Instalación" data-tabla="Instalaciones"><i class="icon-file"></i> Importar</a></h4>
                    </div>
                </div>
            </div>
            <div class="panel-body" id="mantenimiento">
                <div class="dataTables_wrapper form-inline" role="grid">
                    <form id="form_search" method="POST" onsubmit="return false;">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="dataTables_length">
                                    <label>
                                        Mostrar <select name="cboCantidadInstalaciones" aria-controls="tablaInstalacion" class="form-control input-sm" onchange="llenarTabla('Instalacion', '9');">
                                            <option value="9">9</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">5000</option>
                                        </select> Filas
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="dataTables_length">
                                    <label>
                                        Fecha <input type="text" name="fechaInstalaciones" aria-controls="tablaInstalacion" class="form-control input-sm fechita" onchange="llenarTabla('Instalacion', '9');">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter">
                                    <label>Buscar:
                                        <input type="search" name="txtFiltroInstalaciones" class="form-control input-sm" aria-controls="tablaInstalacion" onkeyup="llenarTabla('Instalacion', '9');">
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
                                <th width="5%">Orden</th>
                                <th width="25%">Tecnico(s)</th>
                                <th width="10%">F. Liquid.</th>
                                <th width="37%">Observacion</th>
                                <th width="8%">Actividad</th>
                                <th width="5%">Pref.</th>
                                <th width="5%">Estado</th>
                                <th width="5%">Detalles</th>
                            </tr>
                        </thead>
                        <tbody id="tablaInstalacion"></tbody>
                    </table>                
                </div>
            </div>
            <div class="panel-footer">
                <ul class="pager">
                    <li class="next">
                        <a href="javascript:" id="paginacionInstalacion"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
