<?php
include "../modelo/clsAsignacion.php";

$asignacion = new Asignacion();

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
    $nombreempresa = $_SESSION['nombreempresa'];
    $correo = $_SESSION['correo'];
    $direccion = $_SESSION['direccion'];
    $telefono = $_SESSION['telefono'];
} 

try {
    $numero = $_POST['numero'];

    $idasignacion  = $asignacion->obtenerIdAsignacion($numero, $idempresa);
    $fechaentrega = $_POST['fechaentrega'];
    $nomtecnico = $_POST['nomtecnico'];

    $arch = substr(str_replace(" ", "_", $nombreempresa), 0, 18) . '_ASIGNACION_N_' . $numero . '.txt';

    fopen($arch, 'a');

    if(file_exists($arch)) {
        unlink($arch);
    } 

    fclose(fopen($arch, 'a'));

    $archivo = fopen($arch, 'a');

    fwrite($archivo, "\n" . $nombreempresa . "");
    fwrite($archivo, "\n" . $correo . "\n\n");
    fwrite($archivo, $direccion . "\n");
    fwrite($archivo, $telefono . "\n");
    date_default_timezone_set("America/Lima");
    fwrite($archivo, date("Y-m-d H:i:s") . "\n");
    fwrite($archivo, "\n------------------------------------------\n");
    fwrite($archivo, "ASIGNACION DE EQUIPOS Y/O MATERIALES");
    fwrite($archivo, "\n------------------------------------------\n");
    fwrite($archivo, "\nTECNICO: " . $nomtecnico . "\n");
    fwrite($archivo, "FECHA: " . $fechaentrega . "\n");
    fwrite($archivo, "NUMERO DE ASIGNACION: " . $numero . "\n");
    fwrite($archivo, "\n------------------------------------------\n");

    $rs = $asignacion->ListaDetallesAsignacion($idasignacion, $idempresa);
    if ($rs->rowCount() > 0) {
        $i = 1;
        foreach ($rs as $row) {
            fwrite($archivo, "\n#: " . $i);
            fwrite($archivo, "\nCANTIDAD: " . $row['cantidad']);

            $tipo = substr($row['numero'], 0, 1);
            $idequipomaterial = substr($row['numero'], 1);

            $materiales = $asignacion->cargarDetallesEquipoMaterial($idequipomaterial, $tipo, $idempresa);
            foreach ($materiales as $material) {
                fwrite($archivo, strtoupper(utf8_decode("\n" . $material['tipostr'] . ": " . $material['descripcion'] . "\n")));
                if($material['tipostr'] == "EQUIPO") {
                    fwrite($archivo, "SERIE: " . $material['serie'] . "\n");
                }
                break;
            }
            $i++;
        }
    }

    fwrite($archivo, "\n------------------------------------------\n\n");
    fwrite($archivo, "Comprobante Autorizado\n\n\n");

} catch (Exception $e) {
    echo "OCURRIÓ UN ERROR";
}



    

 
    


     

    

