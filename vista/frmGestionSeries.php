<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script>
    $(document).ready(function() {
        $('input[name=txtSerie]').focus();
        $('#mensajeDetalleSerie').css('color', 'blue').html('Encuentra una serie.');
    });
</script>
<script src="../assets/js/scriptGestionSerie.js"></script>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-12">
                        <h4><i class="glyphicon glyphicon-qrcode"></i> &nbsp;Gestión de Series</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body" id="mantenimiento">
                <div class="dataTables_wrapper form-inline" role="grid">
                    <form id="form_search" method="POST" onsubmit="buscarSerie(); return false;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_length">
                                    <label>
                                        Buscar Serie: 
                                        <input type="text" id="txtSerie" name="txtSerie" class="form-control" placeholder="Ingrese una serie">
                                        <button class="btn btn-success" type="submit">
                                            <i class="icon-search"></i>
                                        </button>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <hr>
                <center><h3 id="mensajeDetalleSerie"></h3></center>
                <hr>
                <div id="detalleSerie"></div>
            </div>
        </div>
    </div>
</div>
