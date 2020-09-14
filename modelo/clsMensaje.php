<?php

include_once "../cado/cado.php";
date_default_timezone_set("America/Lima");

class Mensaje extends Cado
{

    public function login($nombre, $pass)
    {
        $nombre = mysqli_real_escape_string(Cado::conn(), $nombre);
        $pass = mysqli_real_escape_string(Cado::conn(), $pass);
        $sql = "SELECT u.id AS id, u.idempresa, e.nombre AS nombreempresa, e.logo, e.logo2, e.correo, e.descripcion, e.mision, e.vision, e.sucursal, e.paginaweb, e.telefono, e.direccion,
        CASE
            WHEN tipo=1 THEN 'ADMINISTRADOR' WHEN tipo=2 THEN 'CLIENTE'
        END AS tipo,
        u.nombre as cuenta, email, estado, CONCAT(p.nombres, ' ', apellidos) AS nombre
        FROM usuario u
        INNER JOIN persona p ON u.idpersona = p.id
        INNER JOIN empresa e ON e.id = u.idempresa
        WHERE u.nombre = '$nombre' AND pass = '$pass'";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }
}
