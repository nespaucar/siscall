<?php 

require_once('../excel/Classes/PHPExcel.php');
require_once('../modelo/clsTCPDFMensajes.php');
include '../modelo/clsPersonal.php';
include '../modelo/clsTelefono.php';
date_default_timezone_set('America/Lima');
$idempresa = 0;

if(!isset($_SESSION)){
  error_reporting(E_ALL ^ E_NOTICE);
  session_start();
  $idempresa = $_SESSION['idempresa'];
} 

$fechahoy = date('Y-m-j');
$fechahoy = new DateTime($fechahoy);

$objPHPExcel = new PHPExcel();
$opersona = new Personal();
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$accion = $_GET['accion'];
$idpersona = $_GET['idpersona'];
$tipo = $_GET['tipo'];
$opcionPDF = 'I';

//Para Excel

$estiloTituloGeneral = array(
  'font' => array(
    'name'      => 'Arial',
    'strike'    => false,
    'size' =>20
  ),
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  )
);

$estiloTituloColumnas = array(
  'font' => array(
    'name'  => 'Arial',
    'size' =>10,
    'color' => array(
    'rgb' => 'FFFFFF'
    )
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb' => '538DD5')
  ),
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'alignment' =>  array(
    'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
  )
);

$estiloTituloFilas = array(
  'font' => array(
    'name'  => 'Arial',
    'size' =>10,
  ),
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
);

$estiloTituloNumero = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  ),
  'alignment' =>  array(
    'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
  )
);

function normalizar($cadena){
  return mb_strtoupper((utf8_encode($cadena)));
}

function confSheet1($objPHPExcel) {
  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle("Mensajes.pdf");
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
}

function confInicial($objPHPExcel, $estilo) {
  confSheet1($objPHPExcel, "-");    
  $objPHPExcel->getActiveSheet()->setCellValue('B3', 'REPORTE DE MENSAJES');
  $objPHPExcel->getActiveSheet()->mergeCells('B3:G3');
  $objPHPExcel->getActiveSheet()->getStyle('B3:G3')->applyFromArray($estilo);
  $objPHPExcel->getActiveSheet()->getStyle('B5:G5')->applyFromArray($estilo);
}

//Para PDF

function retornoCabeceraTabla() {
  return '<table border="1" cellspacing="1" cellpadding="3">
  			<tr style="margin: 10px;">
  				<td style="font-size:11px" align="center" style="color:red" width="5%">#</td>
  				<td style="font-size:11px" align="center" style="color:red" width="40%">CLIENTE</td>
  				<td style="font-size:11px" align="center" style="color:red" width="20%">TELÉFONO</td>
  				<td style="font-size:11px" align="center" style="color:red" width="15%">FECHA</td>
  				<td style="font-size:11px" align="center" style="color:red" width="10%">HORA</td>
  				<td style="font-size:11px" align="center" style="color:red" width="10%">ESTADO</td>
  			</tr>';
}

function corregirFecha2Mensual($fecha) {
  $expl = explode("-", $fecha);
  $mes = $expl[1];
  $anno =  $expl[0];
  $f0 = new DateTime($anno . "-" . $mes . "-01");
  $f0->modify('last day of this month');
  return $anno . "-" . $mes . "-" . $f0->format('d');
}

function tipoReporte($intervalo) {
  return '<br><h2>FECHA: ' . $intervalo . '</h2><br>';
}

if($accion == 'reporteMensajes') {
  $fecha1 = $_GET['fecha1'];
  $fecha2 = $_GET['fecha2'];
  $rango = $_GET['rango'];
  if($rango == "MENSUAL") {
    //Compruebo último día de mes para no tener problemas en el reporte, ya que no siempre hay 30 o 31 días
    $fecha2 = corregirFecha2Mensual($fecha2);
  }

  if($fecha1 == $fecha2) {
    $intervalo = date("d-m-Y", strtotime($fecha1));
  } else {
    $intervalo = 'Del ' . date("d-m-Y", strtotime($fecha1)) . ' al ' . date("d-m-Y", strtotime($fecha2));
  }

  $mensajes = $opersona->ListaMensajesReporte($idpersona, $fecha1, $fecha2);

  if($tipo == '3') {
    confInicial($objPHPExcel, $estiloTituloColumnas);  
    $objPHPExcel->setActiveSheetIndex(0);    
    $fila = 5;
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, '#');
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, 'CLIENTE');
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, 'TELÉFONO');
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, 'FECHA');
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, 'HORA');
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, 'ESTADO');
    
    foreach ($mensajes as $sms) {
        $fila++;
        $objPHPExcel->getActiveSheet()->getStyle('C' . $fila . ':C' . $fila)->applyFromArray($estiloTituloFilas);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $fila . ':B' . $fila)->applyFromArray($estiloTituloNumero);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $fila . ':D' . $fila)->applyFromArray($estiloTituloNumero);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $fila . ':E' . $fila)->applyFromArray($estiloTituloNumero);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $fila . ':F' . $fila)->applyFromArray($estiloTituloNumero);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $fila . ':G' . $fila)->applyFromArray($estiloTituloNumero);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, ($fila - 5));
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, normalizar($sms['nombre']));
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $sms['numero']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, date("d-m-Y", strtotime($sms['fecha'])));
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, date("h:i A", strtotime($sms['fecha'])));
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $sms['estado']);
    }

    header('Cache-Control: max-age=0');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Disposition: attachment;filename="Mensajes.xlsx"');
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save('php://output');   
    exit;
  } else {
    $pdf->SetTitle("Mensajes.pdf");

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

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('times', '', 10);

    // add a page
    $pdf->AddPage();

    ////////////////////////////////////////////////////////////////////////////////////////7

    $html = '<br>';
    $html = tipoReporte($intervalo);
    $html .= retornoCabeceraTabla();
    $i = 1;
    foreach ($mensajes as $sms) {
        $html .= '<tr>';
        $html .= '<td style="font-size:11px;" align="center">' . $i . '</td>';
        $html .= '<td style="font-size:11px;">' . normalizar($sms['nombre']) . '</td>';
        $html .= '<td style="font-size:11px;" align="center">' . $sms['numero'] . '</td>';
        $html .= '<td style="font-size:11px;" align="center">' . date("d-m-Y", strtotime($sms['fecha'])) . '</td>';
        $html .= '<td style="font-size:11px;" align="center">' . date("h:i A", strtotime($sms['fecha'])) . '</td>';
        $html .= '<td style="font-size:11px;" align="center">' . $sms['estado'] . '</td>';
        $html .= '</tr>';
        $i++;
    }   
    $html .= '</table>';

    // output the HTML content
    $pdf->writeHTML($html, false, false, false, false, '');

    // reset pointer to the last page
    $pdf->lastPage();

    if($tipo == 2) {
    	$opcionPDF = "D";
    }

    //Close and output PDF document
    $pdf->Output("Mensajes.pdf", $opcionPDF);
  }
}