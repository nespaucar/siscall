<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function(){
        inicializarFecha();
        llenarTabla('Asignacion', '9');        
    });
</script>
<script src="../assets/js/ordenarTablas.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>

<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-11">
                        <h4><i class="glyphicon glyphicon-list"></i> &nbsp;Asignaciones</h4>
                    </div>
                    <div class="col-lg-1">
                        <h4><a href="#" class="btn btn-success btn-sm btn-line btn-rect" id="nuevo" data-opcion="0" data-bean="Asignaciones"><i class="icon-plus"></i> Crear</a></h4>
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
                                        Mostrar <select name="cboCantidadAsignaciones" aria-controls="tablaAsignacion" class="form-control input-sm" onchange="llenarTabla('Asignacion', '9');">
                                            <option value="9">9</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                        </select> Filas
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="dataTables_length">
                                    <label>
                                        Fecha <input type="text" name="fechaAsignaciones" aria-controls="tablaAsignacion" class="form-control input-sm fechita" onchange="llenarTabla('Asignacion', '9');">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter">
                                    <label>Buscar:
                                        <input type="search" name="txtFiltroAsignaciones" class="form-control input-sm" aria-controls="tablaAsignacion" onkeyup="llenarTabla('Asignacion', '9');">
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
                                <th width="15%">Nro.</th>
                                <th width="30%">Tecnico</th>
                                <th width="10%">Fecha Ent.</th>
                                <th width="10%">Posesion</th>
                                <th width="10%">Link</th>
                                <th width="10%">Detalles</th>
                                <th width="15%">Accion</th>
                            </tr>
                        </thead>
                        <tbody id="tablaAsignacion"></tbody>
                    </table>                    
                </div>
            </div>
            <div class="panel-footer">
                <ul class="pager">
                    <li class="next">
                        <a href="javascript:" id="paginacionAsignacion"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
