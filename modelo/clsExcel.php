<?php
	
include_once "../excel/Classes/PHPExcel/IOFactory.php";

class ArchivoExcel extends PHPExcel_IOFactory
{
	public static function RecuperarTablaDeExcel($nombreArchivo) {
		$objPHPExcel = PHPEXCEL_IOFactory::load($nombreArchivo);
		$objPHPExcel->setActiveSheetIndex(0);
		return $objPHPExcel;
	}	
}
	
?>