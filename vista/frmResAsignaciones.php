<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function(){
        llenarTabla('Personal', '7', '&tipo=2&res=res');
    });
</script>
<script src="../assets/js/ordenarTablas.js"></script>
<script src="../assets/js/scriptResAsignacion.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>

<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-11">
                        <h4><i class="glyphicon glyphicon-th"></i> &nbsp;Resumen de Asignaciones</h4>
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
                            <div class="col-sm-6">
                                <div class="dataTables_length">
                                    <label>
                                        Mostrar <select name="cboCantidadPersonal" aria-controls="tablaPersonal" class="form-control input-sm" onchange="llenarTabla('Personal', '7', '&tipo=2&res=res');">
                                            <option value="9">9</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                        </select> Filas
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter">
                                    <label>Buscar:
                                        <input type="search" name="txtFiltroPersonal" class="form-control input-sm" aria-controls="tablaPersonal" onkeyup="llenarTabla('Personal', '7', '&tipo=2&res=res');">
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
                                <th width="15%">Carnet</th>
                                <th width="15%">DNI</th>
                                <th width="60%">Técnico</th>
                                <th width="10%">Resumen</th>
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
