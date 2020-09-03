<?php

class Produccion
{
    //$conn = mysqli_connect("jackpolux.com","jackpolu_nespauc","admin","jackpolu_sispwperu");
    //$conn = mysqli_connect("martinampuero.com","mampuero_pwperu","A1savZ4Deq","mampuero_sispwperu");
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

    public function actividadesTecnico($idtecnico, $fecha1, $fecha2, $idempresa) {
        $conn = mysqli_connect("localhost","root","","pwperu");
        $sql = "SELECT orden, actividad, prefijo, fecha_liquidacion 
        FROM instalacion 
        WHERE (idtecnico = $idtecnico OR idtecnico2 = $idtecnico OR idtecnico3 = $idtecnico) 
        AND fecha_liquidacion BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "' 
        AND instalacion.idempresa = $idempresa
        ORDER BY actividad";
        $resultado = $conn->query($sql);
        return $resultado;
    }

    public function obtenerpuntajebaremo($prefijo, $actividad, $idempresa) {
        $conn = mysqli_connect("localhost","root","","pwperu");
        $sql = "SELECT puntaje 
        FROM baremo 
        WHERE idprefijo = (SELECT id FROM prefijo WHERE nombre = '" . $prefijo . "' AND idempresa = " . $idempresa .") 
        AND idactividad = (SELECT id FROM actividad WHERE nombre = '" . $actividad . "' AND idempresa = " . $idempresa . ")
        AND baremo.idempresa = " . $idempresa;
        $resultado = $conn->query($sql);
        if(mysqli_num_rows($resultado) != 0) {
            foreach ($resultado as $row) {
                return $row['puntaje'];
            }
        } else {
            return '-';
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
