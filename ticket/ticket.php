<?php
//require __DIR__ . '/autoload.php'; 
//use Mike42\Escpos\Printer;
//use Mike42\Escpos\EscposImage;
//use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
 
//$nombre_impresora = "TERMICA3"; 
//$connector = new WindowsPrintConnector($nombre_impresora);
//$printer = new Printer($connector);

$bloqueimpresion = $_POST['bloqueimpresion'];
$numero = $_POST['numero'];
$fechaentrega = $_POST['fechaentrega'];
$nomtecnico = $_POST['nomtecnico'];

//$printer->setJustification(Printer::JUSTIFY_CENTER);

session_start();

$arch = substr(str_replace(" ", "_", $_SESSION['nombreempresa']), 0, 18) . '_ASIGNACION_N_' . $numero . '.txt';

fopen($arch, 'a');

if(file_exists($arch)) {
	unlink($arch);
} 

fclose(fopen($arch, 'a'));

$archivo = fopen($arch, 'a');

 
//$printer->text("\nJACKPOLUX E.I.R.L");
fwrite($archivo, "\nJACKPOLUX E.I.R.L");
//$printer->text("\nInformatic Service\n\n");
fwrite($archivo, "\nInformatic Service\n\n");
//$printer->text("Los Algarrobos 497 - Villahermosa J.L.O - CHICLAYO.\n");
fwrite($archivo, "Los Algarrobos 497 - Villahermosa J.L.O - CHICLAYO.\n");
//$printer->text("979271615\n");
fwrite($archivo, "979271615\n");
#La fecha tambi茅n
date_default_timezone_set("America/Lima");
//$printer->text(date("Y-m-d H:i:s") . "\n");
fwrite($archivo, date("Y-m-d H:i:s") . "\n");
//$printer->text("\n------------------------------------------\n");
fwrite($archivo, "\n------------------------------------------\n");
//$printer->text("ASIGNACION DE EQUIPOS Y/O MATERIALES");
fwrite($archivo, "ASIGNACION DE EQUIPOS Y/O MATERIALES");
//$printer->text("\n------------------------------------------\n");
fwrite($archivo, "\n------------------------------------------\n");
//$printer->text("\nTECNICO: " . $nomtecnico . "\n");
fwrite($archivo, "\nTECNICO: " . $nomtecnico . "\n");
//$printer->text("FECHA: " . $fechaentrega . "\n");
fwrite($archivo, "FECHA: " . $fechaentrega . "\n");
//$printer->text("NUMERO DE ASIGNACION: " . $numero . "\n");
fwrite($archivo, "NUMERO DE ASIGNACION: " . $numero . "\n");
//$printer->text("\n------------------------------------------\n");
fwrite($archivo, "\n------------------------------------------\n");

//$printer->setJustification(Printer::JUSTIFY_LEFT);

$detalles = explode('@@@', $bloqueimpresion);

for ($i=0; $i < count($detalles) - 1; $i++) { 
	$det = explode('@@', $detalles[$i]);
	if($det[1] == '-') {
		//$printer->text("\nCANTIDAD: " . $det[0]);
		fwrite($archivo, "\nCANTIDAD: " . $det[0]);
		//$printer->text("\nMATERIAL: " . $det[2] . "\n");
		fwrite($archivo, strtoupper(utf8_decode("\nMATERIAL: " . $det[2] . "\n")));
	} else {
		//$printer->text("\nCANTIDAD: " . $det[0]);
		fwrite($archivo, "\nCANTIDAD: " . $det[0]);
		//$printer->text("\nEQUIPO: " . $det[2]);
		fwrite($archivo, strtoupper(utf8_decode("\nEQUIPO: " . $det[2])));
		//$printer->text("\nSERIE: " . $det[1] . "\n");
		fwrite($archivo, "\nSERIE: " . $det[1] . "\n");
	}
}	

//$printer->setJustification(Printer::JUSTIFY_CENTER);
//$printer->text("\n------------------------------------------\n\n");	
fwrite($archivo, "\n------------------------------------------\n\n");
//$printer->text("Comprobante Autorizado\n\n\n");
fwrite($archivo, "Comprobante Autorizado\n\n\n");

/*Hacemos que el papel salga.*/
//$printer->feed();
 
/*Cortamos el papel.*/
//$printer->cut();
 
/*Por medio de la impresora mandamos un pulso.*/
//$printer->pulse();
 
/*Para imprimir realmente, tenemos que "cerrar" la conexi贸n con la impresora*/
//$printer->close();
