<?php

include_once "../cado/cado.php";

class Instalacion extends Cado
{

    public function ListaInstalaciones($cadena, $limite,$fecha, $idempresa)
    {
        $like = " LIKE '%$cadena%' ";
        $sql  = "SELECT orden, observacion, estado, instalacion.id, fecha_liquidacion, CONCAT(nombres, ' ', apellidos) AS nombre, actividad, prefijo, idtecnico2, idtecnico3 
            FROM instalacion
        	INNER JOIN persona ON persona.id = instalacion.idtecnico
            WHERE (orden $like OR estado $like OR observacion $like OR CONCAT(nombres, ' ', apellidos) $like)
            AND fecha_liquidacion = '$fecha'
            AND instalacion.idempresa = $idempresa
            ORDER BY fecha_liquidacion DESC
            LIMIT $limite";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaDetallesInstalacion($idinstalacion, $idempresa)
    {
        $sql  = "SELECT idequipomaterial as numero, cantidad 
            FROM instalacionequipomaterial 
            INNER JOIN instalacion
            ON instalacionequipomaterial.idinstalacion = instalacion.id
            WHERE instalacion.id = $idinstalacion
            AND instalacion.idempresa = $idempresa";
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

    public function NombreTecnico($id)
    {
        $sql  = "SELECT CONCAT(nombres, ' ', apellidos) AS nombre FROM persona WHERE id = " . $id;
        $resultado = Cado::ejecutarConsulta($sql);
        foreach ($resultado as $row) {
            return $row['nombre'];
        }
    }

    public function buscarLiquidacion($orden, $idempresa) {
        $sql = "SELECT * FROM instalacion WHERE reqos = '$orden' AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function buscarDetallesLiquidacion($idliquidacion) {
        $sql = "SELECT * FROM instalacionequipomaterial
                WHERE idinstalacion = $idliquidacion";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerSerieLiquidacion($iddetalle) {
        $sql = "SELECT serie, idequipomaterial FROM guiaremisionequipomaterial
                WHERE id = $iddetalle";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerNombreSapEquipoMaterial($idequipomaterial) {
        $sql = 'SELECT descripcion, codigo 
                FROM equipomaterial
                WHERE id = ' . $idequipomaterial;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }
}
