<?php
include "../modelo/clsInstalacion.php";

$accion  = $_GET['accion'];
$instalacion = new Instalacion();

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
} 

if ($accion == "ListaInstalacion") {
    $limite  = $_POST['cboCantidadInstalaciones'];
    $cadena  = $_POST['txtFiltroInstalaciones'];
    $fecha   = $_POST['fechaInstalaciones'];
    $retorno = '';
    try {
        $rs = $instalacion->ListaInstalaciones($cadena, $limite, $fecha, $idempresa);        
        if ($rs->rowCount() > 0) {
        	$i = 1;
            foreach ($rs as $row) {
            	if($i == 1) {
            		$primeridserie = $row['id'];
            	}
                $retorno .= '<tr id="' . $row['id'] . '">';
                $retorno .= '<td>' . $row['orden'] . '</td>';
                $tecnicos = $row['nombre'];
                if($row['idtecnico2']) {
                    $tecnicos .= '<br>' . $instalacion->NombreTecnico($row['idtecnico2']) . '<br>';
                } if($row['idtecnico3']) {
                    $tecnicos .= $instalacion->NombreTecnico($row['idtecnico3']);
                }
                $retorno .= '<td>' . $tecnicos . '</td>';
                $retorno .= '<td>' . $row['fecha_liquidacion'] . '</td>';
                $retorno .= '<td>' . $row['observacion'] . '</td>';
                $retorno .= '<td>' . $row['actividad'] . '</td>';
                $retorno .= '<td>' . $row['prefijo'] . '</td>';
                $retorno .= '<td>' . $row['estado'] . '</td>';
                $retorno .= '<td>
                                <div class="row">
                                    <div class="col-sm-12 text-center" style="margin: 0; padding: 0">
                                        <a href="#" class="listarDetallesInstalacion label label-primary" data-id="' . $row['id'] . '" data-orden="' . $row['orden'] . '" data-toggle="modal" data-target="#listarDetallesInstalacion"><i class="icon-list"></i></a>
                                    </div>
                                </div>                            
                            </td>
                            </tr>';
                $i++;
            }

            $jsondata = array(
                'tabla' => $retorno,
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        } else {
            $jsondata = array(
                'tabla' => "tabla='<tr><td colspan='9'><center>NO HAY INSTALACIONES CON ESTE NOMBRE</center></td></tr>';",
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        }
    } catch (Exception $e) {
        echo "<tr colspan='9'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}

if ($accion == "ListaDetallesInstalacion") {
    $idinstalacion  = $_GET['idinstalacion'];
    $retorno = '';
    try {
        $rs = $instalacion->ListaDetallesInstalacion($idinstalacion, $idempresa);
        if ($rs->rowCount() > 0) {
            $i = 1;
            foreach ($rs as $row) {
                $retorno .= '<tr><td>' . $i . '</td>';
                $retorno .= '<td>' . $row['cantidad'] . '</td>';
                $tipo = $row['numero'][0];
                $idequipomaterial = substr($row['numero'], 1);
                $materiales = $instalacion->cargarDetallesEquipoMaterial($idequipomaterial, $tipo, $idempresa);
                foreach ($materiales as $material) {
                    $retorno .= '<td>' . $material['codigo'] . '</td>';
                    $retorno .= '<td>' . $material['descripcion'] . '</td>';
                    $retorno .= '<td>' . $material['tipostr'] . '</td>';
                    $retorno .= '<td>' . $material['serie'] . '</td>';
                    //$color = 'red';
                    //if ($material['estado'] == 'T') {
                        $color = 'green';
                    //}
                    //$retorno .= '<td style="color:' . $color . '">' . $material['estado'] . '</td>';
                    $retorno .= '<td style="color:' . $color . '">I</td>';
                    break;
                }
                $i++;
            }
            echo $retorno;
        } else {
            echo "<tr><td colspan='7'><center style='color:red'>ESTA INSTALACIÓN NO HA UTILIZADO MATERIALES NI EQUIPOS.</center></td></tr>";
        }
    } catch (Exception $e) {
        echo "<tr colspan='7'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}

if($accion == 'buscarLiquidacion') {
    try {
        $fecha = '';
        $observacion = '';
        $actividad = '';
        $ot = '';
        $tec1 = '';
        $tec2 = '';
        $tec3 = '';
        $prefijo = '';
        $telefono = '';
        $detalles = '';
        $mensaje = '<center><h4 style="color:blue">Información de Orden.</h4></center>';
        $orden = $_GET['liquidacion'];
        $rs = $instalacion->buscarLiquidacion($orden, $idempresa);
        if ($rs->rowCount() > 0) {
            foreach ($rs as $row) {
                $idliquidacion = $row['id'];
                $fecha = $row['fecha_liquidacion'];
                $observacion = $row['observacion'];
                $actividad = $row['actividad'];
                $ot = $row['ot'];
                if($row['idtecnico'] != '') {
                    $tec1 = $instalacion->NombreTecnico($row['idtecnico']);
                }                
                if($row['idtecnico2'] != '') {
                    $tec2 = $instalacion->NombreTecnico($row['idtecnico2']);
                }
                if($row['idtecnico3'] != '') {
                    $tec3 = $instalacion->NombreTecnico($row['idtecnico3']);
                }
                $prefijo = $row['prefijo'];
                $telefono = $row['telefono'];
                break;
            }   
            //Armamos los detalles
            $rs = $instalacion->buscarDetallesLiquidacion($idliquidacion); 
            if ($rs->rowCount() > 0) { 
                foreach ($rs as $row) {
                    //Nombre de la serie o Material
                    $serie = '';
                    $nombre = '';
                    $sap = '';
                    $tipo = substr($row['idequipomaterial'], 0, 1);
                    $idequipomaterial = substr($row['idequipomaterial'], 1);
                    $idparanombreysap = $idequipomaterial;
                    if($tipo == 'S') {
                        $rs2 = $instalacion->obtenerSerieLiquidacion($idequipomaterial);
                        if ($rs2->rowCount() > 0) { 
                            foreach ($rs2 as $roww) {
                                $serie = $roww['serie'];
                                $idparanombreysap = $roww['idequipomaterial'];
                                break;
                            }
                        }
                    }

                    $rs2 = $instalacion->obtenerNombreSapEquipoMaterial($idparanombreysap);
                    if ($rs2->rowCount() > 0) {
                        foreach ($rs2 as $roww) {
                            $nombre = $roww['descripcion'];
                            $sap = $roww['codigo'];
                            break;
                        }
                    }
                    
                    $detalles .= '<tr>' .
                                    '<td>' . $sap . '</td>
                                    <td>' . $nombre . '</td>
                                    <td>' . $serie . '</td>
                                    <td>' . $row['cantidad'] . '</td>
                                    <td>' . $row['cantidadcobra'] . '</td>
                                </tr>';
                }   
            } else {
                 $detalles .= '<tr><td colspan="5" style="color:red;"><center>No hay detalles para esta instalación.</center></td></tr>';
            }   
        } else {
            $mensaje = '<center><h4 style="color:red">No Existe esta Orden.</h4></center>';
        }
        $jsondata = array(
            'mensaje' => $mensaje,
            'fecha' => $fecha,
            'observacion' => $observacion,
            'actividad' => $actividad,
            'ot' => $ot,
            'tec1' => $tec1,
            'tec2' => $tec2,
            'tec3' => $tec3,
            'prefijo' => $prefijo,
            'telefono' => $telefono,
            'detalles' => $detalles,
        );
        echo json_encode($jsondata, JSON_FORCE_OBJECT);
    } catch (Exception $e) {
        echo 'OCURRIÓ UN ERROR';
    }
}