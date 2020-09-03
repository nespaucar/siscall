<?php

include_once "../cado/cado.php";

class Asignacion extends Cado
{
    public function ListaAsignaciones($cadena, $limite, $fecha, $idempresa)
    {
        $like = " LIKE '%$cadena%' ";
        $sql  = "SELECT numero, asignacion.id, fechaentrega, CONCAT(nombres, ' ', apellidos) AS nombre, persona.id as idpersona, link FROM asignacion
        	INNER JOIN persona ON persona.id = asignacion.idtecnico
            WHERE (numero $like OR CONCAT(nombres, ' ', apellidos) $like)
            AND fechaentrega = '$fecha'
            AND asignacion.idempresa = $idempresa
            ORDER BY fechaentrega DESC
            LIMIT $limite";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cargardatosasignacion($id, $idempresa)
    {
        $sql       = "SELECT id, idtecnico, fechaentrega, numero FROM asignacion WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cargardatoslastasignacion($idempresa)
    {
        $sql       = "SELECT (id + 1), idtecnico FROM asignacion WHERE idempresa = $idempresa ORDER BY id ASC";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenertecnicos($idempresa) {
        $sql       = "SELECT persona.id, CONCAT(nombres, ' ', apellidos) AS nombre FROM persona 
        INNER JOIN usuario ON usuario.idpersona = persona.id 
        WHERE usuario.tipo = 2
        AND usuario.idempresa = $idempresa
        ORDER BY id ASC";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerequiposseriados($idempresa) {
        $sql = "SELECT id, stock, descripcion FROM equipomaterial WHERE idempresa = $idempresa GROUP BY id ORDER BY descripcion";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerseries($idequipomaterial, $idempresa) {
        $sql       = "SELECT guiaremisionequipomaterial.id, serie FROM guiaremisionequipomaterial 
        INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision
        WHERE idequipomaterial = $idequipomaterial
        AND guiaremisionequipomaterial.estado = 'A'
        AND guiaremision.idempresa = $idempresa
        ORDER BY id ASC";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenertipoequipomaterial($idequipomaterial, $idempresa) {
        $sql       = "SELECT tipo FROM equipomaterial 
        WHERE id = $idequipomaterial AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function nuevaAsignacion($numeroasignacion, $idtecnico, $fechaentrega, $link, $idempresa) {
        $sql       = "INSERT INTO asignacion(numero, idtecnico, fechaentrega, link, idempresa) VALUES('$numeroasignacion', $idtecnico, '$fechaentrega', '$link', $idempresa)";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function nuevoDetalle($cantidad, $numeroasignacion, $idequipomaterial, $idempresa) {
        $sql       = "INSERT INTO asignacionequipomaterial(cantidad, idasignacion, idequipomaterial) VALUES(" . $cantidad . ", (SELECT id FROM asignacion WHERE numero = '" . $numeroasignacion . "' AND idempresa = " . $idempresa . " LIMIT 1), '" . $idequipomaterial . "')";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function reducirStockMaterialNoSeriado($idequipomaterial, $cantidad, $idempresa) {
        $sql = 'UPDATE equipomaterial SET stock = (CASE WHEN (stock - ' . $cantidad . ') >= 0 THEN (stock - ' . $cantidad . ') ELSE stock END) WHERE id = ' . $idequipomaterial . ' AND idempresa = ' . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cambiarEstadoEquipoSeriado($idequipomaterial) {
        $sql = 'UPDATE guiaremisionequipomaterial SET estado = "T" WHERE id = ' . $idequipomaterial;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function reducirStockEquipoSeriado($idequipomaterial, $cantidad, $idempresa) {
        $sql0 = 'SELECT equipomaterial.id FROM guiaremisionequipomaterial 
                INNER JOIN equipomaterial ON equipomaterial.id = guiaremisionequipomaterial.idequipomaterial 
                WHERE guiaremisionequipomaterial.id = ' . $idequipomaterial . ' 
                AND equipomaterial.idempresa = ' . $idempresa;
        $resultado0 = Cado::ejecutarConsulta($sql0);
        foreach ($resultado0 as $row) {
            $idequipomaterial2 = $row['id'];
        }
        $sql = 'UPDATE equipomaterial SET stock = (stock - 1) WHERE id = ' . $idequipomaterial2 . ' AND idempresa = ' . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerDetallesAsignacion($id, $idempresa) {
        $sql = 'SELECT idequipomaterial, cantidad FROM asignacionequipomaterial 
        INNER JOIN asignacion ON asignacion.id = asignacionequipomaterial.idasignacion
        WHERE asignacion.id = ' . $id . '
        AND asignacion.idempresa =  ' . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerDescripcionDetalle($id, $tipo, $idempresa) {
        if($tipo == 'S') {
            $sql = 'SELECT g.serie, e.descripcion FROM guiaremisionequipomaterial g 
            INNER JOIN equipomaterial e ON g.idequipomaterial = e.id  
            WHERE g.id= ' . $id . '
            AND e.idempresa = ' . $idempresa;
        } else {
            $sql = 'SELECT "-" AS serie, descripcion FROM equipomaterial WHERE id = ' . $id . ' AND idempresa = ' . $idempresa;
        }
        
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function eliminarDetallesAumentarStockCambiarEstadoSeries($id, $idempresa) {
        $sql = 'SELECT asignacionequipomaterial.id, asignacionequipomaterial.idequipomaterial, cantidad FROM asignacionequipomaterial 
        INNER JOIN asignacion ON asignacion.id = asignacionequipomaterial.idasignacion
        WHERE idasignacion = ' . $id . '
        AND asignacion.idempresa = ' . $idempresa;
        $rs = Cado::ejecutarConsulta($sql);
        foreach ($rs as $row) {
            $idelemento = substr($row['idequipomaterial'], 1);
            $tipo = $row['idequipomaterial']['0'];
            $cantidad = $row['cantidad'];
            $idequipomaterial = $idelemento;
            if($tipo == 'S') {
                $idequipomaterial = '(SELECT idequipomaterial FROM guiaremisionequipomaterial WHERE id = ' . $idelemento . ')';
                $sql = 'UPDATE guiaremisionequipomaterial SET estado = "A" WHERE id = ' . $idelemento;
                Cado::ejecutarConsulta($sql);
            }
            $sql = 'UPDATE equipomaterial SET stock = (stock + ' . $cantidad . ') WHERE id = ' . $idequipomaterial . ' AND idempresa = ' . $idempresa;
            Cado::ejecutarConsulta($sql);
            $sql = 'DELETE FROM asignacionequipomaterial WHERE id = ' . $row['id'];
            Cado::ejecutarConsulta($sql);
        }        
    }

    public function modificarAsignacion($idasignacion, $idtecnico, $idempresa) {
        $sql = 'UPDATE asignacion SET idtecnico = ' . $idtecnico . ' WHERE id = ' . $idasignacion . ' AND idempresa = ' . $idempresa;
        $rs = Cado::ejecutarConsulta($sql);
        return $rs;
    }

    public function eliminarAsignacion($idasignacion, $idempresa)
    {
        $sql0 = "SELECT numero FROM asignacion WHERE id=$idasignacion AND idempresa = $idempresa";
        $resultado0 = Cado::ejecutarConsulta($sql0);
        $sql       = "DELETE FROM asignacion WHERE id=$idasignacion AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado0;
    }

    public function ListaDetallesAsignacion($idasignacion, $idempresa)
    {
        $sql  = "SELECT idequipomaterial as numero, cantidad FROM asignacionequipomaterial 
            INNER JOIN asignacion
            ON asignacionequipomaterial.idasignacion = asignacion.id
            WHERE asignacion.id = $idasignacion
            AND asignacion.idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cargarDetallesEquipoMaterial($idequipomaterial, $tipo, $idempresa) {
        if($tipo == 'S') {
            $sql = 'SELECT equipomaterial.codigo, equipomaterial.descripcion, (CASE WHEN equipomaterial.tipo = 1 THEN "EQUIPO" WHEN equipomaterial.tipo = 2 THEN "MATERIAL" END) AS tipostr,
            guiaremisionequipomaterial.serie, guiaremisionequipomaterial.estado
            FROM guiaremisionequipomaterial
            LEFT JOIN guiaremision
            ON guiaremisionequipomaterial.idguiaremision = guiaremision.id
            LEFT JOIN equipomaterial
            ON equipomaterial.id = guiaremisionequipomaterial.idequipomaterial
            WHERE guiaremisionequipomaterial.id=' . $idequipomaterial .' 
            AND guiaremision.idempresa = ' . $idempresa . '
            AND equipomaterial.idempresa = ' . $idempresa;
        } else {
            $sql = 'SELECT equipomaterial.codigo, equipomaterial.descripcion, (CASE WHEN equipomaterial.tipo = 1 THEN "EQUIPO" WHEN equipomaterial.tipo = 2 THEN "MATERIAL" END) AS tipostr,
            "" AS serie, "" AS estado
            FROM equipomaterial
            WHERE equipomaterial.id=' . $idequipomaterial . '
            AND equipomaterial.idempresa = ' . $idempresa;
        }  
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerDetalleEquipoSeriado($serie, $idempresa) {
        $sql = 'SELECT CONCAT("S", guiaremisionequipomaterial.id) AS idequipomaterial, guiaremisionequipomaterial.serie, descripcion FROM guiaremisionequipomaterial
            INNER JOIN equipomaterial ON guiaremisionequipomaterial.idequipomaterial = equipomaterial.id
            WHERE guiaremisionequipomaterial.serie LIKE "%' . $serie . '%"
            AND guiaremisionequipomaterial.estado = "A"
            AND equipomaterial.idempresa = ' . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerIdEquipoConSerie($idguiaremisionequipomaterial) {
        $sql = "SELECT idequipomaterial FROM guiaremisionequipomaterial WHERE id=" . $idguiaremisionequipomaterial;
        $resultado = Cado::ejecutarConsulta($sql);
        foreach ($resultado as $row) {
            $id = $row['idequipomaterial'];
            break;
        }
        return $id;
    }

    public function agregarCantidadDetalleResAsignacion($idequipomaterial, $cantidad, $idtecnico) {
        $sql = 'SELECT id FROM resasignacion WHERE idtecnico = ' . $idtecnico;
        $resultado = Cado::ejecutarConsulta($sql);
        if($resultado->rowCount() == 0) {
            $sql = 'INSERT INTO resasignacion(idtecnico) VALUES(' . $idtecnico . ')';
            $resultado = Cado::ejecutarConsulta($sql);
        }
        $sql = 'SELECT resasignacionequipomaterial.id FROM resasignacion 
            INNER JOIN resasignacionequipomaterial ON resasignacionequipomaterial.idresasignacion = resasignacion.id 
            WHERE idtecnico='. $idtecnico . ' 
            AND idequipomaterial=' . $idequipomaterial;
        $resultado = Cado::ejecutarConsulta($sql);
        if($resultado->rowCount() == 0) {
            $sql = "INSERT INTO resasignacionequipomaterial(idresasignacion, idequipomaterial, cantidad) VALUES((SELECT id FROM resasignacion WHERE idtecnico = " . $idtecnico . "), " . $idequipomaterial . ", " . $cantidad . ")";            
        } else {
            $sql = "UPDATE resasignacionequipomaterial SET cantidad = (cantidad + " . $cantidad . ") WHERE idresasignacion = (SELECT id FROM resasignacion WHERE idtecnico = " . $idtecnico. ") AND idequipomaterial = " . $idequipomaterial;
        }

        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function modificarCantidadDetalleResAsignacion($cnt, $idequipomaterial, $cantidad, $idtecnico, $idasignacion) {
        $sql = 'SELECT resasignacionequipomaterial.id FROM resasignacion INNER JOIN resasignacionequipomaterial ON resasignacionequipomaterial.idresasignacion = resasignacion.id WHERE idtecnico='. $idtecnico . ' AND idequipomaterial=' . $idequipomaterial;
        $resultado = Cado::ejecutarConsulta($sql);
        if($resultado->rowCount() != 0) {
            $sql = "UPDATE resasignacionequipomaterial SET cantidad = (cantidad - " . $cnt . " + " . $cantidad . ") WHERE idresasignacion = (SELECT id FROM resasignacion WHERE idtecnico = " . $idtecnico. ") AND idequipomaterial = " . $idequipomaterial;
            Cado::ejecutarConsulta($sql);
        } else {
            $sql = "INSERT INTO resasignacionequipomaterial(idresasignacion, idequipomaterial, cantidad) VALUES((SELECT id FROM resasignacion WHERE idtecnico = " . $idtecnico . "), " . $idequipomaterial . ", " . $cantidad . ")";
            Cado::ejecutarConsulta($sql);
        }
    }  

    public function salvarCantidadesAnteriores($idasignacion, $idempresa) {
        $sql = 'SELECT idequipomaterial, cantidad FROM asignacionequipomaterial 
        INNER JOIN asignacion ON asignacion.id = asignacionequipomaterial.idasignacion
        WHERE idasignacion=' . $idasignacion . '
        AND asignacion.idempresa = ' . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }  

    public function modificarDetallesSinTocar($idtecnico, $idequipomaterial, $cantidad) {
        if (substr($idequipomaterial, 0, 1) == 'S') {
            $idequipomaterial = $this->obtenerIdEquipoConSerie(substr($idequipomaterial, 1));
        } else {
            $idequipomaterial = substr($idequipomaterial, 1);
        }

        $sql = 'SELECT resasignacionequipomaterial.id FROM resasignacion 
            INNER JOIN resasignacionequipomaterial ON resasignacionequipomaterial.idresasignacion = resasignacion.id 
            WHERE idtecnico='. $idtecnico . ' 
            AND idequipomaterial=' . $idequipomaterial;

        $resultado = Cado::ejecutarConsulta($sql);

        $sql0 = "SELECT id FROM resasignacion WHERE idtecnico = " . $idtecnico;
        $resultado0 = Cado::ejecutarConsulta($sql0);

        if($resultado0->rowCount() == 0) {
            $sql0 = "INSERT INTO resasignacion(idtecnico) VALUES(" . $idtecnico . ')';
            $resultado0 = Cado::ejecutarConsulta($sql0);
        }

        if($resultado->rowCount() == 0) {
            $sql = "INSERT INTO resasignacionequipomaterial(idresasignacion, idequipomaterial, cantidad) VALUES((SELECT id FROM resasignacion WHERE idtecnico = " . $idtecnico . "), " . $idequipomaterial . ", " . $cantidad . ")";            
        } else {
            $sql = 'UPDATE resasignacionequipomaterial SET cantidad = (CASE WHEN cantidad >= ' . $cantidad . ' THEN (cantidad - ' . $cantidad . ') ELSE cantidad END) WHERE idresasignacion = (SELECT id FROM resasignacion WHERE idtecnico = ' . $idtecnico . ') AND idequipomaterial = ' . $idequipomaterial;
        }
        $resultado = Cado::ejecutarConsulta($sql);
          
        Cado::ejecutarConsulta($sql);
    }

    public function modificarDetallesSinTocar2($idtecnico, $idequipomaterial, $cantidad) {
        if (substr($idequipomaterial, 0, 1) == 'S') {
            $idequipomaterial = $this->obtenerIdEquipoConSerie(substr($idequipomaterial, 1));
        } else {
            $idequipomaterial = substr($idequipomaterial, 1);
        }
        $sql = 'UPDATE resasignacionequipomaterial SET cantidad = (CASE WHEN cantidad >= ' . $cantidad . ') THEN (cantidad - ' . $cantidad . ') ELSE cantidad END) WHERE idresasignacion = (SELECT id FROM resasignacion WHERE idtecnico = ' . $idtecnico . ') AND idequipomaterial = ' . $idequipomaterial;  
        return $sql;
    }

    public function salvarIdTecnicoAsignacionAnterior($idasignacion, $idempresa) {
        $sql = 'SELECT idtecnico FROM asignacion
        WHERE id=' . $idasignacion . '
        AND idempresa = ' . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        foreach ($resultado as $row) {
            return $row['idtecnico'];
        }        
    }

    public function obtenerIdAsignacion($numero, $idempresa) {
        $sql = 'SELECT id FROM asignacion
        WHERE numero="' . $numero . '"
        AND idempresa = ' . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        foreach ($resultado as $row) {
            return $row['id'];
        }        
    }
}