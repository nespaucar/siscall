<?php
include "../modelo/clsTelefono.php";

$accion   = $_GET['accion'];
$otelefono = new Telefono();

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
}

if ($accion == "comprobarExistenciaCelular") {
    $telefono = $_GET["numero"];
    try {
        $rs = $otelefono->comprobarNumero($telefono);
        if (!$rs) {
        	echo json_encode("1");
        } else {
            echo json_encode("2");
        }
    } catch (Exception $e) {
        echo json_encode("3");
    }
}

if ($accion == "ListaTelefonos") {
    $tipo    = $_GET['tipo'];
    $usuario = 'Telefonos';
    $limite  = $_POST['cboCantidadTelefonos'];
    $cadena  = $_POST['txtFiltroTelefonos'];
    $retorno = '';
    try {
        $rs = $otelefono->ListaTelefonos($cadena, $limite, $tipo, $idempresa);
        if ($rs->rowCount() > 0) {
            $i = 1;
            foreach ($rs as $row) {
                $retorno .= '<tr id="' . $row['idtelefono'] . '">';
                $retorno .= '<td>' . $row['numero'] . '</td>';
                $retorno .= '<td>' . $row['id_AB'] . '</td>';
                $retorno .= '<td>' . $row['nombre'] . '</td>';
                $retorno .= '<td>' . $row['tipo'] . '</td>';
                $retorno .= '<td>
                            <div class="row">
                                <div class="col-sm-6 text-center" style="margin: 0; padding: 0">
                                    <div id="us' . $row['idusuario'] . '">
                                        <a href="#" class="btnPropUsuario btn btn-primary btn-xs" data-nombre="' . $row['nombre'] . '" data-id="' . $row['idusuario'] . '" data-estado="' . $row['estado'] . '" data-toggle="modal" data-target="#propUsuModal">
                                            <i class="icon-user"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-center" style="margin: 0; padding: 0">
                                    <div id="us' . $row['idusuario'] . '">
                                        <a href="#" class="eliminarBean btn btn-xs btn-danger" data-clase="Telefonos" data-table="al ' . substr($usuario, 0, strlen($usuario) - 1) . '" data-nombre="' . $row['nombre'] . '" data-id="' . $row['idpersona'] . '" data-toggle="modal" data-target="#deleteModal"><i class="icon-remove"></i>
                                        </a>
                                    </div>
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
                'tabla' => "tabla='<tr><td colspan='5'><center>NO HUBO COINCIDENCIAS</center></td></tr>';",
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        }
    } catch (Exception $e) {
        echo "<tr colspan='5'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}

if($accion == 'cargarNumeros') {
    $id = $_GET['id'];
    if($id !== "") {
        $retorno = '';
        try {
            $rs = $otelefono->cargarNumeros($id);
            if ($rs->rowCount() > 0) {
                $i = 1;
                foreach ($rs as $row) {
                    $retorno .= '<tr id="' . $row['idtelefono'] . '">';
                    $retorno .= '<td>' . $row['numero'] . '</td>';
                    $retorno .= '<td>
                                <div class="row">
                                    <div class="col-sm-12 text-center" style="margin: 0; padding: 0">
                                        <div id="tel' . $row['idtelefono'] . '">
                                            <a href="#" class="eliminarBean btn btn-xs btn-danger" data-clase="Telefonos" data-table="al Teléfono" data-nombre="' . $row['numero'] . '" data-id="' . $row['idtelefono'] . '" data-toggle="modal" data-target="#deleteModal"><i class="icon-remove"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                </td>
                                </tr>';
                    $i++;             
                }
            }
            $jsondata = array(
                'tabla' => $retorno,
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        } catch (Exception $e) {
            echo "<tr colspan='2'><b>OCURRIÓ UN ERROR</b></tr>";
        }
    }
}

if ($accion == "eliminar") {
    $id = $_GET['id'];
    try {
        $rs = $otelefono->eliminar($id);
        if ($rs) {
            echo '<h4 class="titulo modal-title"><b style="color:green;">Eliminado Correctamente.</b></h4>';
        }
    } catch (Exception $e) {
        echo '<h4 class="titulo modal-title"><b style="color:red;">No se pudo eliminar.</b></h4>';
    }
}