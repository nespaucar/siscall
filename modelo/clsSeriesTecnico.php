<?php

class SeriesTecnico
{
    public function obtenerseries($idtecnico, $idempresa) {
        $conn = mysqli_connect("localhost","root","","pwperu");
        $sql  = "SELECT 
        	(SELECT descripcion FROM equipomaterial INNER JOIN guiaremisionequipomaterial ON equipomaterial.id = guiaremisionequipomaterial.idequipomaterial WHERE guiaremisionequipomaterial.id = (SUBSTRING(ae.idequipomaterial, 2))) AS equipo,
			fechaentrega,
			(SELECT id FROM guiaremisionequipomaterial WHERE id = (SUBSTRING(ae.idequipomaterial, 2))) AS idserie,
			(SELECT serie FROM guiaremisionequipomaterial WHERE id = (SUBSTRING(ae.idequipomaterial, 2))) AS serie,
			(SELECT fecha FROM guiaremision INNER JOIN guiaremisionequipomaterial ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE guiaremisionequipomaterial.id = (SUBSTRING(ae.idequipomaterial, 2))) AS fechaalmacen
			FROM asignacionequipomaterial ae
			INNER JOIN asignacion a ON a.id = ae.idasignacion
			INNER JOIN persona p ON p.id = a.idtecnico
			WHERE SUBSTRING(ae.idequipomaterial, 1, 1) != 'E' 
            AND a.idempresa = $idempresa
			AND p.id = $idtecnico";
        $resultado = $conn->query($sql);
        return $resultado;
    }

    public function comprobarinstalacionserie($idserie, $idempresa) {
        $conn = mysqli_connect("localhost","root","","pwperu");
        $sql  = "SELECT fecha_liquidacion 
        FROM instalacionequipomaterial 
        INNER JOIN instalacion ON instalacion.id = instalacionequipomaterial.idinstalacion
        WHERE idequipomaterial = CONCAT('S', $idserie)
        AND instalacion.idempresa = $idempresa";
        $resultado = $conn->query($sql);
        return $resultado;
    }
}
