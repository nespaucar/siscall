<?php
include "../modelo/clsPuntosBarema.php";

$accion  = $_GET['accion'];
$puntosbarema = new PuntosBarema();

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
} 

if ($accion == "new") {
    $tabla  = $_GET['tabla'];
    $nombre  = $_GET['nombre'];
    $idprefijo  = $_GET['idprefijo'];
    $idactividad  = $_GET['idactividad'];
    try {
        if($tabla == 'baremo') {
            $rs = $puntosbarema->noduplicidad2($idprefijo, $idactividad, $idempresa);
        } else {            
            $rs = $puntosbarema->noduplicidad('nombre', $nombre, $tabla, $idempresa);
        }
        if ($rs == 0) {
            if($tabla == 'baremo') {
                $rs = $puntosbarema->nuevo($nombre, $tabla, $idempresa, $idprefijo, $idactividad);
            } else {
                $rs = $puntosbarema->nuevo($nombre, $tabla, $idempresa);
            }            
            foreach ($rs as $row) {
                echo $row['id'];
                break;
            } 
        } else {
            echo '0';
        }            
    } catch (Exception $e) {
        echo "<b>OCURRIÓ UN ERROR</b>";
    }
}

if ($accion == "llenarTablas") {
    $tabla  = $_GET['tabla'];
    $retorno = '';
    try {
        $rs = $puntosbarema->lista($tabla, $idempresa);
        $i = 1;
        if ($rs->rowCount() > 0) {
            foreach ($rs as $row) {
                if($tabla != 'baremo') {
                    $retorno .= '<tr>
                        <td class="num">' . $i . '</td>
                        <td class="inpt">
                            <center class="objectId">
                                <div class="col-md-9">' . $row['nombre'] . '</div>
                                <div class="col-md-3">
                                    <button class="label label-danger removepb" data-tabla="' . $tabla . '" data-id="' . $row['id'] . '">x</button>
                                </div>
                            </center>
                        </td>
                    </tr>';
                } else {                    
                    $retorno .= '<tr>
                        <td class="num">' . $i . '</td>
                        <td class="tdprefijo">
                            <center class="objectId">
                                <div class="col-md-9">' . $row['prefijo'] . '</div>
                            </center>
                        </td>
                        <td class="tdactividad">
                            <center class="objectId">
                                <div class="col-md-9">' . $row['actividad'] . '</div>
                            </center>
                        </td>
                        <td class="tdpuntaje">
                            <center class="objectId">
                                <div class="col-md-9">' . $row['puntaje'] . '</div>
                                <div class="col-md-3">
                                    <button class="label label-danger removepb" data-tabla="baremo" data-id="' . $row['id'] . '">x</button>
                                </div>
                            </center>
                        </td>
                    </tr>';
                }
                $i++;
            } 
        }
        echo $retorno;           
    } catch (Exception $e) {
        echo "<b>OCURRIÓ UN ERROR</b>";
    }
}

if($accion == 'eliminar') {
    $tabla  = $_GET['tabla'];
    $id  = $_GET['id'];
    try {
        if($tabla != 'baremo') {
            $rs = $puntosbarema->comprobarexistenciabaremo($id, $tabla, $idempresa);
            if($rs == 0) {
                $rs = $puntosbarema->eliminar($id, $tabla, $idempresa);
                echo '1';
            } else {
                echo '0';
            }
        } else {
            $rs = $puntosbarema->eliminar($id, $tabla, $idempresa);
            echo '1';
        }                    
    } catch (Exception $e) {
        echo "<b>OCURRIÓ UN ERROR</b>";
    }
}