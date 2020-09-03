<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function(){
        llenarTabla('EquipoMaterial', '4');
    });
</script>
<script src="../assets/js/ordenarTablas.js"></script>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-11">
                        <h4><i class="glyphicon glyphicon-wrench"></i> &nbsp;Equipos y Materiales</h4>
                    </div>
                    <div class="col-lg-1">
                        <h4><a href="#" class="btn btn-success btn-sm btn-line btn-rect" id="nuevo" data-opcion="0" data-bean="EquiposMateriales"><i class="icon-plus"></i> Crear</a></h4>
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
                                        Mostrar <select name="cboCantidadEquiposMateriales" aria-controls="tablaEquipoMaterial" class="form-control input-sm" onchange="llenarTabla('EquipoMaterial', '4');">
                                            <option value="9">9</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                        </select> Filas
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter">
                                    <label>Buscar:
                                        <input type="search" name="txtFiltroEquiposMateriales" class="form-control input-sm" aria-controls="tablaCliente" onkeyup="llenarTabla('EquipoMaterial', '4');">
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
                                <th width="10%">Código</th>
                                <th width="60%">Descripción</th>
                                <th width="10%">Tipo</th>
                                <th width="20%">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaEquipoMaterial"></tbody>
                    </table>                    
                </div>
            </div>
            <div class="panel-footer">
                <ul class="pager">
                    <li class="next">
                        <a href="javascript:" id="paginacionEquipoMaterial"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
