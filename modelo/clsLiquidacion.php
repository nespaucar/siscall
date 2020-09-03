<?php

class Liquidacion
{
    public function obtenertecnico($idtecnico, $idempresa) {
        $conn = mysqli_connect("localhost","root","","pwperu");
        $sql       = "SELECT persona.id, CONCAT(nombres, ' ', apellidos) AS nombre FROM persona 
        INNER JOIN usuario ON usuario.idpersona = persona.id 
        WHERE usuario.tipo = 2
        AND persona.id = $idtecnico
        AND usuario.idempresa = $idempresa
        ORDER BY id ASC";
        $resultado = $conn->query($sql);
        return $resultado;
    }

    public function obtenernombreproducto($idproducto, $idempresa) {
        $conn = mysqli_connect("localhost","root","","pwperu");
        $sql       = "SELECT descripcion, tipo FROM equipomaterial WHERE id = $idproducto AND idempresa = $idempresa";
        $resultado = $conn->query($sql);        
        return $resultado;
    }

    public function obtenerLiquidaciones($idtecnico, $idproducto, $idliquidaciones, $fecha1, $fecha2, $idempresa) {
        $conn = mysqli_connect("localhost","root","","pwperu");
        $sql = "SELECT actividad, prefijo, ot, reqos,
        idequipomaterial, telefono, cantidad AS cantidadreal, cantidadcobra 
        FROM instalacion 
        INNER JOIN instalacionequipomaterial ON instalacion.id = instalacionequipomaterial.idinstalacion
        WHERE (idtecnico = $idtecnico OR idtecnico2 = $idtecnico OR idtecnico3 = $idtecnico) 
        AND SUBSTR(idequipomaterial, 2) IN (" . $idliquidaciones . ")
        AND fecha_liquidacion BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "'
        AND instalacion.idempresa = " . $idempresa . "
        ORDER BY actividad";
        $resultado = $conn->query($sql);
        return $resultado;
    }

    public function obtenerserie($idproducto, $idempresa) {
        $conn = mysqli_connect("localhost","root","","pwperu");
        $idproducto = substr($idproducto, 1);
        $sql       = "SELECT serie FROM guiaremisionequipomaterial INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE id = $idproducto AND idempresa = $idempresa";
        $resultado = $conn->query($sql); 
        foreach ($resultado as $row) {
            $res = $row['serie'];
            break;
        }       
        return $res;
    }

    public function obtenerIdLiquidaciones($idproducto, $idempresa) {
        $conn = mysqli_connect("localhost","root","","pwperu");
        $sql = "SELECT guiaremisionequipomaterial.id FROM guiaremisionequipomaterial
        INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision
        WHERE idequipomaterial = " . $idproducto . " AND estado = 'I'
        AND guiaremision.idempresa = " . $idempresa;
        $res = '';
        $resultado = $conn->query($sql);
        if(mysqli_num_rows($resultado) > 0) {
            foreach ($resultado as $row) {
                $res .= $row['id'] . ',';
            } 
            //QUITAMOS LA ULTIMA ',' 
            return substr($res, 0, -1);
        } else {
            return '0';
        }            
    }

    /*

    public function produccionMensual($fecha_inicio, $fecha_fin, $tipo)
    {
        $sql = "SELECT orden, fecha_liquidacion, observacion, actividad, prefijo, idtecnico, idtecnico2, idtecnico3 FROM instalacion";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function produccionAnual($fecha_inicio, $fecha_fin, $tipo)
    {
        $sql = "SELECT orden, fecha_liquidacion, observacion, actividad, prefijo, idtecnico, idtecnico2, idtecnico3 FROM instalacion";
        $resultado = Cado::ejecutarConsulta($sql);
        return $resultado;
    }

    public function datosTecnico($idtecnico)
    {
        $sql = "SELECT id_AB, CONCAT(nombres, ' ', apellidos) as nombre WHERE id = " . $idpersona;
        $resultado = Cado::ejecutarConsulta($sql);
        foreach ($resultado as $row) {
            $tecnico = array(
                'nombre' => $row['nombre'],
                'id_AB' => $row['id_AB'],
            );
        }
        return $tecnico;
    }

    */
}
