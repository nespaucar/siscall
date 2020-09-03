<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script src="../assets/js/scriptSeriesTecnico.js"></script>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-12">
                        <h4><i class="glyphicon glyphicon-compressed"></i> &nbsp;Series en Técnico</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-horizontal" role="form">
                    <div class="form-group">
                        <div id="ap_intervalo">
                            <label for="intervalo" class="control-label col-md-1">
                                Técnico
                            </label>
                            <div class="col-md-6" id="cboTecnicos"></div>
                        </div>
                        <p id="mensajeReporte" style="font-weight: bold;"></p>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="form-horizontal" role="form">
                    <div class="form-group">
                        <div align="center" class="col-md-4">
                            <button value="btnVIS"  class="btn btn-primary btnR" type="button"><i class="icon-print"></i> VISUALIZAR</button>
                        </div>
                        <div align="center" class="col-md-4">
                            <button value="btnPDF" class="btn btn-danger btnR" type="button"><i class="icon-download-alt"></i> PDF</button>
                        </div>
                        <div align="center" class="col-md-4">
                            <button value="btnEXC" class="btn btn-success btnR" type="button"><i class="icon-download-alt"></i> EXCEL</button>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
