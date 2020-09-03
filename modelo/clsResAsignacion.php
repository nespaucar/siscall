<?php

include_once "../cado/cado.php";

class ResAsignacion extends Cado
{
    public function ListaDetallesResAsignacion($idtecnico, $idempresa)
    {
        $sql  = "SELECT distinct equipomaterial.id AS idequipomaterial, descripcion, codigo, (CASE WHEN tipo = 1 THEN 'EQUIPO' WHEN tipo = 2 THEN 'MATERIAL' END) AS tipostr
        	FROM resasignacion 
        	INNER JOIN resasignacionequipomaterial 
        	ON resasignacionequipomaterial.idresasignacion = resasignacion.id
        	INNER JOIN equipomaterial 
        	ON equipomaterial.id = resasignacionequipomaterial.idequipomaterial
        	WHERE idtecnico = " . $idtecnico . " 
            AND equipomaterial.idempresa = " . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaDetallesResAsignacionxEquipomaterial($idtecnico, $idequipomaterial, $idempresa) {
        $sql = 'SELECT asignacion.numero, 
            (SELECT guiaremisionequipomaterial.estado FROM guiaremisionequipomaterial INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE guiaremisionequipomaterial.id =  (SUBSTRING(asignacionequipomaterial.idequipomaterial, 2)) AND guiaremision.idempresa = ' . $idempresa . ') AS estado,
            (SELECT serie FROM guiaremisionequipomaterial INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE guiaremisionequipomaterial.id =  (SUBSTRING(asignacionequipomaterial.idequipomaterial, 2)) AND guiaremision.idempresa = ' . $idempresa . ') AS serie,
            cantidad, fechaentrega 
            FROM asignacionequipomaterial
            INNER JOIN asignacion ON asignacion.id = asignacionequipomaterial.idasignacion
            INNER JOIN persona ON persona.id = asignacion.idtecnico
            WHERE persona.id = ' . $idtecnico . '
            AND SUBSTRING(asignacionequipomaterial.idequipomaterial, 1, 1) = "S"
            AND (SELECT idequipomaterial FROM guiaremisionequipomaterial WHERE id =  (SUBSTRING(asignacionequipomaterial.idequipomaterial, 2))) = ' . $idequipomaterial . '
            AND asignacion.idempresa = ' . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaDetallesResAsignacionxEquipomaterial2($idtecnico, $idequipomaterial, $idempresa) {
        $sql = 'SELECT numero, cantidad, fechaentrega 
            FROM asignacion
            INNER JOIN asignacionequipomaterial ON asignacionequipomaterial.idasignacion = asignacion.id
            WHERE idtecnico = ' . $idtecnico . '
            AND SUBSTRING(asignacionequipomaterial.idequipomaterial, 1, 1) = "E"
            AND SUBSTRING(idequipomaterial, 2) = ' . $idequipomaterial . '
            AND asignacion.idempresa = ' . $idempresa;
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function NroDetallesResAsignacionxEquipomaterial($idtecnico, $idequipomaterial, $idempresa) {
        $sql = 'SELECT SUM(asignacionequipomaterial.cantidad) AS cantidad
            FROM asignacionequipomaterial
            INNER JOIN asignacion ON asignacion.id = asignacionequipomaterial.idasignacion
            INNER JOIN persona ON persona.id = asignacion.idtecnico            
            INNER JOIN guiaremisionequipomaterial ON SUBSTRING(asignacionequipomaterial.idequipomaterial, 2) = guiaremisionequipomaterial.id
            WHERE persona.id = ' . $idtecnico . '
            AND SUBSTRING(asignacionequipomaterial.idequipomaterial, 1, 1) = "S"
            AND (SELECT idequipomaterial FROM guiaremisionequipomaterial WHERE id =  (SUBSTRING(asignacionequipomaterial.idequipomaterial, 2))) = ' . $idequipomaterial . '
            AND guiaremisionequipomaterial.estado = "T"
            AND asignacion.idempresa = ' . $idempresa;
        $rs = Cado::ejecutarConsulta($sql);
        if($rs->rowCount() == 0) {
            return '-';
        } else {
            foreach ($rs as $row) {
                return $row['cantidad'];
            }            
        }
    }

    public function NroDetallesResAsignacionxEquipomaterial2($idtecnico, $idequipomaterial, $idempresa) {
        $sql = 'SELECT SUM(asignacionequipomaterial.cantidad) AS cantidad
            FROM asignacion
            INNER JOIN asignacionequipomaterial ON asignacionequipomaterial.idasignacion = asignacion.id
            WHERE idtecnico = ' . $idtecnico . '
            AND SUBSTRING(asignacionequipomaterial.idequipomaterial, 1, 1) = "E"
            AND SUBSTRING(asignacionequipomaterial.idequipomaterial, 2) = ' . $idequipomaterial . '     
            AND asignacion.idempresa = ' . $idempresa;
        $rs = Cado::ejecutarConsulta($sql);
        if($rs->rowCount() == 0) {
            return '-';
        } else {
            foreach ($rs as $row) {
                return $row['cantidad'];
            }            
        }
    }
}