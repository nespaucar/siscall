<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function(){
        llenarTabla('Telefonos', '5', '&tipo=2');
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
                        <h4><i class="glyphicon glyphicon-user"></i> &nbsp;Teléfonos</h4>
                    </div>
                    <div class="col-lg-1">
                        <h4><a href="#" class="btn btn-success btn-sm btn-line btn-rect" id="nuevo" data-opcion="0" data-bean="Telefonos"><i class="icon-plus"></i> Crear</a></h4>
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
                                        Mostrar <select name="cboCantidadTelefonos" aria-controls="tablaTelefonos" class="form-control input-sm" onchange="llenarTabla('Telefonos', '5', '&tipo=' + $('#cboTipoTelefonos').val());">
                                            <option value="9">9</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> Filas
                                    </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>
                                        Tipo de Persona <select name="cboTipoTelefonos" id="cboTipoTelefonos" aria-controls="tablaTelefonos" class="form-control input-sm" onchange="llenarTabla('Telefonos', '5', '&tipo=' + $(this).val());" style="width: 200px;">
                                            <option value="2">Cliente</option>
                                            <option value="1">Administrador</option>                                            
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter">
                                    <label>Buscar:
                                        <input type="search" name="txtFiltroTelefonos" class="form-control input-sm" aria-controls="tablaTelefonos" onkeyup="llenarTabla('Telefonos', '5', '&tipo=' + $('#cboTipoTelefonos').val());">
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
                                <th width="20%">Número</th>
                                <th width="10%">Código</th>
                                <th width="50%">Persona</th>
                                <th width="10%">Tipo</th>
                                <th width="10%">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaTelefonos"></tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <ul class="pager">
                    <li class="next">
                        <a href="javascript:" id="paginacionTelefonos"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
