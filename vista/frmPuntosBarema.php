<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
?>
<script src="../assets/js/scriptPuntosBarema.js"></script>
<style>
    tr td{
        padding: 3px !important;
        margin: 3px !important;
    }
</style>
<div class="row">
    <br>
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-12">
                        <h4><i class="glyphicon glyphicon-signal"></i> &nbsp;Puntos Barema</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body" id="mantenimiento">
                <div class="row">
                    <div class="col-md-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="25%">#</th>
                                        <th width="75%">PREFIJO</th>
                                    </tr>
                                </thead>
                                <tbody id="divprefijo"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <center>
                                                <a href="#" class="btn btn-info btn-sm new" id="prefijo">
                                                    <i class="icon-plus"></i>
                                                </a>
                                            </center>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="15%">#</th>
                                        <th width="85%">ACTIVIDAD</th>
                                    </tr>
                                </thead>
                                <tbody id="divactividad"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <center>
                                                <a href="#" class="btn btn-warning btn-sm new" id="actividad">
                                                    <i class="icon-plus"></i>
                                                </a>
                                            </center>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="25%">PREFIJO</th>
                                        <th width="35%">ACTIVIDAD</th>
                                        <th width="35%">PUNTAJE</th>
                                    </tr>
                                </thead>
                                <tbody id="divbaremo"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">
                                            <center>
                                                <a href="#" class="btn btn-success btn-sm new" id="baremo">
                                                    <i class="icon-plus"></i>
                                                </a>
                                            </center>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>