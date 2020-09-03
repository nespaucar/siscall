<?php
include "../modelo/clsResAsignacion.php";

$accion = $_GET['accion'];
$resasignacion = new ResAsignacion();

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
} 

if ($accion == "ListaDetallesResAsignacion") {
	$idtecnico = $_GET['idtecnico'];
	$lista = $resasignacion->ListaDetallesResAsignacion($idtecnico, $idempresa);
	$retorno = '';
	if($lista->rowCount() != 0) {
		$i = 0;
		foreach ($lista as $row) {
			$retorno .= '<tr>';
			$retorno .= '<td>' . ($i + 1) . '</td>';
			$retorno .= '<td>' . $row['codigo'] . '</td>';
			$retorno .= '<td>' . $row['descripcion'] . '</td>';
			$retorno .= '<td>' . $row['tipostr'] . '</td>';
			if($row['tipostr'] == 'EQUIPO') {
				$cantidad = $resasignacion->NroDetallesResAsignacionxEquipomaterial($idtecnico, $row['idequipomaterial'], $idempresa);
			} else {
				$cantidad = $resasignacion->NroDetallesResAsignacionxEquipomaterial2($idtecnico, $row['idequipomaterial'], $idempresa);
			}
			if($cantidad == '') {
				$cantidad = '0';
			}	
			$retorno .= '<td>' . $cantidad . '</td>';		
			$retorno .= '<td>
							<a href="#carousel-ejemplo" class="btnDetallesResAsignacionxEquipomaterial btn btn-success btn-xs text-center" data-slide="next" data-tipo="' . $row['tipostr'] . '" data-idtecnico="' . $idtecnico . '" data-idequipomaterial="' . $row['idequipomaterial'] . '" data-descripcion="' . $row['descripcion'] . '" data-codigo="' . $row['codigo'] . '" data-cantidad="' . $cantidad . '">
								<div class="glyphicon glyphicon-list"></div>
							</a>
						</td>';
			$retorno .= '</tr>';
			$i++;
		}
	} else {
		$retorno .= '<tr><td colspan="6">ESTE TÉCNICO NO TIENE EQUIPOS NI MATERIALES ASIGNADOS</td></tr>';
	}
	echo $retorno;
}

if ($accion == "ListaDetallesResAsignacionxEquipomaterial") {
	$idtecnico = $_POST['idtecnico'];
	$idequipomaterial = $_POST['idequipomaterial'];
	$tipo = $_POST['tipo'];
	if($tipo == 'EQUIPO') {
		$lista = $resasignacion->ListaDetallesResAsignacionxEquipomaterial($idtecnico, $idequipomaterial, $idempresa);
		$retorno = '';
		if($lista->rowCount() != 0) {
			$i = 0;
			foreach ($lista as $row) {
				//

				if($row['estado'] == 'I') {
					$estado = '<td style="color:green">INSTALADO</td>';
					$dias = $estado;

				} else {
					$estado = '<td style="color:red">EN TECNICO</td>';
					date_default_timezone_set('America/Lima');
	                $fechahoy = date('Y-m-j');
	                $fechaentrega = date('Y-m-j', strtotime($row['fechaentrega']));
	                $fechahoy = new DateTime($fechahoy);
	                $fechaentrega = new DateTime($fechaentrega);
	                $intervalo = $fechahoy->diff($fechaentrega);
	                $dias = '<td style="color:red">' . $intervalo->format('%a') . ' DIAS</td>';
				}

				//
				$retorno .= '<tr>';
				$retorno .= '<td>' . ($i + 1) . '</td>';
				$retorno .= '<td>' . $row['numero'] . '</td>';
				$retorno .= '<td>' . $row['fechaentrega'] . '</td>';
				///
				
                $retorno .= $dias;

				///
				$retorno .= '<td>' . $row['cantidad'] . '</td>';
				$retorno .= '<td>' . $row['serie'] . '</td>';
				$retorno .= $estado;
				$retorno .= '</tr>';
				$i++;
			}
		} else {
			$retorno .= '<tr><td colspan="7">NO HAY EQUIPOS DE ESTE TIPO ASIGNADOS.</td></tr>';
		}
	} else {
		$lista = $resasignacion->ListaDetallesResAsignacionxEquipomaterial2($idtecnico, $idequipomaterial, $idempresa);
		$retorno = '';
		if($lista->rowCount() != 0) {
			$i = 0;
			foreach ($lista as $row) {
				//

				date_default_timezone_set('America/Lima');
                $fechahoy = date('Y-m-j');
                $fechaentrega = date('Y-m-j', strtotime($row['fechaentrega']));
                $fechahoy = new DateTime($fechahoy);
                $fechaentrega = new DateTime($fechaentrega);
                $intervalo = $fechahoy->diff($fechaentrega);
                $dias = '<td style="color:red">' . $intervalo->format('%a') . ' DIAS</td>';

				//
				$retorno .= '<tr>';
				$retorno .= '<td>' . ($i + 1) . '</td>';
				$retorno .= '<td>' . $row['numero'] . '</td>';
				$retorno .= '<td>' . $row['fechaentrega'] . '</td>';
				///
				
                $retorno .= $dias;

				///
				$retorno .= '<td>' . $row['cantidad'] . '</td>';
				$retorno .= '<td>-</td>';
				$retorno .= '<td>-</td>';
				$retorno .= '</tr>';
				$i++;
			}
		} else {
			$retorno .= '<tr><td colspan="7">NO HAY MATERIALES DE ESTE TIPO ASIGNADOS.</td></tr>';
		}
	}
	echo $retorno;
}