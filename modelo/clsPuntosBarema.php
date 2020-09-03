<?php

include_once "../cado/cado.php";

class PuntosBarema extends Cado
{

    public function nuevo($nombre, $tabla, $idempresa, $idprefijo = '', $idactividad = '')
    {
    	if($tabla == 'baremo') {
    		$sql  = "INSERT INTO baremo(idprefijo, idactividad, puntaje, idempresa) VALUES($idprefijo, $idactividad, $nombre, $idempresa)";
    	} else {
    		$sql  = "INSERT INTO $tabla(nombre, idempresa) VALUES('$nombre', $idempresa)";
    	}        
        $resultado = Cado::ejecutarConsulta($sql);
        $sql  = "SELECT MAX(id) AS id FROM $tabla WHERE idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function noduplicidad($campo, $palabra, $bean, $idempresa)
    {
        $sql = "SELECT id FROM $bean WHERE $campo='$palabra'";
        if ($campo == 'pass') {
            session_start();
            $sql .= " AND id=" . $_SESSION['id'];
        } 
        if ($bean == 'actividad' || $bean == 'asignacion' || $bean == 'baremo' || $bean == 'devolucion' || $bean == 'equipomaterial' || $bean == 'guiaremision' || $bean == 'actividad' || $bean == 'instalacion' || $bean == 'prefijo' || $bean == 'usuario') {
            $sql .= " AND idempresa = " . $idempresa;
        }
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado->rowCount();
    }

    public function noduplicidad2($idprefijo, $idactividad, $idempresa)
    {
        $sql = "SELECT * FROM baremo WHERE idprefijo = " . $idprefijo . " AND idactividad = " . $idactividad . " AND idempresa = " . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado->rowCount();
    }

    public function lista($tabla, $idempresa)
    {
    	if($tabla != 'baremo') {
    		$sql  = "SELECT * FROM " . $tabla . " WHERE idempresa = " . $idempresa;
    	} else {
    		$sql  = "SELECT baremo.id, prefijo.nombre AS prefijo, actividad.nombre AS actividad, puntaje
    		FROM baremo
    		INNER JOIN prefijo ON prefijo.id = baremo.idprefijo
    		INNER JOIN actividad ON actividad.id = baremo.idactividad
            WHERE baremo.idempresa = " . $idempresa . "
            AND actividad.idempresa = " . $idempresa . "
            AND prefijo.idempresa = " . $idempresa;
    	}        
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function eliminar($id, $tabla, $idempresa)
    {
        $sql       = "DELETE FROM " . $tabla . " WHERE id=" . $id . " AND idempresa = " . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function comprobarexistenciabaremo($id, $tabla, $idempresa)
    {
        $sql = "SELECT * FROM baremo WHERE id" . $tabla . " = " . $id . " AND idempresa = " . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado->rowCount();
    }
}
