<?php

include_once "../cado/cado.php";
date_default_timezone_set("America/Lima");

class Personal extends Cado
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

    public function recoverPass($nombre, $idempresa)
    {
        $sql       = "SELECT id, nombre  FROM usuario WHERE nombre = '$nombre' AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function reiniciarPass($pass, $idusuario, $idempresa)
    {
        $sql       = "UPDATE usuario SET pass = '$pass', updated_at = '" . date("Y-m-d H:i:s") . "' WHERE id = $idusuario AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaPersonal($cadena, $limite, $tipo, $idempresa)
    {
        $like = " LIKE '%$cadena%' ";
        $sql  = "SELECT p.id AS idpersona, u.id AS idusuario, u.estado AS estado, u.email, u.nombre AS nombreusuario,
            CASE
                WHEN u.tipo=1 THEN 'ADMINISTRADOR' WHEN u.tipo=2 THEN 'CLIENTE'
            END AS tipo,
            CONCAT(p.nombres, ' ', p.apellidos) AS nombre, p.id_AB, p.DNI, p.direccion, p.telefono
            FROM usuario u INNER JOIN persona p ON u.idpersona = p.id
            WHERE u.tipo=$tipo 
            AND (tipo $like OR CONCAT(p.nombres, ' ', p.apellidos) $like OR direccion $like)
            AND u.idempresa = $idempresa LIMIT $limite";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function DatosPersona($carnet)
    {
        $sql  = "SELECT id FROM persona WHERE id_AB='" . $carnet . "'";
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

    public function nuevo($nombres, $apellidos, $id_AB, $direccion, $tipo, $idempresa)
    {
        session_start();
        $pass       = md5('admin');
        $sql        = "INSERT INTO persona(nombres, apellidos, id_AB, direccion, encargado_id, created_at, updated_at) VALUES('$nombres', '$apellidos', '$id_AB', '$direccion', " . $_SESSION['id'] . ", '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "')";
        $resultado  = Cado::ejecutarConsulta($sql);
        $sql2       = "INSERT INTO usuario(nombre, pass, estado, tipo, idpersona, idempresa, encargado_id, created_at, updated_at) VALUES('$id_AB', '$pass', 1, $tipo, (SELECT MAX(id) FROM persona), $idempresa, " . $_SESSION['id'] . ", '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "')";
        $resultado2 = Cado::ejecutarConsulta($sql2);
        return $resultado2;
    }

    public function cargardatospersona($id, $idempresa)
    {
        $sql       = "SELECT p.*, u.email, u.tipo FROM persona p INNER JOIN usuario u ON p.id=u.idpersona WHERE p.id=$id AND u.idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function modificar($id, $nombres, $apellidos, $id_AB, $direccion, $tipo, $idempresa)
    {
        $sql        = "UPDATE persona SET nombres='$nombres', apellidos='$apellidos', id_AB='$id_AB', direccion='$direccion', updated_at='" . date("Y-m-d H:i:s") . "' WHERE id=$id";
        $resultado  = Cado::ejecutarConsulta($sql);
        $sql2       = "UPDATE usuario SET nombre='$id_AB', updated_at='" . date("Y-m-d H:i:s") . "', tipo=$tipo WHERE idpersona=$id AND idempresa = $idempresa";
        $resultado2 = Cado::ejecutarConsulta($sql2);
        return $resultado2;
    }

    public function eliminar($id, $idempresa)
    {
        $sql        = "DELETE FROM usuario WHERE idpersona=$id AND idempresa = $idempresa";
        $resultado  = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cambiarestadousuario($id, $estado, $idempresa)
    {
        $sql       = "UPDATE usuario SET estado=$estado, updated_at='" . date("Y-m-d H:i:s") . "' WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function resetearclave($id, $idempresa)
    {
        $pass      = md5('admin');
        $sql       = "UPDATE usuario SET pass='$pass', updated_at='" . date("Y-m-d H:i:s") . "' WHERE id=$id AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cambiarclave($clavenueva, $idempresa)
    {
        session_start();
        $clavenueva = md5($clavenueva);
        $sql        = "UPDATE usuario SET pass='$clavenueva', updated_at='" . date("Y-m-d H:i:s") . "' WHERE id=" . $_SESSION['id'] . " AND idempresa = " . $idempresa;
        $resultado  = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function gener($idusuario, $idempresa)
    {
        $sql  = "SELECT estado FROM usuario WHERE id = $idusuario AND idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        foreach ($resultado as $row) {
            return $row['estado'];
        }
    }

    public function generarClave() {
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $clave = substr(str_shuffle($permitted_chars), 0, 6);
        return $this->comprobarClave($clave, $permitted_chars);
    }

    public function comprobarClave($clave, $permitted_chars) {
        $sql  = "SELECT id FROM persona WHERE id_AB = '$clave'";
        $resultado = Cado::ejecutarConsulta($sql);
        if ($resultado->rowCount() > 0) {
            $clave = substr(str_shuffle($permitted_chars), 0, 6);
            $this->comprobarClave($clave, $permitted_chars);
        }
        return $clave;
    }

    public function obtenerpersonas($idempresa) {
        $sql  = "SELECT p.id, 
            CASE
                WHEN u.tipo=1 THEN 'ADMINISTRADOR' WHEN u.tipo=2 THEN 'CLIENTE'
            END AS tipo,
            CONCAT(p.nombres, ' ', p.apellidos) AS nombre, p.id_AB AS codigo
            FROM persona p
            INNER JOIN usuario u ON p.id = u.idpersona
            WHERE u.idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cargarDatosPersonaC($id) {
        $sql  = "SELECT p.id_AB AS codigo, 
            CASE
                WHEN u.tipo=1 THEN 'ADMINISTRADOR' WHEN u.tipo=2 THEN 'CLIENTE'
            END AS tipo
            FROM persona p
            INNER JOIN usuario u ON p.id = u.idpersona
            WHERE p.id = $id";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function comprobarExistenciaCodigo($codigo) {
        $sql  = "SELECT id, CONCAT(p.nombres, ' ', p.apellidos) AS nombre FROM persona p WHERE id_AB = '$codigo' LIMIT 1";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function cargarSelectAdministradores() {
        $sql  = "SELECT p.id, CONCAT(p.nombres, ' ', p.apellidos) AS nombre, p.principal
            FROM persona p
            INNER JOIN usuario u ON p.id = u.idpersona
            WHERE u.tipo = 1";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaClientesReporte($idempresa) {
        $sql  = "SELECT p.id AS idpersona, CONCAT(p.nombres, ' ', p.apellidos) AS nombre, p.id_AB AS clave
            FROM usuario u INNER JOIN persona p ON u.idpersona = p.id
            WHERE u.tipo=2 
            AND u.idempresa = $idempresa";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function ListaMensajesReporte($idpersona, $fecha1, $fecha2) {
        $sql  = "SELECT m.nombre, m.numero, m.created_at AS fecha,
                CASE
                    WHEN m.estado = 'queued' THEN 'ENVIADO' ELSE 'ERROR AL ENVIAR'
                END AS estado
            FROM mensaje m 
            INNER JOIN persona p ON m.idpersona = p.id
            INNER JOIN usuario u ON u.idpersona = p.id
            WHERE u.tipo = 2
            AND m.created_at BETWEEN CAST('$fecha1' AS DATE) AND CAST('$fecha2' AS DATE);";
        if($idpersona !== "") {
            $sql .= " AND p.id = " . $idpersona;
        }
        $sql .= " ORDER BY m.created_at";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }
}
