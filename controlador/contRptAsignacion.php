<?php 

require_once('../modelo/clsTCPDF.php');
include "../modelo/clsAsignacion.php";

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
    $nombreempresa = $_SESSION['nombreempresa'];
    $correo = $_SESSION['correo'];
    $direccion = $_SESSION['direccion'];
    $telefono = $_SESSION['telefono'];
} 

$asignacion = new Asignacion();

$numero = $_GET['numero'];

$medidas = array(200, 600);
$pdf = new MYPDF('P', 'mm', $medidas, true, 'UTF-8', false);

$pdf->SetTitle(substr(str_replace(" ", "_", $nombreempresa), 0, 18) . "_ASIGNACION_N_" . $numero . ".pdf");

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists('../tcpdf/examples/lang/eng.php')) {
  require_once('../tcpdf/examples/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('times', '', 8);

// add a page
$pdf->AddPage();

////////////////////////////////////////////////////////////////////////////////////////7

try {
	$html = '';    

    $idasignacion  = $asignacion->obtenerIdAsignacion($numero, $idempresa);
    $fechaentrega = $_GET['fechaentrega'];
    $nomtecnico = $_GET['nomtecnico'];

    $html .= "<br>" . $nombreempresa;
    $html .= "<br>" . $correo . "<br><br>";
    $html .= $direccion . "<br>";
    $html .= $telefono . "<br>";
    date_default_timezone_set("America/Lima");
    $html .= date("Y-m-d H:i:s") . "<br>";
    $html .= "<br>------------------------------------------<br>";
    $html .= "ASIGNACION DE EQUIPOS Y/O MATERIALES";
    $html .= "<br>------------------------------------------<br>";
    $html .= "<br>TECNICO: " . $nomtecnico . "<br>";
    $html .= "FECHA: " . $fechaentrega . "<br>";
    $html .= "NUMERO DE ASIGNACION: " . $numero . "<br>";
    $html .= "<br>------------------------------------------<br>";

    $rs = $asignacion->ListaDetallesAsignacion($idasignacion, $idempresa);
    if ($rs->rowCount() > 0) {
        $i = 1;
        foreach ($rs as $row) {
        	$html .= "<br>#: " . $i;
        	$html .= "<br>CANTIDAD: " . $row['cantidad'];

            $tipo = substr($row['numero'], 0, 1);
            $idequipomaterial = substr($row['numero'], 1);

            $materiales = $asignacion->cargarDetallesEquipoMaterial($idequipomaterial, $tipo, $idempresa);
            foreach ($materiales as $material) {
            	$html .= strtoupper(utf8_decode("<br>" . $material['tipostr'] . ": " . $material['descripcion'] . "<br>"));
                if($material['tipostr'] == "EQUIPO") {
                	$html .= "SERIE: " . $material['serie'] . "<br>";
                }
                break;
            }
            $i++;
        }
    }

    $html .= "<br>------------------------------------------<br><br>";
    $html .= "Comprobante Autorizado<br><br><br>";
} catch (Exception $e) {
    echo "OCURRIÓ UN ERROR";
}

// output the HTML content
$pdf->writeHTML($html, false, false, false, false, '');

// reset pointer to the last page
$pdf->lastPage();

//Close and output PDF document
$pdf->Output(substr(str_replace(" ", "_", $nombreempresa), 0, 18) . "_ASIGNACION_N_" . $numero . ".pdf", 'I');
