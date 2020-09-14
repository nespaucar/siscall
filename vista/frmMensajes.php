<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
date_default_timezone_set('America/Lima');
$sdate = date("d") . "-" . date("m") . "-" . date("Y");
$namemeses = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SETIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE");
$numbermeses = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
?>
<script>
    $( function() {
        inicializarFecha();
    });
</script>
<script src="../assets/js/scriptMensajes.js"></script>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-12">
                        <h4><i class="icon-bar-chart"></i> &nbsp;Reporte de Mensajes</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-horizontal" role="form">
                    <div class="form-group">
                        <div id="ap_intervalo">
                            <label for="intervalo" class="control-label col-md-1">
                                Intervalo
                            </label>
                            <div class="col-md-2">
                                <select class="form-control input-sm" name="intervalo" id="intervalo">
                                    <option id="N" value="N">NORMAL</option>
                                    <option id="P" value="P">PERIODO</option>
                                </select>
                            </div>
                        </div>
                        <div id="opcion_fecha">
                            <label for="tipo" class="control-label col-md-1">
                                Tipo
                            </label>
                            <div class="col-md-2">
                                <select class="form-control input-sm" name="tipo" id="tipo">
                                    <option id="D" value="D">DIARIO</option>
                                    <option id="M" value="M">MENSUAL</option>
                                </select>
                            </div>
                        </div>
                        <p id="mensajeReporte" style="font-weight: bold;"></p>
                    </div>
                </div>
                <hr>
                
                <form class="hide form-horizontal" role="form" id="_dia">
                    <div class="form-group">
                        <label for="dia" class="control-label col-md-1">
                            DIA
                        </label>
                        <div class="col-md-3">
                            <input class="form-control input-sm fechita" id="dia" name="dia" type="text" value="" autofocus="autofocus" placeholder="Ingrese Fecha">
                        </div>
                    </div>
                </form>

                <form class="hide form-horizontal" role="form" id="rango_dia">
                    <div class="form-group">
                        <label for="di" class="control-label col-md-1">
                            INICIO
                        </label>
                        <div class="col-md-3">
                            <input class="form-control input-sm fechita" id="di" name="di" type="text" value="" placeholder="Día Inicial">
                        </div>
                        <label for="df" class="control-label col-md-1">
                            FIN
                        </label>
                        <div class="col-md-3">
                            <input class="form-control input-sm fechita" id="df" name="df" type="text" value="" placeholder="Día Final">
                        </div>
                    </div>
                </form>

                <form class="hide form-horizontal" role="form" id="_mes">
                    <div class="form-group">
                        <label for="mes" class="control-label col-md-1">
                            MES
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="mes" name="mes">
                                <?php 
                                    for ($i = 0; $i < 12; $i++) {
                                 ?>
                                    <option value="<?=$numbermeses[$i]?>"><?=$namemeses[$i]?></option>
                                <?php 
                                    }
                                 ?>
                            </select>
                        </div>
                        <label for="mano" class="control-label col-md-1">
                            AÑO
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="mano" name="mano">
                                <?php 
                                    for ($i = 2020; $i < 2041; $i++) {
                                 ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?php 
                                    }
                                 ?>
                            </select>
                        </div>
                    </div>
                </form>

                <form class="hide form-horizontal" role="form" id="rango_mes">
                    <div class="form-group">
                        <label for="mi" class="control-label col-md-1">
                            M. INI.
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="mi" name="mi">
                                <?php 
                                    for ($i = 0; $i < 12; $i++) {
                                 ?>
                                    <option value="<?=$numbermeses[$i]?>"><?=$namemeses[$i]?></option>
                                <?php 
                                    }
                                 ?>
                            </select>
                        </div>
                        <label for="ami" class="control-label col-md-1">
                            AÑO
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="ami" name="ami">
                                <?php 
                                    for ($i = 2020; $i < 2041; $i++) {
                                 ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?php 
                                    }
                                 ?>
                            </select>
                        </div>
                        <label for="mf" class="control-label col-md-1">
                            M. FIN.
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="mf" name="mf">
                                <?php 
                                    for ($i = 0; $i < 12; $i++) {
                                 ?>
                                    <option value="<?=$numbermeses[$i]?>"><?=$namemeses[$i]?></option>
                                <?php 
                                    }
                                 ?>
                            </select>
                        </div>
                        <label for="amf" class="control-label col-md-1">
                            AÑO
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="amf" name="amf">
                                <?php 
                                    for ($i = 2020; $i < 2041; $i++) {
                                 ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?php 
                                    }
                                 ?>
                            </select>
                        </div>
                    </div>
                </form>  
                <hr>
                <form class="form-horizontal" role="form" id="_idtecnico">
                    <div class="form-group">
                        <label for="cboCliente" class="control-label col-md-1">
                            CLIENTE
                        </label>
                        <div class="col-md-8">
                            <select class="form-control input-sm" name="cboCliente" id="cboCliente">
                            </select>
                        </div>
                    </div>
                </form>                         
            </div>
            <div class="panel-footer">
                <div class="form-horizontal" role="form" id="p__dia">
                    <div class="form-group">
                        <div align="center" class="col-md-4">
                            <button value="btnVIS" form="_dia" class="btn btn-primary btn-line btn-rect btnR" type="button"><i class="icon-print"></i> PREVISUALIZAR REPORTE</button>
                        </div>
                        <div align="center" class="col-md-4">
                            <button value="btnPDF" form="_dia" class="btn btn-danger btn-line btn-rect btnR" type="button"><i class="icon-download-alt"></i> REPORTE PDF</button>
                        </div>
                        <div align="center" class="col-md-4">
                            <button value="btnEXC" form="_dia" class="btn btn-success btn-line btn-rect btnR" type="button"><i class="icon-download-alt"></i> REPORTE EXCEL</button>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
