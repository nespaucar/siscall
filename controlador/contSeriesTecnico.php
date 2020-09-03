<?php 

require_once('../excel/Classes/PHPExcel.php');
require_once('../modelo/clsTCPDF.php');
include '../modelo/clsSeriesTecnico.php';

if(!isset($_SESSION)){
  error_reporting(E_ALL ^ E_NOTICE);
  session_start();
  $idempresa = $_SESSION['idempresa'];
} 

date_default_timezone_set('America/Lima');
$fechahoy = date('Y-m-j');
$fechahoy = new DateTime($fechahoy);

$objPHPExcel = new PHPExcel();
$seriestecnico = new SeriesTecnico();
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$series = $seriestecnico->obtenerseries($_GET['idtecnico'], $idempresa);
$accion = $_GET['accion'];
$tecnico = $_GET['nombretecnico'];
$tecnico2 = substr($tecnico, 0, 9);
$tipo = $_GET['type'];
$opcionPDF = 'D';

//Para Excel

$estiloTituloGeneral = array(
  'font' => array(
    'name'      => 'Arial',
    'strike'    => false,
    'size' =>15
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

function normalizar($cadena){
  return strtoupper(utf8_encode($cadena));
}

function confSheet1($objPHPExcel, $tecnico2) {
  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle("Series" . $tecnico2 . ".pdf");
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
}

function confInicial($objPHPExcel, $estilo, $tecnico2, $tecnico) {
  confSheet1($objPHPExcel, $tecnico2);    
  $objPHPExcel->getActiveSheet()->setCellValue('B3', 'REPORTE DE SERIES EN TECNICO');
  $objPHPExcel->getActiveSheet()->mergeCells('B3:I3');
  $objPHPExcel->getActiveSheet()->setCellValue('B5', 'TECNICO');
  $objPHPExcel->getActiveSheet()->setCellValue('C5', $tecnico);
  $objPHPExcel->getActiveSheet()->mergeCells('C5:E5');
  $objPHPExcel->getActiveSheet()->getStyle('B5:B5')->applyFromArray($estilo); 
}

//Para PDF

function retornoCabeceraTabla() {
  return '<table border="1"><tr><th style="font-size:11px" align="center" style="color:red">EQUIPO</th><th style="font-size:11px" align="center" style="color:red">SERIE</th><th style="font-size:11px" align="center" style="color:red">FECHA ASIGNACION</th><th style="font-size:11px" align="center" style="color:red">EN TECNICO</th><th style="font-size:11px" align="center" style="color:red">FECHA ALMACEN</th><th style="font-size:11px" align="center" style="color:red">EN ALMACEN</th><th style="font-size:11px" align="center" style="color:red">¿INSTALADO?</th><th style="font-size:11px" align="center" style="color:red">FECHA INSTALACION</th></tr>';
}

if($accion == 'repSeriesTecnico') { 

  if($tipo == '3') {
    confInicial($objPHPExcel, $estiloTituloColumnas, $tecnico2, $tecnico);  
    $objPHPExcel->setActiveSheetIndex(0);    
    $fila = 7;
    $objPHPExcel->getActiveSheet()->getStyle('B3:B3')->applyFromArray($estiloTituloGeneral);  
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, 'EQUIPO');
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, 'SERIE');
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, 'FECHA ASIGNACION');
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, 'EN TECNICO');
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, 'FECHA ALMACEN');
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, 'EN ALMACEN');
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, '¿INSTALADO?');
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, 'FECHA INSTALACION');
    foreach ($series as $serie) {
    	$fila++;
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, normalizar($serie['equipo']));
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, normalizar($serie['serie']));
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, normalizar($serie['fechaentrega']));
        
        //DIAS ASIGNACION                
        $fechaentrega = date('Y-m-j', strtotime($serie['fechaentrega']));        
        $fechaentrega = new DateTime($fechaentrega);
        $intervalo = $fechahoy->diff($fechaentrega);

        $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, normalizar($intervalo->format('%a') . ' DIAS'));

        $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, normalizar($serie['fechaalmacen']));
        
        //DIAS ALMACEN
        $fechaalmacen = date('Y-m-j', strtotime($serie['fechaalmacen']));        
        $fechaalmacen = new DateTime($fechaalmacen);
        $intervalo = $fechahoy->diff($fechaalmacen);

        $objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, normalizar($intervalo->format('%a') . ' DIAS'));

        //¿INSTALADO?
        $instalado = $seriestecnico->comprobarinstalacionserie($serie['idserie'], $idempresa);
        if(mysqli_num_rows($instalado) > 0) {
        	foreach ($instalado as $instal) {
        		$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, normalizar('SI'));
        		//FECHA DE INSTALACION
       			 $objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, normalizar($instal['fecha_liquidacion']));
        		break;
        	}
        } else {
        	$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, normalizar('NO'));
       		$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, normalizar('-'));
        }             
    }

    header('Cache-Control: max-age=0');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Disposition: attachment;filename="Series' . $tecnico2 . '.xlsx"');
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter->save('php://output');   
    exit;
  } else {
    $pdf->SetTitle("Series" . $tecnico2 . ".pdf");

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
    $pdf->AddPage('L');

    ////////////////////////////////////////////////////////////////////////////////////////7

    $html = '<br><h2>Reporte de Series de Técnico ' . $tecnico . '</h2><br>';
    $html .= retornoCabeceraTabla();
    $html .= '<table border="1">';
    foreach ($series as $serie) {
        $html .= '<tr>';
        $html .= '<td style="font-size:11px" align="center">' . normalizar($serie['equipo']) . '</td>';
        $html .= '<td style="font-size:11px" align="center">' . normalizar($serie['serie']) . '</td>';
        $html .= '<td style="font-size:11px" align="center">' . normalizar($serie['fechaentrega']) . '</td>';
        
        //DIAS ASIGNACION                
        $fechaentrega = date('Y-m-j', strtotime($serie['fechaentrega']));        
        $fechaentrega = new DateTime($fechaentrega);
        $intervalo = $fechahoy->diff($fechaentrega);

        $html .= '<td style="font-size:11px" align="center">' . normalizar($intervalo->format('%a') . ' DIAS') . '</td>';
        $html .= '<td style="font-size:11px" align="center">' . normalizar($serie['fechaalmacen']) . '</td>';
        
        //DIAS ALMACEN
        $fechaalmacen = date('Y-m-j', strtotime($serie['fechaalmacen']));        
        $fechaalmacen = new DateTime($fechaalmacen);
        $intervalo = $fechahoy->diff($fechaalmacen);

        $html .= '<td style="font-size:11px" align="center">' . normalizar($intervalo->format('%a') . ' DIAS') . '</td>';
        
        //¿INSTALADO?
        $instalado = $seriestecnico->comprobarinstalacionserie($serie['idserie'], $idempresa);
        if(mysqli_num_rows($instalado) > 0) {
        	foreach ($instalado as $instal) {
        		$html .= '<td style="font-size:11px" align="center">' . normalizar('SI') . '</td>';
        		//FECHA DE INSTALACION
       			$html .= '<td style="font-size:11px" align="center">' . normalizar($instal['fecha_liquidacion']) . '</td>';
        		break;
        	}
        } else {
        	$html .= '<td style="font-size:11px" align="center">' . normalizar('NO') . '</td>';
       		$html .= '<td style="font-size:11px" align="center">' . normalizar('-') . '</td>';
        }  
        $html .= '</tr>';
    }   
    $html .= '</table>';     

    // output the HTML content
    $pdf->writeHTML($html, false, false, false, false, '');

    // reset pointer to the last page
    $pdf->lastPage();

    //Close and output PDF document
    if($tipo == '1') {
      $opcionPDF = 'I';
    }
    $pdf->Output("Series" . $tecnico2 . ".pdf", $opcionPDF);
  }
}