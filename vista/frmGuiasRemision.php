<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function(){
        inicializarFecha();
        llenarTabla('GuiaRemision', '7');        
    });
</script>
<script src="../assets/js/scriptExcelFile.js"></script>
<script src="../assets/js/scriptGuiaRemision.js"></script>
<script src="../assets/js/ordenarTablas.js"></script>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-8">
                        <h4><i class="glyphicon glyphicon-list-alt"></i> &nbsp;Guías de Remisión</h4>
                    </div>
                    <div class="col-lg-4">
                        <div class="col-md-3">
                            <h4><a href="#" class="btn btn-success btn-sm btn-line btn-rect" data-toggle="modal" id="btnCargarExcel" data-target="#cargarExcel" data-bean="Guía de Remisión" data-tabla="GuiasRemision"><i class="icon-file"></i> Importar</a></h4>
                        </div>
                        <div class="col-md-9">
                            <h4><a href="#" class="btn btn-info btn-sm btn-line btn-rect" data-toggle="modal" id="btnAumentarStock" data-target="#aumentarStock" data-tabla="GuiasRemision"><i class="icon-file"></i> Guía Manual de Materiales</a></h4>
                        </div>
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
                                        Mostrar <select name="cboCantidadGuiasRemision" aria-controls="tablaGuiaRemision" class="form-control input-sm" onchange="llenarTabla('GuiaRemision', '7');">
                                            <option value="9">9</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                            <option value="1000">1000</option>
                                            <option value="2000">1200</option>
                                        </select> Filas
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="dataTables_length">
                                    <label>
                                        Fecha <input type="text" name="fechaGuiasRemision" aria-controls="tablaGuiaRemision" class="form-control input-sm fechita" onchange="llenarTabla('GuiaRemision', '7');">
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter">
                                    <label>Buscar:
                                        <input type="search" name="txtFiltroGuiasRemision" class="form-control input-sm" aria-controls="tablaGuiaRemision" onkeyup="llenarTabla('GuiaRemision', '7');">
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
                                <th width="20%">Número de Guía</th>
                                <th width="20%">Fecha de Ent.</th>
                                <th width="20%">Último día</th>
                                <th width="30%">Situación</th>
                                <th width="10%">Materiales</th>
                            </tr>
                        </thead>
                        <tbody id="tablaGuiaRemision"></tbody>
                    </table>                
                </div>
            </div>
            <div class="panel-footer">
                <ul class="pager">
                    <li class="next">
                        <a href="javascript:" id="paginacionGuiaRemision"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
