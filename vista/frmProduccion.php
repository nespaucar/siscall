<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
date_default_timezone_set('America/Lima');
$sdate = date("d") . "-" . date("m") . "-" . date("Y");
?>
<script>
    $( function() {
        inicializarFecha();        
    });
</script>
<script src="../assets/js/scriptProduccion.js"></script>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-12">
                        <h4><i class="icon-bar-chart"></i> &nbsp;Producción</h4>
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
                                <option value="01">ENERO</option>
                                <option value="02">FEBRERO</option>
                                <option value="03">MARZO</option>
                                <option value="04">ABRIL</option>
                                <option value="05">MAYO</option>
                                <option value="06">JUNIO</option>
                                <option value="07">JULIO</option>
                                <option value="08">AGOSTO</option>
                                <option value="09">SETIEMBRE</option>
                                <option value="10">OCTUBRE</option>
                                <option value="11">NOVIEMBRE</option>
                                <option value="12">DICIEMBRE</option>
                            </select>
                        </div>
                        <label for="mano" class="control-label col-md-1">
                            AÑO
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="mano" name="mano">
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                                <option value="2032">2032</option>
                                <option value="2033">2033</option>
                                <option value="2034">2034</option>
                                <option value="2035">2035</option>
                                <option value="2036">2036</option>
                                <option value="2037">2037</option>
                                <option value="2038">2038</option>
                                <option value="2039">2039</option>
                                <option value="2040">2040</option>
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
                                <option value="01">ENERO</option>
                                <option value="02">FEBRERO</option>
                                <option value="03">MARZO</option>
                                <option value="04">ABRIL</option>
                                <option value="05">MAYO</option>
                                <option value="06">JUNIO</option>
                                <option value="07">JULIO</option>
                                <option value="08">AGOSTO</option>
                                <option value="09">SETIEMBRE</option>
                                <option value="10">OCTUBRE</option>
                                <option value="11">NOVIEMBRE</option>
                                <option value="12">DICIEMBRE</option>
                            </select>
                        </div>
                        <label for="ami" class="control-label col-md-1">
                            AÑO
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="ami" name="ami">
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                                <option value="2032">2032</option>
                                <option value="2033">2033</option>
                                <option value="2034">2034</option>
                                <option value="2035">2035</option>
                                <option value="2036">2036</option>
                                <option value="2037">2037</option>
                                <option value="2038">2038</option>
                                <option value="2039">2039</option>
                                <option value="2040">2040</option>
                            </select>
                        </div>
                        <label for="mf" class="control-label col-md-1">
                            M. FIN.
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="mf" name="mf">
                                <option value="01">ENERO</option>
                                <option value="02">FEBRERO</option>
                                <option value="03">MARZO</option>
                                <option value="04">ABRIL</option>
                                <option value="05">MAYO</option>
                                <option value="06">JUNIO</option>
                                <option value="07">JULIO</option>
                                <option value="08">AGOSTO</option>
                                <option value="09">SETIEMBRE</option>
                                <option value="10">OCTUBRE</option>
                                <option value="11">NOVIEMBRE</option>
                                <option value="12">DICIEMBRE</option>
                            </select>
                        </div>
                        <label for="amf" class="control-label col-md-1">
                            AÑO
                        </label>
                        <div class="col-md-2">
                            <select class="form-control input-sm" id="amf" name="amf">
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                                <option value="2028">2028</option>
                                <option value="2029">2029</option>
                                <option value="2030">2030</option>
                                <option value="2031">2031</option>
                                <option value="2032">2032</option>
                                <option value="2033">2033</option>
                                <option value="2034">2034</option>
                                <option value="2035">2035</option>
                                <option value="2036">2036</option>
                                <option value="2037">2037</option>
                                <option value="2038">2038</option>
                                <option value="2039">2039</option>
                                <option value="2040">2040</option>
                            </select>
                        </div>
                    </div>
                </form>  
                <hr>
                <form class="form-horizontal" role="form" id="_idtecnico">
                    <div class="form-group">
                        <label for="cboTecnicos" class="control-label col-md-1">
                            TECNICO
                        </label>
                        <div class="col-md-8">
                            <select class="form-control input-sm" name="cboTecnicos" id="cboTecnicos">
                            </select>
                        </div>
                    </div>
                </form>                         
            </div>
            <div class="panel-footer">
                <div class="form-horizontal" role="form" id="p__dia">
                    <div class="form-group">
                        <div align="center" class="col-md-4">
                            <button value="btnVIS" form="_dia" class="btn btn-primary btnR" type="button"><i class="icon-print"></i> VISUALIZAR</button>
                        </div>
                        <div align="center" class="col-md-4">
                            <button value="btnPDF" form="_dia" class="btn btn-danger btnR" type="button"><i class="icon-download-alt"></i> PDF</button>
                        </div>
                        <div align="center" class="col-md-4">
                            <button value="btnEXC" form="_dia" class="btn btn-success btnR" type="button"><i class="icon-download-alt"></i> EXCEL</button>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
