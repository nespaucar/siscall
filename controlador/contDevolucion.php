<?php
include "../modelo/clsDevolucion.php";

$accion  = $_GET['accion'];
$devolucion = new Devolucion();
date_default_timezone_set('America/Lima');

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
} 

if ($accion == "ListaDevolucion") {
    $limite  = $_POST['cboCantidadDevolucion'];
    $cadena  = $_POST['txtFiltroDevolucion'];
    $retorno = '';
    try {
        $rs = $devolucion->ListaDevolucion($cadena, $limite, $idempresa);
        if ($rs->rowCount() > 0) {
            foreach ($rs as $row) {
                $retorno .= '<tr id="' . $row['id'] . '">';
                $retorno .= '<td>' . $row['observacion'] . '</td>';
                $retorno .= '<td>' . $row['sap'] . '</td>';
                $retorno .= '<td>' . $row['producto'] . '</td>';
                $retorno .= '<td>' . $row['tipo'] . '</td>';
                $retorno .= '<td>' . $row['serie'] . '</td>';
                $retorno .= '<td>' . $row['cantidad'] . '</td></tr>';
            }
            
            $jsondata = array(
                'tabla' => $retorno,
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        } else {
            $jsondata = array(
                'tabla' => "tabla='<tr><td colspan='6'><center>NO HAY DEVOLUCIONES CON ESTE NOMBRE</center></td></tr>';",
                'paginacion' => '<b><b id="cantfilas">' . $rs->rowCount() .'</b></b>',
            );
            echo json_encode($jsondata, JSON_FORCE_OBJECT);
        }
    } catch (Exception $e) {
        echo "<tr colspan='6'><b>OCURRIÓ UN ERROR</b></tr>";
    }
}