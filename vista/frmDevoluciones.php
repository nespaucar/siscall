<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function(){
        llenarTabla('Devolucion', '6');        
    });
</script>
<script src="../assets/js/scriptExcelFile.js"></script>
<script src="../assets/js/ordenarTablas.js"></script>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-10">
                        <h4><i class="glyphicon glyphicon-share-alt"></i> &nbsp;Devoluciones</h4>
                    </div>
                    <div class="col-lg-2">
                         <h4><a href="#" class="btn btn-success btn-sm btn-line btn-rect" data-toggle="modal" id="btnCargarExcel" data-target="#cargarExcel" data-bean="Devolución" data-tabla="Devoluciones"><i class="icon-file"></i> Importar</a></h4>
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
                                        Mostrar <select name="cboCantidadDevolucion" aria-controls="tablaDevolucion" class="form-control input-sm" onchange="llenarTabla('Devolucion', '6');">
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
                            <div class="col-sm-6">
                                <div class="dataTables_filter">
                                    <label>Buscar:
                                        <input type="search" name="txtFiltroDevolucion" class="form-control input-sm" aria-controls="tablaDevolucion" onkeyup="llenarTabla('Devolucion', '6');">
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
                                <th width="30%">Observacion</th>
                                <th width="10%">Codigo_SAP</th>
                                <th width="20%">Producto</th>
                                <th width="10%">Tipo</th>
                                <th width="20%">Serie</th>
                                <th width="10%">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody id="tablaDevolucion"></tbody>
                    </table>                
                </div>
            </div>
            <div class="panel-footer">
                <ul class="pager">
                    <li class="next">
                        <a href="javascript:" id="paginacionDevolucion"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
