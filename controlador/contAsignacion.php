<?php
include "../modelo/clsAsignacion.php";

$accion  = $_GET['accion'];
$asignacion = new Asignacion();

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
} 
if ($accion == "ListaAsignacion") {
    $limite  = $_POST['cboCantidadAsignaciones'];
    $cadena  = $_POST['txtFiltroAsignaciones'];
    $fecha   = $_POST['fechaAsignaciones'];
    $retorno = '';
    try {
        $rs = $asignacion->ListaAsignaciones($cadena, $limite, $fecha, $idempresa);        
        if ($rs->rowCount() > 0) {
        	$i = 1;
            foreach ($rs as $row) {
            	if($i == 1) {
            		$primeridserie = $row['id'];
            	}
                $retorno .= '<tr id="' . $row['id'] . '">';
                $retorno .= '<td>' . $row['numero'] . '</td>';
                $retorno .= '<td>' . $row['nombre'] . '</td>';
                $retorno .= '<td>' . $row['fechaentrega'] . '</td>';

                //

                date_default_timezone_set('America/Lima');
                $fechahoy = date('Y-m-j');
                $fechaentrega = date('Y-m-j', strtotime($row['fechaentrega']));
                $fechahoy = new DateTime($fechahoy);
                $fechaentrega = new DateTime($fechaentrega);
                $intervalo = $fechahoy->diff($fechaentrega);
                $retorno .= '<td>' . $intervalo->format('%a') . ' DIAS</td>';

                //
                $retorno .= '<td><a style="cursor: pointer;" onclick="imprimirAsignacion(\'' . $row['nombre'] . '\', \'' . $row['numero'] . '\', \'' . $row['fechaentrega'] . '\', \'' . $row['link'] . '\')">Enlace</a></td>';
                $retorno .= '<td>
                                <div class="row">
                                    <div class="col-sm-12 text-center" style="margin: 0; padding: 0">
                                        <a href="#" class="listarDetallesAsignacion label label-primary" data-id="' . $row['id'] . '" data-numero="' . $row['numero'] . '" data-toggle="modal" data-target="#listarDetallesAsignacion"><i class="icon-list"></i></a>
                                    </div>
                                </div>                            
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-6 text-center" style="margin: 0; padding: 0">
                                        <a href="#" class="label label-success modificar" data-opcion="0" data-bean="Asignaciones" data-id="' . $row['id'] . '"><i class="icon-edit"></i></a>
                                    </div>
                                    <div class="col-sm-6 text-center" style="margin: 0; padding: 0">
                                        <a href="#" class="eliminarBean label label-danger" data-clase="Asignacion" data-table="la ASIGNACIÓN" data-nombre="' . $row['numero'] . '" data-id="' . $row['id'] . '" data-idpersona="' . $row['idpersona'] . '" data-toggle="modal" data-target="#deleteModal"><i class="icon-remove"></i></a>
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
                'tabla' => "tabla='<tr><td colspan='8'><center>NO HAY ASIGNACIONES CON ESTE NOMBRE</center></td></tr>';",
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        }
    } catch (Exception $e) {
        echo "<tr colspan='9'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}

if ($accion == 'obtenerseries') {
    $idequipomaterial = $_GET['idequipomaterial'];
    try {
        $rs = $asignacion->obtenertipoequipomaterial($idequipomaterial, $idempresa);
        foreach ($rs as $row) {
            $tipo = $row['tipo'];
        }

        if($tipo == 1) {
            $rs = $asignacion->obtenerseries($idequipomaterial, $idempresa);
            $result = '<span class="input-group-addon">Serie</span>
                            <select class="chos form-control input-sm" name="serie" id="serie">';
            if ($rs->rowCount() > 0) {                    
                foreach ($rs as $row) {
                    $result .= '<option value="' . $row['id'] . '">' . $row['serie'] . '</option>';
                }
            } else {
                $result .= '<option value="0">No Hay Equipos en Stock.</option>';                
            }
            $result .= '</select><span class="input-group-btn"><button data-bean="serie" class="btn btn-success input-sm" id="adddetalleasignacion" type="button">+</button></span>';
            echo $result;
        } else {
            echo '<span class="input-group-addon">Cantidad</span>
                    <input class="form-control input-sm" type="text" name="cantidad" onkeypress="return filterFloat(event,this);" id="cantidad"><span class="input-group-btn"><button data-bean="equipo" class="btn btn-success input-sm" id="adddetalleasignacion" type="button">+</button></span>';
        }
        
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> No se pudo obtener series.</p>';
    }
}

if ($accion == "nuevoAsignacion") {
    $bloque = $_GET['bloque'];
    $idtecnico = $_POST['tecnico'];
    $numeroasignacion = $_POST['numero'];
    $fechaentrega = $_POST['fechaentrega'];
    $link = substr(str_replace(" ", "_", $_SESSION['nombreempresa']), 0, 18) . '_ASIGNACION_N_' . $numeroasignacion . '.txt';
    try {
        $rs = $asignacion->nuevaAsignacion($numeroasignacion, $idtecnico, $fechaentrega, $link, $idempresa);
        if ($rs->rowCount() > 0) {
            $detalles = explode(';', $bloque);

            for ($i = 0; $i < count($detalles) - 1; $i++) { 
                $det = explode('@', $detalles[$i]);
                $cantidad = $det[0];
                $idequipomaterial = $det[1];
                $asignacion->nuevoDetalle($cantidad, $numeroasignacion, $idequipomaterial, $idempresa);
                $tipo = $idequipomaterial[0];
                $idequipomaterial = substr($idequipomaterial, 1);
                if($tipo == 'E') {

                    //Reducimos el stock de material no seriado

                    $asignacion->reducirStockMaterialNoSeriado($idequipomaterial, $cantidad, $idempresa);
                    $idequipomaterial2 = $idequipomaterial;
                } else {

                    //Cambiamos de estado al equipo seriado y reducimos el stock del equipomaterial general
                    
                    $asignacion->cambiarEstadoEquipoSeriado($idequipomaterial);
                    $asignacion->reducirStockEquipoSeriado($idequipomaterial, $cantidad, $idempresa);
                    $idequipomaterial2 = $asignacion->obtenerIdEquipoConSerie($idequipomaterial);
                }
                $asignacion->agregarCantidadDetalleResAsignacion($idequipomaterial2, $cantidad, $idtecnico);
                $det = '';
            }
            echo '<p style="color: green;"><i class="icon-check"></i> Asignación ' . $numeroasignacion . ' registrada Correctamente.</p><p style="color: green;">';
        } else {
            echo '<p style="color: red;"><i class="icon-check"></i> No se pudo Registrar.</p>';
        }
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> No se pudo Registrar.</p>';
    }
}

if ($accion == 'obtenerequiposseriados') {
    try {
        $equipos = $asignacion->obtenerequiposseriados($idempresa);
        $resultado = '<select name="equipo" id="equipo" class="chos form-control input-sm chzn-select">';
        if($equipos->rowCount() != 0) {
            foreach ($equipos as $equipo) {
                $resultado .= '<option id="O' . $equipo['id'] . '" data-cantidad="' . $equipo['stock'] . '" value="' . $equipo['id'] . '">' . $equipo['descripcion'] . '</option>';
            }
        } else {
            $resultado .= '<option value="0">No hay Equipos/Materiales disponibles</option>';
        }
        $resultado .= '</select>';
        echo $resultado;           
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> Error.</p>';
    }
}

if ($accion == 'obtenerDetallesAsignacion') {
    try {
        $detalles = $asignacion->obtenerDetallesAsignacion($_GET['id'], $idempresa);
        $resultado = '';
        if($detalles->rowCount() != 0) {
            foreach ($detalles as $detalle) {
                $idelemento = substr($detalle['idequipomaterial'], 1);
                $tipo = substr($detalle['idequipomaterial'], 0, 1);
                $elemento = $asignacion->obtenerDescripcionDetalle($idelemento, $tipo, $idempresa);
                foreach ($elemento as $el) {
                    $serie = $el['serie'];
                    $descripcion = $el['descripcion'];
                    break;
                }
                $resultado .= '<tr id="' . $detalle['idequipomaterial'] . '" data-id="' . $detalle['idequipomaterial'] . '">
                                    <td>' . $detalle['cantidad'] . '</td>
                                    <td>' . $serie . '</td>
                                    <td>' . $descripcion . '</td>
                                    <td>
                                        <a class="label label-danger removeProductoAsignacion" href="javascript:void(0)">X</a>
                                    </td>
                                </tr>';
            }
            echo $resultado; 
        }                   
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> Error.</p>';
    }
}

if ($accion == "modificarAsignacion") {
    $bloque = $_GET['bloque'];
    $idtecnico = $_POST['tecnico'];
    $numeroasignacion = $_POST['numero'];
    $fechaentrega = $_POST['fechaentrega'];
    $idasignacion = $_GET['id'];
    try {
        //Comprobamos si el técnico anterior es igual al que se quiere editar 
        
        //Salvamos idasignacion anterior
        
        $idtecnicoasignacionanterior = $asignacion->salvarIdTecnicoAsignacionAnterior($idasignacion, $idempresa);
        
        //Comparamos con el que se quiere editar
        
        if($idtecnico != $idtecnicoasignacionanterior) {
            
            //Si no es el mismo reducimos el stock de los resumenes de asignacion del anterior y luego pasamos al proceso
            
            $cantidades = $asignacion->salvarCantidadesAnteriores($idasignacion, $idempresa);
            
            foreach ($cantidades as $cantidad) {
                $asignacion->modificarDetallesSinTocar($idtecnicoasignacionanterior, $cantidad['idequipomaterial'], $cantidad['cantidad']);
            }
        }
        
        //Si es el mismo pasamos a hacer esto directo

        $asignacion->eliminarDetallesAumentarStockCambiarEstadoSeries($idasignacion, $idempresa);
        $asignacion->modificarAsignacion($idasignacion, $idtecnico, $idempresa);
        $detalles = explode(';', $bloque);
        for ($i = 0; $i < count($detalles) - 1; $i++) { 
            $det = explode('@', $detalles[$i]);
            $cantidad = $det[0];
            $idequipomaterial = $det[1]; 
            $tipo = $idequipomaterial[0];

            if($tipo == 'E') {
                $idequipomaterial2 = substr($idequipomaterial, 1);
            } else {
                $idequipomaterial2 = $asignacion->obtenerIdEquipoConSerie(substr($idequipomaterial, 1));
            }
            if(isset($cantidadessalvadas['' . $idequipomaterial])) {
                $cnt = $cantidadessalvadas['' . $idequipomaterial];
                unset($cantidadessalvadas['' . $idequipomaterial]);
            } else {
                $cnt = 0;
            }
            $asignacion->modificarCantidadDetalleResAsignacion($cnt, $idequipomaterial2, $cantidad, $idtecnico, $idasignacion);   

            $asignacion->nuevoDetalle($cantidad, $numeroasignacion, $idequipomaterial, $idempresa);
            
            $idequipomaterial = substr($idequipomaterial, 1);            
            if($tipo == 'E') {
                //Reducimos el stock de material no seriado
                $asignacion->reducirStockMaterialNoSeriado($idequipomaterial, $cantidad, $idempresa);
            } else {
                //Cambiamos de estado al equipo seriado y reducimos el stock del equipomaterial general
                $asignacion->cambiarEstadoEquipoSeriado($idequipomaterial);
                $asignacion->reducirStockEquipoSeriado($idequipomaterial, $cantidad, $idempresa);
            }            
            $det = '';
        }
        if(count($cantidadessalvadas) != 0) {
            while ($b = current($cantidadessalvadas)) {          
                $asignacion->modificarDetallesSinTocar($idtecnico, key($cantidadessalvadas), $cantidadessalvadas[key($cantidadessalvadas)]);
                next($cantidadessalvadas);
            }
        }
        echo '<p style="color: green;"><i class="icon-check"></i> Asignación ' . $numeroasignacion . ' editada Correctamente.</p><p style="color: green;">';
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> No se pudo Editar.</p>';
    }
}

if ($accion == "ListaDetallesAsignacion") {
    $idasignacion  = $_GET['idasignacion'];
    $retorno = '';
    try {
        $rs = $asignacion->ListaDetallesAsignacion($idasignacion, $idempresa);
        if ($rs->rowCount() > 0) {
            $i = 1;
            foreach ($rs as $row) {
                $retorno .= '<tr><td>' . $i . '</td>';
                $retorno .= '<td>' . $row['cantidad'] . '</td>';
                $tipo = substr($row['numero'], 0, 1);
                $idequipomaterial = substr($row['numero'], 1);
                $materiales = $asignacion->cargarDetallesEquipoMaterial($idequipomaterial, $tipo, $idempresa);
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
                    $retorno .= '<td style="color:' . $color . '">T</td>';
                    break;
                }
                $i++;
            }
            echo $retorno;
        } else {
            echo "<tr><td colspan='7'><center>ESTA ASIGNACIÓN NO TIENE MATERIALES.</center></td></tr>";
        }
    } catch (Exception $e) {
        echo "<tr colspan='7'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}

if ($accion == "eliminar") {
    $idasignacion = $_GET['id'];
    $idpersona = $_GET['idpersona'];
    try {
        $cantidades = $asignacion->salvarCantidadesAnteriores($idasignacion, $idempresa);
        $cantidadessalvadas = array();

        foreach ($cantidades as $cantidad) {
            $cantidadessalvadas[$cantidad['idequipomaterial']] =  $cantidad['cantidad'];
        }

        while ($b = current($cantidadessalvadas)) {          
            $asignacion->modificarDetallesSinTocar($idpersona, key($cantidadessalvadas), $cantidadessalvadas[key($cantidadessalvadas)]);
            next($cantidadessalvadas);
        }

        $asignacion->eliminarDetallesAumentarStockCambiarEstadoSeries($idasignacion, $idempresa);
        $asignacion->eliminarAsignacion($idasignacion, $idempresa);

        foreach ($cantidades as $cantidad) {
            $asignacion->modificarDetallesSinTocar($idpersona, $cantidad['idequipomaterial'], $cantidad['cantidad']);
        }

        $asignacion->eliminarDetallesAumentarStockCambiarEstadoSeries($idasignacion, $idempresa);
        $del = $asignacion->eliminarAsignacion($idasignacion, $idempresa); 

        foreach ($del as $num) {
            $numeroasig = $num['numero'];
        }

        $arch = '../ticket/' . substr(str_replace(" ", "_", $_SESSION['nombreempresa']), 0, 18) . '_ASIGNACION_N_' . $numeroasig . '.txt';

        fopen($arch, 'a');

        if(file_exists($arch)) {
            unlink($arch);
        } 

        echo '<h4 class="titulo modal-title"><b style="color:green;">Asignación Eliminada Correctamente.</b></h4>';
    } catch (Exception $e) {
        echo '<h4 class="titulo modal-title"><b style="color:red;">No se pudo eliminar.</b></h4>';
    }
}

if($accion == "obtenerDetalleEquipoSeriado") {
    $serie = $_GET['serie'];
    try {
        $detalle = $asignacion->obtenerDetalleEquipoSeriado($serie, $idempresa);
        $mensaje = '0';
        $tabla = '';
        $idequipomaterial = '';
        if ($detalle->rowCount() > 0) {
            foreach ($detalle as $row) {
                $tabla = '<tr id="' . $row['idequipomaterial'] . '" data-id="' . $row['idequipomaterial'] . '"><td>1</td><td>' . $row['serie'] . '</td><td>' . $row['descripcion'] . '</td><td><a class="label label-danger removeProductoAsignacion" href="javascript:void(0)">X</a></td></tr>';
                $mensaje = 'Producto Agregado Correctamente.';
                $idequipomaterial = $row['idequipomaterial'];
                break;
            }
        }
        $jsondata = array(
            'tabla' => $tabla,
            'mensaje' => $mensaje,
            'idequipomaterial' => $idequipomaterial,
        );
        echo json_encode($jsondata, JSON_FORCE_OBJECT);    
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> Error.</p>';
    }
}