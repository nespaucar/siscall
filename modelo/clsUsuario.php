<?php

include_once "../cado/cado.php";

class Usuario extends Cado
{

    public function login($nombre, $pass)
    {
        $nombre = mysqli_real_escape_string(Cado::conn(), $nombre);
        $pass = mysqli_real_escape_string(Cado::conn(), $pass);
        $sql = "SELECT u.id AS id, u.idempresa, e.nombre AS nombreempresa, e.logo, e.logo2, e.correo, e.descripcion, e.mision, e.vision, e.sucursal, e.paginaweb, e.telefono, e.direccion,
        CASE
            WHEN tipo=1 THEN 'SUPERUSUARIO' WHEN tipo=2 THEN 'TÉCNICO'
        END AS tipo,
        u.nombre as cuenta, email, estado, CONCAT(p.nombres, ' ', apellidos) AS nombre
        FROM usuario u
        INNER JOIN persona p ON u.idpersona = p.id
        INNER JOIN empresa e ON e.id = u.idempresa
        WHERE u.nombre = '$nombre' AND pass = '$pass'";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function recoverPass($email, $idempresa)
    {
        $sql       = "SELECT id, nombre FROM usuario WHERE email = '$email' AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function reiniciarPass($pass, $idusuario, $idempresa)
    {
        $sql       = "UPDATE usuario SET pass = '$pass' WHERE id = $idusuario AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaUsuarios($cadena, $limite, $tipo, $idempresa)
    {
        $like = " LIKE '%$cadena%' ";
        $sql  = "SELECT p.id AS idpersona, u.id AS idusuario, u.estado AS estado, u.email, u.nombre AS nombreusuario,
            CASE
                WHEN u.tipo=1 THEN 'ADMINISTRADOR' WHEN u.tipo=2 THEN 'CLIENTE'
            END AS tipo,
            CONCAT(p.nombres, ' ', p.apellidos) AS nombre, p.id_AB, p.DNI, p.direccion, p.telefono
            FROM usuario u INNER JOIN persona p ON u.idpersona = p.id
            WHERE u.tipo=$tipo 
            AND (tipo $like OR CONCAT(p.nombres, ' ', p.apellidos) $like OR DNI $like OR direccion $like OR telefono $like OR u.email $like)
            AND u.idempresa = $idempresa LIMIT $limite";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cambiarestadousuario($id, $estado, $idempresa)
    {
        $sql       = "UPDATE usuario SET estado=$estado WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function resetearclave($id, $idempresa)
    {
        $pass      = md5('admin');
        $sql       = "UPDATE usuario SET pass='$pass' WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cambiarclave($clavenueva, $idempresa)
    {
        session_start();
        $clavenueva = md5($clavenueva);
        $sql        = "UPDATE usuario SET pass='$clavenueva' WHERE id=" . $_SESSION['id'] . " AND idempresa = " . $idempresa;
        $resultado  = Cado::ejecutarConsulta($sql);
        return $resultado;
    }
}
