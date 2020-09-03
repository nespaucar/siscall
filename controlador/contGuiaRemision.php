<?php
include "../modelo/clsGuiaRemision.php";

$accion  = $_GET['accion'];
$guiaremision = new GuiaRemision();
date_default_timezone_set('America/Lima');

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
} 

if ($accion == "ListaGuiaRemision") {
    $limite  = $_POST['cboCantidadGuiasRemision'];
    $cadena  = $_POST['txtFiltroGuiasRemision'];
    $fecha   = $_POST['fechaGuiasRemision'];
    $retorno = '';
    try {
        $rs = $guiaremision->ListaGuiasRemision($cadena, $limite, $fecha, $idempresa);
        if ($rs->rowCount() > 0) {
            $i = 1;
            foreach ($rs as $row) {
                if($i == 1) {
                    $primeridserie = $row['id'];
                } else if($i == $rs->rowCount()) {
                    $ultimoidserie = $row['id'];
                }
                $fechafinal = strtotime('+10 day', strtotime($row['fecha']));
                $fechafinal = date('Y-m-j', $fechafinal);
                $fechahoy = date('Y-m-j');
                $fechai = new DateTime($fechahoy);
                $fechaf = new DateTime($fechafinal);
                $intervalo = $fechai->diff($fechaf);
                $retorno .= '<tr id="' . $row['id'] . '">';
                $retorno .= '<td>' . $row['numero'] . '</td>';
                $retorno .= '<td>' . $row['fecha'] . '</td>';
                $retorno .= '<td>' . $fechafinal . '</td>';
                if($intervalo->format('%R') == '+') {
                    if ($intervalo->format('%a') == '0'){
                        $situacion = '<font style="color:orange">HOY ES EL ÚLTIMO DÍA DE MANTENER.</font>';
                    } else {
                        $situacion = '<font style="color:green">FALTAN ' . $intervalo->format('%a DÍAS POR MANTENER.') . '</font>';
                    }
                } else {
                    $situacion = '<font style="color:red">SE PASARON '. $intervalo->format('%a DÍAS DE MANTENER.') . '</font>';
                } 
                $retorno .= '<td>' . $situacion . '</td>';
                /*
                $color = 'red';
                $sms = 'EN ALMACÉN';
                if($row['estado'] == 1) {
                    $color = 'green';
                    $sms = 'CANCELADA';
                }
                $retorno .= '<td style="color:' . $color . '">' . $sms . '</td>';
                */
                $retorno .= '<td>
                    <div class="row">
                        <div class="col-sm-12 text-center" style="margin: 0; padding: 0">
                        	<a href="#" class="detalleguiaremision label label-primary" data-id="' . $row['id'] . '" data-numero="' . $row['numero'] . '" data-toggle="modal" data-target="#detallesequiposmateriales"><i class="icon-list"></i>
                        	</a>
                        </div>
                    </div>
                </td></tr>';
                $i++;
            }
            
            $jsondata = array(
                'tabla' => $retorno,
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        } else {
            $jsondata = array(
                'tabla' => "tabla='<tr><td colspan='7'><center>NO HAY GUÍAS DE REMISIÓN CON ESTE NOMBRE</center></td></tr>';",
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        }
    } catch (Exception $e) {
        echo "<tr colspan='7'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}

if ($accion == "ListaDetallesGuiaRemision") {
    $idguiaremision  = $_GET['idguiaremision'];
    $retorno = '';
    try {
        $rs = $guiaremision->ListaDetallesGuiasRemision($idguiaremision, $idempresa);
        if ($rs->rowCount() > 0) {
            $i = 1;
            foreach ($rs as $row) {
                $serie = $row['serie'];
                $est = $row['estado'];
                if(!$row['serie']) {
                    $serie = '-';
                    $est = '-';
                }
                $estado = '';                
                if($row['estado'] == 'A') {
                    $estado = ' style="color:red"';
                } else if ($row['estado'] == 'T') {
                    $estado = ' style="color:green"';
                }
                $retorno .= '<tr><td>' . $i . '</td>';
                $retorno .= '<td>' . $row['cantidad'] . '</td>';
                $retorno .= '<td>' . $row['codigo'] . '</td>';
                $retorno .= '<td>' . $row['descripcion'] . '</td>';
                $retorno .= '<td>' . $row['tipostr'] . '</td>';
                $retorno .= '<td>' . $serie . '</td>';
                $retorno .= '<td' . $estado . '>' . $est . '</td></tr>';
                $i++;
            }
            echo $retorno;
        } else {
            echo "<tr><td colspan='7'><center>ESTA GUÍA DE REMISIÓN NO TIENE MATERIALES ASIGNADOS.</center></td></tr>";
        }
    } catch (Exception $e) {
        echo "<tr colspan='7'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}

if ($accion == "nuevaGuiaRemisionManual") {
    $bloque = $_GET['bloque'];
    $fecha = date('Y-m-j');
    $cant = $guiaremision->ListaGuiasRemisionManuales($idempresa);
    $numero = 'MANUAL-' . ($cant->rowCount() + 1);
    try {
        $rs = $guiaremision->nuevaGuiaRemision($numero, $fecha, '0', $idempresa);
        if ($rs->rowCount() > 0) {
            $detalles = explode('@@@', $bloque);

            for ($i = 0; $i < count($detalles) - 1; $i++) { 
                $det = explode('@@', $detalles[$i]);
                $idmaterial = $det[0];
                $cantidad = $det[1];
                $guiaremision->nuevoDetalleGuiaRemision($numero, $idmaterial, $cantidad, ($i + 1) . $numero, $idempresa);
                $guiaremision->aumentarStockMaterial($idmaterial, $cantidad, $idempresa);
            }
            echo 'Guía de Remisión Nº ' . $numero . ' registrada Correctamente.';
        } else {
            echo 'No se pudo Registrar.';
        }
    } catch (Exception $e) {
        echo 'No se pudo Registrar.';
    }
}

if($accion == 'pp') {
    $rs = $guiaremision->cambiarEstadosInstalados();
    foreach ($rs as $r) {
        $guiaremision->cambioEstadoInstalado($r['extract']);
    }
}