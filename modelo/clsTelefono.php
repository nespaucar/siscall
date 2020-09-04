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
}