<?php 

require_once('../excel/Classes/PHPExcel.php');
require_once('../modelo/clsTCPDF.php');
include '../modelo/clsProduccion.php';

if(!isset($_SESSION)){
  error_reporting(E_ALL ^ E_NOTICE);
  session_start();
  $idempresa = $_SESSION['idempresa'];
} 

$objPHPExcel = new PHPExcel();
$produccion = new Produccion();
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$idtecnico = $_GET['idtecnico'];
$tecnicos = $produccion->obtenertecnico($idtecnico, $idempresa);
$accion = $_GET['accion'];
$tipo = $_GET['tipo'];
$opcionPDF = 'D';

$intervalo = '';

//Para Excel

$estiloTituloGeneral = array(
  'font' => array(
    'name'      => 'Arial',
    'bold'      => true,
    'italic'    => false,
    'strike'    => false,
    'size' =>15
  ),
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  )
);

$estiloTituloReporte = array(
  'font' => array(
    'name'      => 'Arial',
    'bold'      => true,
    'italic'    => false,
    'strike'    => false,
    'size' =>11
  ),
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
  )
);

$estiloTituloColumnas = array(
  'font' => array(
    'name'  => 'Arial',
    'bold'  => true,
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

$estiloInformacion = array(
  'font' => array(
    'name'  => 'Arial',
    'color' => array(
      'rgb' => '000000'
    )
  ),
  'fill' => array(
    'type'  => PHPExcel_Style_Fill::FILL_SOLID
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

function normalizar($cadena){
  return strtoupper(utf8_encode($cadena));
}

function confSheet1($objPHPExcel, $estilo, $intervalo) {
  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle("Prod_" . $intervalo . ".pdf");
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
}

function confInicial($objPHPExcel, $estilo, $tipo, $intervalo) {
  confSheet1($objPHPExcel, $estilo, $intervalo);    
  $objPHPExcel->getActiveSheet()->setCellValue('B3', 'REPORTE DE PRODUCCIÓN');
  $objPHPExcel->getActiveSheet()->mergeCells('B3:F3');
  $objPHPExcel->getActiveSheet()->setCellValue('B5', 'TIPO');
  $objPHPExcel->getActiveSheet()->setCellValue('C5', $tipo);
  $objPHPExcel->getActiveSheet()->mergeCells('C5:F5');
  $objPHPExcel->getActiveSheet()->setCellValue('B6', 'INTERVALO');
  $objPHPExcel->getActiveSheet()->setCellValue('C6', $intervalo);
  $objPHPExcel->getActiveSheet()->mergeCells('C6:F6');
  $objPHPExcel->getActiveSheet()->getStyle('B5:B5')->applyFromArray($estilo);  
  $objPHPExcel->getActiveSheet()->getStyle('B6:B6')->applyFromArray($estilo);  
}

//Para PDF

function tipoReporte($tipo, $intervalo) {
  return '<br><h2>TIPO: ' . $tipo . '</h2><h2>INTERVALO: ' . $intervalo . '</h2><br>';
}

function retornoCabeceraTabla() {
  return '<table border="1"><tr><th align="center" style="color:red">ORDEN</th><th align="center" style="color:red">ACTIVIDAD</th><th align="center" style="color:red">PREFIJO</th><th align="center" style="color:red">F_LIQUID.</th><th align="center" style="color:red">PUNTAJE</th></tr>';
}

function retornoPieTabla($total) {
  return '<tr><th style="color:blue" align="center" colspan="4">TOTAL</th><th align="center">' . $total . '</th></tr></table><br><br>';
}

if($accion == 'reporteProduccion') {  
  $fecha1 = $_GET['fecha1'];
  $fecha2 = $_GET['fecha2'];
  $rango = $_GET['rango'];

  if($fecha1 == $fecha2) {
    $intervalo = $fecha1;
  } else {
    $intervalo = $fecha1 . '_' . $fecha2;
  }

  if($tipo == '3') {
    confInicial($objPHPExcel, $estiloTituloColumnas, $rango, $intervalo);  
    $objPHPExcel->setActiveSheetIndex(0);    
    $fila = 9;
    foreach ($tecnicos as $tecnico) {
      $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, normalizar($tecnico['nombre']));
      $objPHPExcel->getActiveSheet()->getStyle('B'.$fila.':B'.$fila)->applyFromArray($estiloTituloReporte); 
      $objPHPExcel->getActiveSheet()->getStyle('B3:B3')->applyFromArray($estiloTituloGeneral);  
      $objPHPExcel->getActiveSheet()->mergeCells('B'.$fila.':E'.$fila);
      $instalaciones = $produccion->actividadesTecnico($tecnico['id'], $fecha1, $fecha2, $idempresa);
      $fila++;
      if(mysqli_num_rows($instalaciones) != 0) {
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, 'ORDEN');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, 'ACTIVIDAD');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, 'PREFIJO');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, 'F_LIQUIDACIÓN');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, 'PUNTAJE');
        $objPHPExcel->getActiveSheet()->getStyle('B' . $fila . ':F' . $fila)->applyFromArray($estiloTituloColumnas);
        $fila++;
        $in = $fila;
        foreach ($instalaciones as $instalacion) {
          $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, normalizar($instalacion['orden']));
          $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, normalizar($instalacion['actividad']));
          $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, normalizar($instalacion['prefijo']));
          $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, normalizar($instalacion['fecha_liquidacion']));
          //OBTENGO PUNTAJE BAREMO
          $puntaje = $produccion->obtenerpuntajebaremo($instalacion['prefijo'], $instalacion['actividad'], $idempresa);
          $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, normalizar($puntaje));
          $fila++;
        }
        $objPHPExcel->getActiveSheet()->mergeCells('B'.$fila.':E'.$fila);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, 'TOTAL');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, '=SUM(F' . $in . ':F' . ($fila - 1) . ')');
        $objPHPExcel->getActiveSheet()->getStyle('B'.$fila.':F' . $fila)->applyFromArray($estiloTituloColumnas);
        $objPHPExcel->getActiveSheet()->getStyle('B'.$in.':F' . $fila)->applyFromArray($estiloInformacion);
        $fila++;
      }     
      $fila++;      
    } 
    header('Cache-Control: max-age=0');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Disposition: attachment;filename="Prod_' . $intervalo . '.xlsx"');
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
    $objWriter->save('php://output');   
    exit;
  } else {
    $pdf->SetTitle("Prod_" . $intervalo . ".pdf");

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
    $pdf->SetFont('times', '', 8);

    // add a page
    $pdf->AddPage();

    ////////////////////////////////////////////////////////////////////////////////////////7

    // create some HTML content
    $html = tipoReporte($rango, $intervalo);

    foreach ($tecnicos as $tecnico) {
      $total = 0.0;
      $html .= normalizar($tecnico['nombre']) . '<br><br>';
      $instalaciones = $produccion->actividadesTecnico($tecnico['id'], $fecha1, $fecha2, $idempresa);
      if(mysqli_num_rows($instalaciones) != 0) {
        $html .= retornoCabeceraTabla();
        foreach ($instalaciones as $instalacion) {
          $html .= '<tr>';
          $html .= '<td align="center">' . normalizar($instalacion['orden']) . '</td>';
          $html .= '<td align="center">' . normalizar($instalacion['actividad']) . '</td>';
          $html .= '<td align="center">' . normalizar($instalacion['prefijo']) . '</td>';
          $html .= '<td align="center">' . normalizar($instalacion['fecha_liquidacion']) . '</td>';
          //OBTENGO PUNTAJE BAREMO
          $puntaje = $produccion->obtenerpuntajebaremo($instalacion['prefijo'], $instalacion['actividad'], $idempresa);
          $html .= '<td align="center">' . normalizar($puntaje) . '</td>';
          $html .= '<td align="center"></td>';
          $html .= '</tr>';
          if($puntaje != '-') {
            $total += $puntaje;
          }          
        }
        $html .= retornoPieTabla($total);
      }      
    }        

    // output the HTML content
    $pdf->writeHTML($html, false, false, false, false, '');

    // reset pointer to the last page
    $pdf->lastPage();

    //Close and output PDF document
    if($tipo == '1') {
      $opcionPDF = 'I';
    }
    $pdf->Output("Prod_" . $intervalo . ".pdf", $opcionPDF);

    //============================================================+
    // END OF FILE
    //============================================================+
  }
}