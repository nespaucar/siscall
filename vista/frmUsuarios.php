<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function(){
        llenarTabla('Usuarios', '5', '&tipo=1');
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
                        <h4><i class="glyphicon glyphicon-user"></i> &nbsp;Usuarios</h4>
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
                                        Mostrar <select name="cboCantidadUsuarios" aria-controls="tablaUsuarios" class="form-control input-sm" onchange="llenarTabla('Usuarios', '5', '&tipo=1');">
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
                                        <input type="search" name="txtFiltroUsuarios" class="form-control input-sm" aria-controls="tablaUsuarios" onkeyup="llenarTabla('Usuarios', '5', '&tipo=1');">
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
                                <th width="40%">Nombres</th>
                                <th width="30%">Dirección</th>
                                <th width="20%">Usuario</th>
                                <th width="10%">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="tablaUsuarios"></tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <ul class="pager">
                    <li class="next">
                        <a href="javascript:" id="paginacionUsuarios"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
