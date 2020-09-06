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
        $sql  = "SELECT t.id, t.numero
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

    public function nuevoMensaje($idpersona, $nombre, $mensaje, $numero, $estado)
    {
        $sql       = 'INSERT INTO mensaje(idpersona, nombre, encargado_id, numero, mensaje, estado, created_at) VALUES(' . $idpersona . ', "' . $nombre . '", 1, "' . $numero . '", "' . $mensaje . '", "' . $estado . '", "' . date("Y-m-d H:i:s") . '")';
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function nuevoConfiguracionMensaje($mensaje)
    {
        $sql       = 'INSERT INTO configuracion(mensaje, created_at) VALUES("' . $mensaje . '", "' . date("Y-m-d H:i:s") . '")';
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function obtenerConfiguracionMensaje()
    {
        $mensaje   = "null";
        $sql       = 'SELECT mensaje FROM configuracion ORDER BY id DESC LIMIT 1';
        $resultado = Cado::ejecutarConsulta($sql);
        if($resultado->rowCount() > 0) {
            foreach ($resultado as $row) {
                $mensaje = $row["mensaje"];
            }
        }
        return $mensaje;
    }

    public function cargarNumerosAdministradorPrincipal() {
        $sql  = "SELECT t.numero
            FROM telefono t
            INNER JOIN persona p ON p.id = t.persona_id
            WHERE p.principal = 1";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function adminsprincipales() {
        $sql  = "SELECT id FROM persona WHERE principal = 1";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cambiarestadoprincipal($id, $estado) {
        $sql       = "UPDATE persona SET principal = $estado, updated_at = '" . date("Y-m-d H:i:s") . "' WHERE id = $id";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }
}