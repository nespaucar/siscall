<?php

include_once "../cado/cado.php";

class GuiaRemision extends Cado
{

    public function ListaGuiasRemision($cadena, $limite, $fecha, $idempresa)
    {
        $like = " LIKE '%$cadena%' ";
        $sql  = "SELECT * FROM guiaremision
            WHERE numero $like AND fecha = '$fecha'
            AND idempresa = $idempresa
            ORDER BY fecha DESC
            LIMIT $limite";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaGuiasRemisionManuales($idempresa)
    {
        $sql = "SELECT id FROM guiaremision WHERE numero LIKE '%MANUAL%' AND idempresa = $idempresa";
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

    public function nuevo($numero, $fecha, $idempresa)
    {
        $sql       = "INSERT INTO guiaremision(numero, fecha, idempresa) VALUES('$numero', '$fecha', $idempresa)";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cargardatosguiaremision($id, $idempresa)
    {
        $sql       = "SELECT * FROM guiaremision WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function modificar($numero, $fecha, $idempresa)
    {
        $sql       = "UPDATE guiaremision SET numero='$numero', fecha='$fecha' WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function eliminar($id, $idempresa)
    {
        $sql       = "DELETE FROM guiaremision WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaDetallesGuiasRemision($idguiaremision, $idempresa)
    {
        $sql  = "SELECT cantidad, equipomaterial.codigo, descripcion, (CASE WHEN tipo = 1 THEN 'EQU' WHEN tipo = 2 THEN 'MAT' END) AS tipostr, serie, guiaremisionequipomaterial.estado FROM guiaremisionequipomaterial 
            INNER JOIN guiaremision
            ON guiaremisionequipomaterial.idguiaremision = guiaremision.id
            INNER JOIN equipomaterial
            ON equipomaterial.id = guiaremisionequipomaterial.idequipomaterial
            WHERE guiaremision.id = $idguiaremision
            AND guiaremision.idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function nuevaGuiaRemision($numero, $fecha, $estado, $idempresa) {
        $sql = "INSERT INTO guiaremision(numero, fecha, estado, idempresa) VALUES('" . $numero . "','" . $fecha ."', '" . $estado . "', " . $idempresa . ")";
        $rs = Cado::ejecutarConsulta($sql);
        return $rs;
    }

    public function nuevoDetalleGuiaRemision($numero, $idequipomaterial, $cantidad, $idunico, $idempresa) {
        $sql = "INSERT INTO guiaremisionequipomaterial(idguiaremision, idequipomaterial, cantidad, idunico) VALUES((SELECT id FROM guiaremision WHERE numero = '" . $numero . "' AND idempresa = " . $idempresa . "), " . $idequipomaterial . "," . $cantidad . ", '" . $idunico . "')";
        $rs = Cado::ejecutarConsulta($sql);
        return $rs;
    }

    public function aumentarStockMaterial($id, $stock, $idempresa) {
        $sql = "UPDATE equipomaterial SET stock = (stock + " . $stock . ") WHERE id = " . $id . " AND idempresa = " . $idempresa;
        $rs = Cado::ejecutarConsulta($sql);
        return $rs;
    }

    public function cambiarEstadosInstalados() {
        $sql = "SELECT SUBSTRING(idequipomaterial, 2) extract 
                FROM instalacionequipomaterial 
                WHERE SUBSTRING(idequipomaterial, 1, 1) = 'S'";
        $rs = Cado::ejecutarConsulta($sql);
        return $rs;
    }

    public function cambioEstadoInstalado($id) {
        $sql = 'UPDATE guiaremisionequipomaterial SET estado = "I" WHERE id = "' . $id . '"';
        $rs = Cado::ejecutarConsulta($sql);
    }
}
