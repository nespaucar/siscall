<?php

include_once "../cado/cado.php";

class Devolucion extends Cado
{

    public function ListaDevolucion($cadena, $limite, $idempresa)
    {
        $like = " LIKE '%$cadena%' ";
        $sql  = "SELECT d.id, d.observacion, 
            (SELECT codigo FROM equipomaterial WHERE id = d.idequipomaterial) AS sap, 
            (SELECT descripcion FROM equipomaterial WHERE id = d.idequipomaterial) AS producto, 
            (SELECT (CASE WHEN tipo = 1 THEN 'EQUIPO' WHEN tipo = 2 THEN 'MATERIAL' END) FROM equipomaterial WHERE id = d.idequipomaterial) AS tipo, 
            (SELECT serie FROM guiaremisionequipomaterial WHERE id = d.idguiaremisionequipomaterial) AS serie,
            cantidad
            FROM devolucion d
            WHERE (observacion $like OR 
            (SELECT codigo FROM equipomaterial WHERE id = d.idequipomaterial) $like OR 
            (SELECT descripcion FROM equipomaterial WHERE id = d.idequipomaterial) $like OR 
            (SELECT (CASE WHEN tipo = 1 THEN 'EQUIPO' WHEN tipo = 2 THEN 'MATERIAL' END) FROM equipomaterial WHERE id = d.idequipomaterial) $like OR
            (SELECT serie FROM guiaremisionequipomaterial WHERE id = d.idguiaremisionequipomaterial) $like OR
            cantidad $like)
            AND d.idempresa = $idempresa
            ORDER BY id DESC
            LIMIT $limite";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }
}
