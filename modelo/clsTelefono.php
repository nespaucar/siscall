<?php 

include_once "../cado/cado.php";
date_default_timezone_set("America/Lima");

class Telefono extends Cado
{

    public function comprobarNumero($telefono) {
        $sql  = "SELECT id FROM telefono WHERE numero = '$telefono'";
        $rs = Cado::ejecutarConsulta($sql);
        if ($rs->rowCount() > 0) {
            return false;
        }
        return true;
    }

    public function nuevo($numero, $persona_id)
    {
        session_start();
        $sql       = "INSERT INTO telefono(numero, persona_id, encargado_id, created_at, updated_at) VALUES('$numero', $persona_id, " . $_SESSION['id'] . ", '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "')";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaTelefonos($cadena, $limite, $tipo, $idempresa)
    {
        $like = " LIKE '%$cadena%' ";
        $sql  = "SELECT t.id AS idtelefono, t.numero, p.id AS idpersona,
            CASE
                WHEN u.tipo=1 THEN 'ADMINISTRADOR' WHEN u.tipo=2 THEN 'CLIENTE'
            END AS tipo,
            CONCAT(p.nombres, ' ', p.apellidos) AS nombre, p.id_AB
            FROM telefono t 
            INNER JOIN persona p ON t.persona_id = p.id
            INNER JOIN usuario u ON u.idpersona = p.id            
            WHERE u.tipo=$tipo 
            AND (tipo $like OR CONCAT(p.nombres, ' ', p.apellidos) $like OR DNI $like OR direccion $like OR t.numero $like OR p.id_AB $like)
            AND u.idempresa = $idempresa LIMIT $limite";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cargarNumeros($id) {
        $sql  = "SELECT t.id, t.numero AS idtelefono, t.numero
            FROM telefono t
            WHERE t.persona_id=$id";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function eliminar($id)
    {
        $sql        = "DELETE FROM telefono WHERE id=$id";
        $resultado  = Cado::ejecutarConsulta($sql);
        return $resultado;
    }
}