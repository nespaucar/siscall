<?php
include "../modelo/clsUsuario.php";

$accion   = $_GET['accion'];
$usuarios = new Usuario();

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
}

if ($accion == "recoverPass") {
    $email = $_POST["email"];
    try {
        $rs = $usuarios->recoverPass($email, $idempresa);
        if ($rs->rowCount() > 0) {
            foreach ($rs as $row) {
                $pass      = substr(md5(rand(5, 100)), 0, 8);
                $usuarios2 = new Usuario();

                $rs2 = $usuarios2->reiniciarPass(md5($pass), $row['id'], $idempresa);

                $envio = mail($email, 'De: JackPolux', 'Recuperación de Contraseña para el usuario ' . $row['nombre'], 'Su contraseña nueva es: ' . $pass);
                if($envio) {
                    echo "respuesta='1';";
                } else {
                    echo "respuesta='2';";
                }                
            }
        } else {
            echo "respuesta='2';";
        }
    } catch (Exception $e) {
        echo "respuesta='3';";
    }
}

if ($accion == "cambiarestadousuario") {
    $id     = $_GET['id'];
    $estado = $_GET['estado'];
    $nombre = $_GET['nombre'];
    if ($estado == '1') {
        $nuevoestado = '0';
        $btn         = 'danger';
        $txt         = 'Deshabilitado';

    } else {
        $nuevoestado = '1';
        $btn         = 'success';
        $txt         = 'Habilitado';
    }
    try {
        $rs = $usuarios->cambiarestadousuario($id, $nuevoestado, $idempresa);
        if ($rs) {
            $boton  = '<a data-id="' . $id . '" data-estado="' . $nuevoestado . '" data-nombre="' . $nombre . '" class="btnEstadoUsuario btn btn-' . $btn . ' btn-sm">' . $txt . '</i></a>';
            $boton2 = '<a href="#" class="btnPropUusuario label label-primary" data-nombre="' . $nombre . '" data-id="' . $id . '" data-estado="' . $nuevoestado . '" data-toggle="modal" data-target="#propUsuModal"><i class="icon-user"></i></a>';
            echo "btn='" . $boton . "';btn2='" . $boton2 . "'";
        }
    } catch (Exception $e) {
        echo 'btn="Error inesperado";btn2=""';
    }
}

if ($accion == "resetearclave") {
    $id = $_GET['id'];
    try {
        $rs = $usuarios->resetearclave($id, $idempresa);
        if ($rs) {
            echo 'La clave se inicializó correctamente.';
        }
    } catch (Exception $e) {
        echo 'Error inesperado';
    }
}

if ($accion == "cambiarclave") {
    $clavenueva = $_GET['clavenueva'];
    try {
        $rs = $usuarios->cambiarclave($clavenueva, $idempresa);
        if ($rs) {
            echo 'Tu clave se cambió correctamente.';
        } else {
            echo 'Tu clave no pudo cambiarse.';
        }
    } catch (Exception $e) {
        echo 'Error inesperado';
    }
}

if ($accion == "ListaUsuarios") {
    $tipo    = $_GET['tipo'];
    $usuario = 'Usuarios';
    $limite  = $_POST['cboCantidadUsuarios'];
    $cadena  = $_POST['txtFiltroUsuarios'];
    $retorno = '';
    try {
        $rs = $usuarios->ListaUsuarios($cadena, $limite, $tipo, $idempresa);
        if ($rs->rowCount() > 0) {
            $i = 1;
            foreach ($rs as $row) {
                $retorno .= '<tr id="' . $row['idusuario'] . '">';
                $retorno .= '<td>' . $row['nombre'] . '</td>';
                $retorno .= '<td>' . $row['direccion'] . '</td>';
                $retorno .= '<td>' . $row['nombreusuario'] . '</td>';
                $retorno .= '<td>
                            <div class="row">
                                <div class="col-sm-12 text-center" style="margin: 0; padding: 0">
                                    <div id="us' . $row['idusuario'] . '">
                                        <a href="#" class="btnPropUsuario btn btn-primary btn-xs" data-nombre="' . $row['nombreusuario'] . '" data-id="' . $row['idusuario'] . '" data-estado="' . $row['estado'] . '" data-toggle="modal" data-target="#propUsuModal">
                                            <i class="icon-user"></i> Habilitar / Resetear
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
                'tabla' => "tabla='<tr><td colspan='7'><center>NO HAY USUARIOS CON ESTE NOMBRE</center></td></tr>';",
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        }
    } catch (Exception $e) {
        echo "<tr colspan='7'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}
