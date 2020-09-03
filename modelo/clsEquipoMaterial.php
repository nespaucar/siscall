<?php

include_once "../cado/cado.php";

class EquipoMaterial extends Cado
{

    public function ListaEquiposMateriales($cadena, $limite, $idempresa)
    {
        $like = " LIKE '%$cadena%' ";
        $sql  = "SELECT id, codigo, descripcion, (CASE WHEN tipo = 1 THEN 'EQUIPO' WHEN tipo = 2 THEN 'MATERIAL' END) AS tipostr FROM equipomaterial
            WHERE (codigo $like OR descripcion $like OR (CASE WHEN tipo = 1 THEN 'EQUIPO' WHEN tipo = 2 THEN 'MATERIAL' END) $like)
            AND equipomaterial.idempresa = $idempresa LIMIT $limite";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaEquiposMaterialesxTipo($cadena, $limite, $tipo, $idempresa)
    {
        $like = " LIKE '%$cadena%' ";
        $sql  = "SELECT id, codigo, descripcion, stock, (CASE WHEN tipo = 1 THEN 'EQUIPO' WHEN tipo = 2 THEN 'MATERIAL' END) AS tipostr
            FROM equipomaterial
            WHERE (codigo $like OR descripcion $like OR (CASE WHEN tipo = 1 THEN 'EQUIPO' WHEN tipo = 2 THEN 'MATERIAL' END) $like)";
        if($tipo != '') {
            $sql .= " AND tipo = " . $tipo;
        }
        $sql .= " AND idempresa = " . $idempresa . " ORDER BY stock ASC LIMIT $limite";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }    

    public function noduplicidad($campo, $palabra, $bean, $idempresa)
    {
        $sql = "SELECT id FROM $bean WHERE $campo='$palabra' ";
        if ($campo == 'pass') {
            session_start();
            $sql .= ' AND id=' . $_SESSION['id'];
        } 
        if ($bean == 'actividad' || $bean == 'asignacion' || $bean == 'baremo' || $bean == 'devolucion' || $bean == 'equipomaterial' || $bean == 'guiaremision' || $bean == 'actividad' || $bean == 'instalacion' || $bean == 'prefijo' || $bean == 'usuario') {
            $sql .= " AND idempresa = " . $idempresa;
        }
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function nuevo($codigo, $descripcion, $tipo, $idempresa)
    {
        $sql       = "INSERT INTO equipomaterial(codigo, descripcion, tipo, idempresa) VALUES('$codigo', '" . addslashes($descripcion) . "', $tipo, $idempresa)";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cargardatosequipomaterial($id, $idempresa)
    {
        $sql       = "SELECT * FROM equipomaterial WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function modificar($id, $codigo, $descripcion, $tipo, $idempresa)
    {
        $sql       = "UPDATE equipomaterial SET codigo='$codigo', descripcion='" . addslashes($descripcion) . "', tipo=$tipo WHERE id=$id AND idempresa=$idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function eliminar($id, $idempresa)
    {
        $sql       = "DELETE FROM equipomaterial WHERE id=$id AND idempresa=$idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaDetallesEquipoMaterial($idequipomaterial, $idempresa) {
        $sql  = "SELECT cantidad, equipomaterial.codigo, descripcion, (CASE WHEN tipo = 1 THEN 'EQU' WHEN tipo = 2 THEN 'MAT' END) AS tipostr, serie, guiaremisionequipomaterial.estado FROM guiaremisionequipomaterial 
            INNER JOIN guiaremision
            ON guiaremisionequipomaterial.idguiaremision = guiaremision.id
            INNER JOIN equipomaterial
            ON equipomaterial.id = guiaremisionequipomaterial.idequipomaterial
            WHERE equipomaterial.id = $idequipomaterial
            AND equipomaterial.idempresa = $idempresa
            AND guiaremision.idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerMateriales($idempresa, $tipo = '')
    {
        $sql  = "SELECT id, codigo, descripcion FROM equipomaterial WHERE tipo LIKE '%$tipo%' AND idempresa = $idempresa ORDER BY id";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function modificarstock($id, $stocknuevo, $idempresa) {
        $sql       = "UPDATE equipomaterial SET stock=$stocknuevo WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function buscarSerieEquipo($serie, $idempresa) {
        $sql = 'SELECT codigo, descripcion, fecha, numero, serie, guiaremisionequipomaterial.estado
        FROM guiaremisionequipomaterial
        INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision
        INNER JOIN equipomaterial ON equipomaterial.id = guiaremisionequipomaterial.idequipomaterial
        WHERE serie LIKE "%' . $serie . '%" 
        AND equipomaterial.idempresa = ' . $idempresa . ' 
        AND guiaremision.idempresa = ' . $idempresa . ' 
        LIMIT 1';
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function buscarSerieEquipoAsignado($serie, $idempresa) {
        $sql = 'SELECT asignacion.numero, CONCAT(nombres, " ", apellidos) AS nombre, fechaentrega 
        FROM asignacionequipomaterial
        INNER JOIN asignacion ON asignacion.id = asignacionequipomaterial.idasignacion
        INNER JOIN persona ON persona.id = asignacion.idtecnico
        WHERE SUBSTRING(asignacionequipomaterial.idequipomaterial, 1, 1) = "S"
        AND (SELECT guiaremisionequipomaterial.id FROM guiaremisionequipomaterial INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE serie LIKE "%' . $serie . '%" AND guiaremision.idempresa = ' . $idempresa . ' LIMIT 1) = (SUBSTRING(asignacionequipomaterial.idequipomaterial, 2)) 
        AND asignacion.idempresa = ' . $idempresa . ' LIMIT 1';
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function detalleinstalacion($serie, $idempresa) {
        $sql = 'SELECT orden, fecha_liquidacion 
        FROM instalacion 
        INNER JOIN instalacionequipomaterial ON instalacion.id = instalacionequipomaterial.idinstalacion
        WHERE instalacionequipomaterial.idequipomaterial = CONCAT("S", 
        (SELECT guiaremisionequipomaterial.id FROM guiaremisionequipomaterial INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE serie LIKE "' . $serie . '" AND guiaremision.idempresa = ' . $idempresa . ' LIMIT 1)) 
        AND instalacion.idempresa = ' . $idempresa . ' LIMIT 1';
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }
}
