<?php 

require_once('../excel/Classes/PHPExcel.php');
require_once('../modelo/clsTCPDF.php');
include '../modelo/clsLiquidacion.php';

if(!isset($_SESSION)){
  error_reporting(E_ALL ^ E_NOTICE);
  session_start();
  $idempresa = $_SESSION['idempresa'];
} 

$objPHPExcel = new PHPExcel();
$liquidacion = new Liquidacion();
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$idtecnico = $_GET['idtecnico'];
$idproducto = $_GET['idproducto'];
$resultado = $liquidacion->obtenertecnico($idtecnico, $idempresa);
$nombreproducto = 'TODOS';
$tipoproducto = '';
$intervalo = '';
if($idproducto != '') {
  $nombreproduct = $liquidacion->obtenernombreproducto($idproducto, $idempresa);
  if(mysqli_num_rows($nombreproduct) != 0) {
    foreach ($nombreproduct as $row) {
      $nombreproducto = $row['descripcion'];
      $tipoproducto = $row['tipo'];
      break;
    }
  }
}
//OBTENEMOS FECHAS
$fecha1 = $_GET['fecha1'];
$fecha2 = $_GET['fecha2'];
$rango = $_GET['rango'];

if($fecha1 == $fecha2) {
  $intervalo = $fecha1;
} else {
  $intervalo = $fecha1 . '_' . $fecha2;
}
//OBTENEMOS ID DE LAS LIQUIDACIONES A CONSIDERAR
if($tipoproducto == '1') {
  $idliquidaciones = $liquidacion->obtenerIdLiquidaciones($idproducto, $idempresa);
} else {
  $idliquidaciones = $idproducto;
}
$liquidaciones = $liquidacion->obtenerLiquidaciones($idtecnico, $tipoproducto, $idliquidaciones, $fecha1, $fecha2, $idempresa);
$accion = $_GET['accion'];
$tipo = $_GET['tipo'];
$opcionPDF = 'D';

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
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
  $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(70);
  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
  $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
  $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
}

function confInicial($objPHPExcel, $estilo, $tipo, $intervalo) {
  confSheet1($objPHPExcel, $estilo, $intervalo);    
  $objPHPExcel->getActiveSheet()->setCellValue('B3', 'REPORTE DE LIQUIDACIONES');
  $objPHPExcel->getActiveSheet()->mergeCells('B3:L3');
  $objPHPExcel->getActiveSheet()->setCellValue('B5', 'TIPO');
  $objPHPExcel->getActiveSheet()->setCellValue('C5', $tipo);
  $objPHPExcel->getActiveSheet()->mergeCells('C5:L5');
  $objPHPExcel->getActiveSheet()->setCellValue('B6', 'INTERVALO');
  $objPHPExcel->getActiveSheet()->setCellValue('C6', $intervalo);
  $objPHPExcel->getActiveSheet()->mergeCells('C6:L6');
  $objPHPExcel->getActiveSheet()->getStyle('B5:B5')->applyFromArray($estilo);  
  $objPHPExcel->getActiveSheet()->getStyle('B6:B6')->applyFromArray($estilo);  
}

//Para PDF

function tipoReporte($tipo, $intervalo) {
  return '<br><h2>TIPO: ' . $tipo . '</h2><h2>INTERVALO: ' . $intervalo . '</h2><br>';
}

function retornoCabeceraTabla() {
  return '<table border="1"><tr><th align="center" style="color:red">ACTIVIDAD</th><th align="center" style="color:red">PREFIJO</th><th align="center" style="color:red">O/T</th><th align="center" style="color:red">REQ_O/S</th><th align="center" style="color:red">EQUIPO</th><th align="center" style="color:red">TIPO</th><th align="center" style="color:red">SERIE</th><th align="center" style="color:red">TELEFONO</th><th align="center" style="color:red">CANTIDAD REAL</th><th align="center" style="color:red">CANTIDAD COBRA</th><th align="center" style="color:red">DIFERENCIA</th></tr>';
}

function retornoPieTabla($total) {
  return '<tr><th style="color:blue" align="center" colspan="10">RESULTADO</th><th align="center">' . $total . '</th></tr></table><br><br>';
}

if($accion == 'reporteLiquidacion') {
  if($tipo == '3') {
    confInicial($objPHPExcel, $estiloTituloColumnas, $rango, $intervalo);  
    $objPHPExcel->setActiveSheetIndex(0);    
    $fila = 9;
    foreach ($resultado as $tecnico) {
      $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, normalizar($tecnico['nombre']));
      $objPHPExcel->getActiveSheet()->getStyle('B'.$fila.':B'.$fila)->applyFromArray($estiloTituloReporte); 
      $objPHPExcel->getActiveSheet()->getStyle('B3:B3')->applyFromArray($estiloTituloGeneral);  
      $objPHPExcel->getActiveSheet()->mergeCells('B'.$fila.':E'.$fila);
      $fila++;
      if(mysqli_num_rows($liquidaciones) != 0) {
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, 'ACTIVIDAD');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, 'PREFIJO');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, 'O/T');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, 'REQ_O/S');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, 'EQUIPO');
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, 'TIPO');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, 'SERIE');
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, 'TELEFONO');
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, 'CANTIDAD REAL');
        $objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, 'CANTIDAD COBRA');
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, 'DIFERENCIA');
        $objPHPExcel->getActiveSheet()->getStyle('B' . $fila . ':L' . $fila)->applyFromArray($estiloTituloColumnas);
        $fila++;
        $in = $fila;
        foreach ($liquidaciones as $liquid) {
          $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, normalizar($liquid['actividad']));
          $objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, normalizar($liquid['prefijo']));
          $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, normalizar($liquid['ot']));
          $objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, normalizar($liquid['reqos']));

          //DATOS DE EQUIPO

          //CUANDO ES UN SOLO PRODUCTO

          if($idproducto != '') {
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, normalizar($nombreproducto));

            //SI ES MATERIAL //SI ES SERIADO

            if($tipoproducto == '2') {
              $objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, normalizar('MATERIAL'));
              $objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, normalizar('-'));
            } else {
              $objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, normalizar('SERIADO'));

              //SACAMOS EL IDEQUIPOMATERIAL DEL DETALLE DE INSTALACION Y OBTENEMOS LA SERIE

              $seriee = $liquidacion->obtenerserie($liquid['idequipomaterial'], $idempresa);
              $objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, normalizar($seriee));
            }              
          }

          //FIN DATOS EQUIPO

          $objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, normalizar($liquid['telefono']));
          $objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, normalizar($liquid['cantidadreal']));
          $objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, normalizar($liquid['cantidadcobra']));
          $objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, normalizar($liquid['cantidadreal'] - $liquid['cantidadcobra']));
          $fila++;
        }
        $objPHPExcel->getActiveSheet()->mergeCells('B'.$fila.':K'.$fila);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, 'RESULTADO');
        $objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, '=SUM(L' . $in . ':L' . ($fila - 1) . ')');
        $objPHPExcel->getActiveSheet()->getStyle('B'.$fila.':L' . $fila)->applyFromArray($estiloTituloColumnas);
        $objPHPExcel->getActiveSheet()->getStyle('B'.$in.':L' . $fila)->applyFromArray($estiloInformacion);
        $fila++;
      }     
      $fila++;      
    } 
    header('Cache-Control: max-age=0');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Disposition: attachment;filename="Liquid_' . $intervalo . '.xlsx"');
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
    $objWriter->save('php://output');   
    exit;
  } else {
    $pdf->SetTitle("Liquid_" . $intervalo . ".pdf");

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
    $pdf->AddPage('L');

    ////////////////////////////////////////////////////////////////////////////////////////7

    // create some HTML content
    $html = tipoReporte($rango, $intervalo);

    foreach ($resultado as $tecnico) {
      $total = 0.0;      
      $html .= 'PRODUCTO: ' . normalizar($nombreproducto) . '<br><br>';
      $html .= 'TÉCNICO: ' . normalizar($tecnico['nombre']) . '<br><br>';
      if(mysqli_num_rows($liquidaciones) != 0) {
        $html .= retornoCabeceraTabla();
        foreach ($liquidaciones as $liquid) {
          $html .= '<tr>';
          $html .= '<td align="center">' . normalizar($liquid['actividad']) . '</td>';
          $html .= '<td align="center">' . normalizar($liquid['prefijo']) . '</td>';
          $html .= '<td align="center">' . normalizar($liquid['ot']) . '</td>';
          $html .= '<td align="center">' . normalizar($liquid['reqos']) . '</td>';

          //DATOS DE EQUIPO

          //CUANDO ES UN SOLO PRODUCTO

          if($idproducto != '') {
            $html .= '<td align="center">' . normalizar($nombreproducto) . '</td>';

            //SI ES MATERIAL //SI ES SERIADO

            if($tipoproducto == '2') {
              $html .= '<td align="center">' . normalizar('MATERIAL') . '</td>';
              $html .= '<td align="center">' . normalizar('-') . '</td>';
            } else {
              $html .= '<td align="center">' . normalizar('SERIADO') . '</td>';

              //SACAMOS EL IDEQUIPOMATERIAL DEL DETALLE DE INSTALACION Y OBTENEMOS LA SERIE

              $seriee = $liquidacion->obtenerserie($liquid['idequipomaterial'], $idempresa);
              $html .= '<td align="center">' . normalizar($seriee) . '</td>';
            }              
          }

          //FIN DATOS EQUIPO

          $html .= '<td align="center">' . normalizar($liquid['telefono']) . '</td>';
          $html .= '<td align="center">' . normalizar($liquid['cantidadreal']) . '</td>';
          $html .= '<td align="center">' . normalizar($liquid['cantidadcobra']) . '</td>';
          $html .= '<td align="center">' . normalizar($liquid['cantidadreal'] - $liquid['cantidadcobra']) . '</td>';
          $html .= '</tr>';
          $total += $liquid['cantidadreal'] - $liquid['cantidadcobra'];
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
    $pdf->Output("Liquid_" . $intervalo . ".pdf", $opcionPDF);

    //============================================================+
    // END OF FILE
    //============================================================+
  }
}