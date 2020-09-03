<?php
include "../modelo/clsEquipoMaterial.php";

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
} 
$accion         = $_GET['accion'];
$equipomaterial = new EquipoMaterial();

if ($accion == "ListaEquipoMaterial") {
    $limite  = $_POST['cboCantidadEquiposMateriales'];
    $cadena  = $_POST['txtFiltroEquiposMateriales'];
    $tipo = '';
    $retorno = '';
    try {
        if(isset($_GET['tipo'])) {
            $tipo = $_GET['tipo'];
            $rs = $equipomaterial->ListaEquiposMaterialesxTipo($cadena, $limite, $tipo, $idempresa);
        } else {
            $rs = $equipomaterial->ListaEquiposMateriales($cadena, $limite, $idempresa);
        }
        
        if ($rs->rowCount() > 0) {
            $i = 1;
            foreach ($rs as $row) {
                if($i == 1) {
                    $primeridserie = $row['id'];
                } else if($i == $rs->rowCount()) {
                    $ultimoidserie = $row['id'];
                }
                $retorno .= '<tr id="' . $row['id'] . '">';
                $retorno .= '<td width="20%">' . $row['codigo'] . '</td>';
                $retorno .= '<td width="60%">' . $row['descripcion'] . '</td>';
                if(isset($_GET['tipo'])) {
                    if($_GET['tipo'] == '1') {
                        $retorno .= '<td width="10%">
                                <div class="row">
                                    <div class="col-sm-6 text-center" style="margin: 0; padding: 0">
                                        <a href="#" class="label label-primary listarDetallesEquipoMaterial" data-id="' . $row['id'] . '" data-codigo="' . $row['codigo'] . '" data-toggle="modal" data-target="#listarDetallesEquipoMaterial"><i class="icon-list"></i></a>
                                    </div>
                                    <div class="col-sm-6 text-center" style="margin: 0; padding: 0; font-weight:bold; color:';
                        if($row['stock'] > 0) {
                            $retorno .= 'green';
                        } else {
                            $retorno .= 'red';
                        }
                        $retorno .= ';">' . round($row['stock'],2) . '</div></div></td>';
                    } else {
                        $retorno .= '<td width="10%">
                                <div class="row stockequipomaterial">
                                    <div class="col-sm-12 text-center mostrarstockequipomaterial" id="mostrarstockequipomaterial' . $row['id'] . '" style="margin: 0; padding: 0; font-weight:bold; color:';
                        if($row['stock'] > 0) {
                            $retorno .= 'green';
                        } else {
                            $retorno .= 'red';
                        }
                        $retorno .= ';" data-id="' . $row['id'] . '" data-stock="' . $row['stock'] . '">' . round($row['stock'],2) . '</div></div><div class="row"><div class="col-sm-12 text-center editarstockequipomaterial" id="editarstockequipomaterial' . $row['id'] . '"></div></div></td>';
                    }                        
                } else {
                    $retorno .= '<td width="10%">' . $row['tipostr'] . '</td>';                    
                    $retorno .= '<td width="10%">
                            <div class="row">
                                <div class="col-sm-6 text-center" style="margin: 0; padding: 0">
                                    <a href="#" class="label label-success modificar" data-opcion="0" data-bean="EquiposMateriales" data-id="' . $row['id'] . '"><i class="icon-edit"></i></a>
                                </div>
                                <div class="col-sm-6 text-center" style="margin: 0; padding: 0">
                                    <a href="#" class="eliminarBean label label-danger" data-clase="EquipoMaterial" data-table="al ' . $row['tipostr'] . '" data-nombre="' . $row['descripcion'] . '" data-id="' . $row['id'] . '" data-toggle="modal" data-target="#deleteModal"><i class="icon-remove"></i></a>
                                </div>
                            </div>
                            </td>';
                }
                $retorno .= '</tr>';
                $i++;
            }            

            $jsondata = array(
                'tabla' => $retorno,
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        } else {
            $jsondata = array(
                'tabla' => "tabla='<tr><td colspan='4'><center>NO HAY EQUIPOS O MATERIALES CON ESTE NOMBRE</center></td></tr>';",
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
        $rs = $equipomaterial->noduplicidad($campo, $palabra, $bean, $idempresa);
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
    $codigo      = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $tipo        = $_POST['tipo'];
    try {
        $rs = $equipomaterial->nuevo($codigo, $descripcion, $tipo, $idempresa);
        if ($rs->rowCount() > 0) {
            echo '<p style="color: green;"><i class="icon-check"></i> Equipo o Material con identificación ' . $codigo . ' registrado Correctamente.</p><p style="color: green;">';
        } else {
            echo '<p style="color: red;"><i class="icon-check"></i> No se pudo Registrar.</p>';
        }
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> No se pudo Registrar.</p>';
    }
}

if ($accion == "modificar") {
    $codigo      = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $tipo        = $_POST['tipo'];
    $id          = $_GET['id'];
    try {
        $rs = $equipomaterial->modificar($id, $codigo, $descripcion, $tipo, $idempresa);
        if ($rs) {
            echo '<p style="color: green;"><i class="icon-check"></i> Equipo o Material con identificación ' . $codigo . ' editado Correctamente.</p><p style="color: green;">';
        }
    } catch (Exception $e) {
        echo '<p style="color: red;"><i class="icon-check"></i> No se pudo Editar.</p>';
    }
}

if ($accion == "eliminar") {
    $id = $_GET['id'];
    try {
        $rs = $equipomaterial->eliminar($id, $idempresa);
        if ($rs) {
            echo '
                <h4 class="titulo modal-title"><b style="color:green;">Equipo o Material Eliminado Correctamente.</b></h4>';
        }
    } catch (Exception $e) {
        echo '<h4 class="titulo modal-title"><b style="color:red;">No se pudo eliminar.</b></h4>';
    }
}

if ($accion == "ListaDetallesEquipoMaterial") {
    $idequipomaterial  = $_GET['idequipomaterial'];
    $retorno = '';
    try {
        $rs = $equipomaterial->ListaDetallesEquipoMaterial($idequipomaterial, $idempresa);
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
            echo "<tr><td colspan='7'><center>ESTE EQUIPO NO TIENE STOCK.</center></td></tr>";
        }
    } catch (Exception $e) {
        echo "<tr colspan='7'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}

if($accion == 'CargarNuevosProductos') {
    $cantidadnuevosproducto = $_POST['cantidadnuevosproducto'];
    for ($i=1; $i <= $cantidadnuevosproducto; $i++) { 
        $codigo = $_POST['codsap' . $i];
        $descripcion = $_POST['descrp' . $i];
        $tipo = $_POST['tip' . $i];
        try {
            $rs = $equipomaterial->nuevo($codigo, $descripcion, $tipo, $idempresa);
        } catch (Exception $e) {
            echo "<b>OCURRIÓ UN ERROR</b>";
        }
    }

    echo '<br><font style="color:blue">Registraste satisfactoriamente los equipos y/o materiales.</font><br>
        <font style="color:blue">Estás listo para importar las guías de remisión.</font><br>
        <font style="color:green">Da click en el botón "IMPORTAR" para hacerlo.</font>';
}

if($accion == 'obtenerMateriales') {
    $rs = $equipomaterial->obtenerMateriales($idempresa, 2);
    $respuesta = '<div class="form-group input-group">                    
            <label class="input-group-addon">Material</label>
            <select class="form-control input-sm chzn-select" name="asmaterial" id="asmaterial">';
    if ($rs->rowCount() > 0) {
        foreach ($rs as $fila) {
            $respuesta .= '<option data-codigo="' . $fila['codigo'] . '" value="MAT' . $fila['id'] . '">' . $fila['descripcion'] . '</option>';
        }
    } else {
        $respuesta .= '<option value="0">No hay Materiales Disponibles.</option>';
    }
    $respuesta .= '</select>
            <span class="input-group-btn">
                <a href="#" class="btn btn-default btn-sm">*</a>
            </span>
        </div>';

    echo $respuesta;
}

if($accion == 'modificarstock') {
    try {
        $id = $_GET['id'];
        $stocknuevo = $_GET['stocknuevo'];
        $equipomaterial->modificarstock($id, $stocknuevo, $idempresa);
    } catch (Exception $e) {
        echo 'NO SE PUDO ACTUALIZAR STOCK';
    }
}

if($accion == 'buscarSerie') {
    try {
        $retorno = '<dl class="dl-horizontal">';
        $serie = $_GET['serie'];
        $rs = $equipomaterial->buscarSerieEquipo($serie, $idempresa);
        if ($rs->rowCount() > 0) {
            foreach ($rs as $row) {
                $estadito = $row['estado'];
                if($row['estado'] == 'A') {
                    $state = '<label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie" checked>
                        <font style="color:blue">EN ALMACÉN</font>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie">
                        <font>ASIGNADO A TÉCNICO</font>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie">
                        <font>SERIE INSTALADA</font>
                    </label>';
                } else if($row['estado'] == 'T') {
                    $state = '<label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie">
                        <font>EN ALMACÉN</font>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie" checked>
                        <font style="color:red">ASIGNADO A TÉCNICO</font>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie">
                        <font>SERIE INSTALADA</font>
                    </label>';
                } else if($row['estado'] == 'I') {
                    $state = '<label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie">
                        <font>EN ALMACÉN</font>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie">
                        <font>ASIGNADO A TÉCNICO</font>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie" checked>
                        <font style="color:green">SERIE INSTALADA</font>
                    </label>';                    
                } else if($row['estado'] == 'D') {
                    $state = '<label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie">
                        <font>EN ALMACÉN</font>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie">
                        <font>ASIGNADO A TÉCNICO</font>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie">
                        <font>SERIE INSTALADA</font>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="rbEstadoSerie" checked>
                        <font style="color:red">SERIE DEVUELTA</font>
                    </label>';
                }
                date_default_timezone_set('America/Lima');
                $fechahoy = date('Y-m-j');
                $fechaenalmacen = date('Y-m-j', strtotime($row['fecha']));
                $fechahoy = new DateTime($fechahoy);
                $fechaenalmacen = new DateTime($fechaenalmacen);
                $intervalo = $fechahoy->diff($fechaenalmacen);
                $retorno .= '<dt>Equipo </dt>
                    <dd>' . $row['descripcion'] . '</dd><br>
                <dt>Código SAP </dt>
                    <dd>' . $row['codigo'] . '</dd><br>
                <dt>Serie </dt>
                    <dd>' . $row['serie'] . '</dd><br>
                <dt>Guía Remisión </dt>
                    <dd>' . $row['numero'] . '</dd><br>
                <dt>Estado </dt>
                    <dd>' . $state . '</dd><br>
                <dt>Tiempo en almacen </dt>
                    <dd>' . $intervalo->format('%a') . ' DÍAS</dd><br>';

                //Buscamos instalacion y sacamos detalles :'3
                    
                $detalleinstalacion = $equipomaterial->detalleinstalacion($row['serie'], $idempresa);
                if ($detalleinstalacion->rowCount() > 0) {
                    foreach ($detalleinstalacion as $row) {
                        $retorno .= '<dt>Orden </dt>
                        <dd>' . $row['orden'] . '</dd><br>';
                         $retorno .= '<dt>Fecha de Instalacion </dt>
                        <dd>' . $row['fecha_liquidacion'] . '</dd><br>';
                        break;
                    }                        
                }
                break;
            }
                
            $rs = $equipomaterial->buscarSerieEquipoAsignado($serie, $idempresa);
            if ($rs->rowCount() > 0) {
                foreach ($rs as $row) {
                    date_default_timezone_set('America/Lima');
                    $fechahoy = date('Y-m-j');
                    $fechaentrega = date('Y-m-j', strtotime($row['fechaentrega']));
                    $fechahoy = new DateTime($fechahoy);
                    $fechaentrega = new DateTime($fechaentrega);
                    $intervalo = $fechahoy->diff($fechaentrega);
                    $retorno .= '<dt>Asignación </dt>
                        <dd>' . $row['numero'] . '</dd><br>
                    <dt>Técnico a Cargo </dt>
                        <dd>' . $row['nombre'] . '</dd><br>';
                    if($estadito != 'I') {
                        $retorno .= '<dt>Tiempo en técnico </dt>
                        <dd>' . $intervalo->format('%a') . ' DÍAS</dd><br>';
                    }
                    break;
                }                    
            }
        } else {
            $retorno .= '<center><h3 style="color:red">No Existe esta serie.</h3></center>';
        }            
        $retorno .= '</dl>';
        echo $retorno;
    } catch (Exception $e) {
        echo 'OCURRIÓ UN ERROR';
    }
}
if ($accion == "obtenerProductos") {
    try {
        $retorno = '';
        $rs = $equipomaterial->obtenerMateriales($idempresa);
        if ($rs) {
            //id codigo descr
            foreach ($rs as $row) {
                $retorno .= '<option value="' . $row['id'] . '">' . $row['codigo'] . ' - ' . $row['descripcion'] . '</option>';
            }
            echo $retorno;
        } else {
            echo 'Error inesperado';
        }
    } catch (Exception $e) {
        echo 'Error inesperado';
    }
}