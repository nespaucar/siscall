<?php
include "../modelo/clsPersonal.php";
include "../modelo/clsTelefono.php";
include "../modelo/clsUsuario.php";
include("../frmGetCode/keys.php");
require '../twilio-php-main/src/Twilio/autoload.php';
use Twilio\Rest\Client;

$accion   = $_GET['accion'];
$personal = new Personal();
$otelefono = new Telefono();
$client = new Client(TWILIO_ID, TWILIO_TOKEN);

if ($accion == "login") {
    $nombre = $_POST["nombre"];
    $pass   = $_POST["pass"];
    try {
        $rs = $personal->login($nombre, md5($pass));
        if ($rs->rowCount() > 0) {
            foreach ($rs as $row) {
                if ($row['estado'] == 1) {
                    ini_set("session.cookie_lifetime", "144000000");
                    ini_set("session.gc_maxlifetime", "144000000");
                    session_start();
                    $_SESSION['id']     = $row['id'];
                    $_SESSION['tipo']   = $row['tipo'];
                    $_SESSION['nombre'] = $row['nombre'];
                    $_SESSION['cuenta'] = $row['cuenta'];
                    $_SESSION['email']  = $row['email'];
                    $_SESSION['idempresa']  = $row['idempresa'];
                    $_SESSION['logo']  = $row['logo'];
                    $_SESSION['logo2']  = $row['logo2'];
                    $_SESSION['nombre']  = $row['nombre'];
                    $_SESSION['correo']  = $row['correo'];
                    $_SESSION['descripcion']  = $row['descripcion'];
                    $_SESSION['direccion']  = $row['direccion'];
                    $_SESSION['mision']  = $row['mision'];
                    $_SESSION['vision']  = $row['vision'];
                    $_SESSION['sucursal']  = $row['sucursal'];
                    $_SESSION['paginaweb']  = $row['paginaweb'];
                    $_SESSION['telefono']  = $row['telefono'];
                    $_SESSION['nombreempresa']  = $row['nombreempresa'];                    
                    echo '1';
                    break;
                } else {
                    echo '2';
                }
            }
        } else {
            echo '3';
        }
    } catch (Exception $e) {
        echo '3';
    }
}

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
} 

if ($accion == "recoverPass") {
    $nombre = $_POST["name"];
    try {
        $rs = $personal->recoverPass($nombre, 1);
        if ($rs->rowCount() > 0) {
            foreach ($rs as $row) {
                $pass      = substr(md5(rand(5, 100)), 0, 8);
                $usuarios2 = new Usuario();
                $rs2 = $usuarios2->reiniciarPass(md5($pass), $row['id'], 1);
                // Envío la contraseña al usuario
                $celulares = $otelefono->cargarNumeros($row['id']);
                if ($celulares->rowCount() > 0) {
                    //ENVÍO MENSAJE A LOS NÚMEROS AFILIADOS A ESTE CLIENTE CON TWILIO
                    foreach ($celulares as $row2) {
                        $message = $client->messages
                          ->create("+51" . $row2['numero'], // to
                          [
                            "from" => "+15124563240",
                            "body" => "Recuperación de Contraseña para el usuario " . $nombre . "\nSu contraseña nueva es: " . $pass
                          ]
                        );
                    }
                    echo "respuesta='1';";
                } else {
                    echo "respuesta='4';";
                }
                //$envio = mail($email, 'De: JackPolux', );
                /*if($envio) {
                    echo "respuesta='1';";
                } else {
                    echo "respuesta='2';";
                }*/                
            }
        } else {
            echo "respuesta='2';";
        }
    } catch (Exception $e) {
        echo "respuesta='3';";
    }
}

if ($accion == "ListaPersonal") {
    $tipo    = $_GET['tipo'];
    $usuario = 'Personas';
    $limite  = $_POST['cboCantidadPersonal'];
    $cadena  = $_POST['txtFiltroPersonal'];
    $retorno = '';
    try {
        $rs = $personal->ListaPersonal($cadena, $limite, $tipo, $idempresa);
        if ($rs->rowCount() > 0) {
            $i = 1;
            foreach ($rs as $row) {
                $rtelefonos = $otelefono->ListaTelefonosUsuario($row['idpersona']);
                $cadenatelefonos = '';
                if ($rtelefonos->rowCount() > 0) {
                    foreach ($rtelefonos as $rt) {
                        $cadenatelefonos .= $rt['numero'] . "<br>";
                    }
                }
                $retorno .= '<tr id="' . $row['idpersona'] . '">';
                $retorno .= '<td>' . $row['nombre'] . '</td>';
                $retorno .= '<td>' . $row['id_AB'] . '</td>';
                $retorno .= '<td>' . $row['direccion'] . '</td>';
                $retorno .= '<td>' . $row['telefono'] . '</td>';
                $retorno .= '<td>' . $row['tipo'] . '</td>';
                $retorno .= '<td>
                            <div class="row">
                                <div class="col-sm-4 text-center" style="margin: 0; padding: 0">
                                    <div id="us' . $row['idusuario'] . '">
                                        <a href="#" class="btnPropUsuario btn btn-xs btn-primary" data-nombre="' . $row['nombreusuario'] . '" data-id="' . $row['idusuario'] . '" data-estado="' . $row['estado'] . '" data-toggle="modal" data-target="#propUsuModal">
                                            <i class="icon-user"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-sm-4 text-center" style="margin: 0; padding: 0">
                                    <a href="#" class="btn btn-xs btn-success modificar" data-opcion="0" data-bean="' . $usuario . '" data-id="' . $row['idpersona'] . '"><i class="icon-edit"></i></a>
                                </div>
                                <div class="col-sm-4 text-center" style="margin: 0; padding: 0">
                                    <a href="#" class="eliminarBean btn btn-xs btn-danger" data-clase="Personal" data-table="al ' . substr($usuario, 0, strlen($usuario) - 1) . '" data-nombre="' . $row['nombre'] . '" data-id="' . $row['idpersona'] . '" data-toggle="modal" data-target="#deleteModal"><i class="icon-remove"></i></a>
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
                'tabla' => "tabla='<tr><td colspan='6'><center>NO HUBO COINCIDENCIAS</center></td></tr>';",
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        }
    } catch (Exception $e) {
        echo "<tr colspan='7'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}

if ($accion == "noduplicidad") {
    $campo   = $_GET['campo'];
    $palabra = $_GET['palabra'];
    $bean    = $_GET['bean'];
    if ($campo == 'pass') {
        $palabra = md5($palabra);
    }
    try {
        $rs = $personal->noduplicidad($campo, $palabra, $bean, $idempresa);
        if ($rs->rowCount() > 0) {
            echo json_encode("true");
        } else {
            echo json_encode("false");
        }
    } catch (Exception $e) {
        echo json_encode("false");
    }
}

if ($accion == "nuevo") {
    $nombres           = $_POST['nombres'];
    $apellidos         = $_POST['apellidos'];
    $id_AB             = $_POST['id_AB'];
    $direccion         = $_POST['direccion'];
    $tipo              = $_POST['tipo'];
    $cantidadcelulares = $_POST['cantidadcelulares'];
    $telefono          = $_POST['telefono'];
    $procederegistro   = true;
    $procederegistro2  = true;
    $celular           = '';
    $mensaje           = '';
    try {
        //Compruebo los teléfonos
        $procederegistro2 = $otelefono->comprobarNumero($telefono);
        if(!$procederegistro2) {
            $mensaje .= '<p style="color: red;"><i class="icon-check"></i> El número de celular ' . $telefono . ' ya se encuentra registrado.</p>';
        }
        if($cantidadcelulares > 0) {
            for ($cc=0; $cc < $cantidadcelulares; $cc++) { 
                $celular = $_POST['celular' . $cc];
                $procederegistro = $otelefono->comprobarNumero($celular);
                if(!$procederegistro) {
                    $mensaje .= '<p style="color: red;"><i class="icon-check"></i> El número de celular ' . $celular . ' ya se encuentra registrado.</p>';
                }
            }
        }
        if($procederegistro && $procederegistro2) {
            $rs = $personal->nuevo($nombres, $apellidos, $id_AB, $direccion, $tipo, $idempresa);
            if ($rs->rowCount() > 0) {
                if($tipo == '2') {
                    $rs = $personal->DatosPersona($id_AB, $idempresa);
                    foreach ($rs as $row) {
                        $idpersona = $row['id'];
                        break;
                    }
                }
                $mensaje = '<p style="color: green;"><i class="icon-check"></i> Persona registrada Correctamente.</p><p style="color: green;"><i class="icon-check"></i> Usuario registrado Correctamente.</p><p style="color: green;"><i class="icon-check"></i> La contraseña inicial para este usuario es "admin".</p>';
                // Registramos los celulares
                $rs2 = $otelefono->nuevo($telefono, $idpersona);
                $mensaje .= '<p style="color: green;"><i class="icon-check"></i> Número ' . $telefono . ' registrado.</p>';
                if($cantidadcelulares > 0) {
                    for ($cc=0; $cc < $cantidadcelulares; $cc++) { 
                        $celular = $_POST['celular' . $cc];
                        $procederegistro = $otelefono->nuevo($celular, $idpersona);
                        $mensaje .= '<p style="color: green;"><i class="icon-check"></i> Número ' . $celular . ' registrado.</p>';
                    }
                }
            } else {
                $mensaje = '<p style="color: red;"><i class="icon-check"></i> No se pudo Registrar.</p>';
            }
        }
        echo $mensaje;
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> No se pudo Registrar.</p>';
    }
}

if ($accion == "modificar") {
    $nombres   = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $id_AB     = $_POST['id_AB'];
    $direccion = $_POST['direccion'];
    $tipo      = $_POST['tipo'];
    $id        = $_GET['id'];
    try {
        $rs = $personal->modificar($id, $nombres, $apellidos, $id_AB, $direccion, $tipo, $idempresa);
        if ($rs) {
            echo '<p style="color: green;"><i class="icon-check"></i> Persona editada Correctamente.</p><p style="color: green;">';
        }
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> No se pudo Editar.</p>';
    }
}

if ($accion == "eliminar") {
    $id = $_GET['id'];
    try {
        $rs = $personal->eliminar($id, $idempresa);
        if ($rs) {
            echo '
                <h4 class="titulo modal-title"><b style="color:green;">Eliminado Correctamente.</b></h4>';
        }
    } catch (Exception $e) {
        echo '<h4 class="titulo modal-title"><b style="color:red;">No se pudo eliminar.</b></h4>';
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
        $rs = $personal->cambiarestadousuario($id, $nuevoestado, $idempresa);
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
        $rs = $personal->resetearclave($id, $idempresa);
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
        $rs = $personal->cambiarclave($clavenueva, $idempresa);
        if ($rs) {
            echo 'Tu clave se cambió correctamente.';
        } else {
            echo 'Tu clave no pudo cambiarse.';
        }
    } catch (Exception $e) {
        echo 'Error inesperado';
    }
}

if ($accion == "generarClave") {
    try {
        $rs = $personal->generarClave();
        echo $rs;
    } catch (Exception $e) {
        echo 'Error inesperado';
    }
}

if ($accion == "cargarDatosPersona") {
    $id = $_GET['id'];
    try {
        $rs = $personal->cargarDatosPersonaC($id);
        if ($rs->rowCount() > 0) {
            foreach ($rs as $row) {
                $jsondata = array(
                    'codigo' => $row['codigo'],
                    'tipo' => $row['tipo'],
                );
            }            
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        } else {
            echo 'Error inesperado';
        }
    } catch (Exception $e) {
        echo 'Error inesperado';
    }
}

if ($accion == "cargarSelectAdministradores") {
    $mensaje = '<span class="input-group-addon">Administrador</span>
                <select name="mainadmin" id="mainadmin" class="form-control input-sm chzn-select">
                <option value="">Selecciona una persona...</option>';
    try {
        $rs = $personal->cargarSelectAdministradores();
        if ($rs->rowCount() > 0) {
            foreach ($rs as $row) {
                if($row['principal'] === "1") {
                    $mensaje .= '<option selected value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                } else {
                    $mensaje .= '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                }
            }
            $mensaje .= '</select><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>';
            echo $mensaje;
        } else {
            echo 'Error inesperado';
        }
    } catch (Exception $e) {
        echo 'Error inesperado';
    }
}