<?php 

include '../modelo/clsExcel.php';
include '../cado/cado.php';

$cado = new Cado();

$tabla = $_GET['accion'];

if(!isset($_SESSION)){
    error_reporting(E_ALL ^ E_NOTICE);
    session_start();
    $idempresa = $_SESSION['idempresa'];
} 

////////////////////////////////////////////////////GUIAS DE REMISION

if($tabla == 'GuiasRemision') {
	try {
		set_time_limit(0);
		$nom_archivo_a_guardar = $_FILES['fileExcel']['name'];
		$archivo_copiado = $_FILES['fileExcel']['tmp_name'];
		$archivo_guardado = 'copia_' . $nom_archivo_a_guardar . '_' . $idempresa;

		copy($archivo_copiado, $archivo_guardado);

		$tablaExcel = ArchivoExcel::RecuperarTablaDeExcel($archivo_guardado);

		$tablaExcel->setActiveSheetIndex(1);

		//Comprobar si el archivo es del formato correcto

		if($tablaExcel->getActiveSheet()->getCell('A1')->getValue() == 'ZONAL' &&
		$tablaExcel->getActiveSheet()->getCell('B1')->getValue() == 'MOVIMIENTO' &&
		$tablaExcel->getActiveSheet()->getCell('C1')->getValue() == 'NRO_GUIA' &&
		$tablaExcel->getActiveSheet()->getCell('D1')->getValue() == 'OBSERVACIONES' &&
		$tablaExcel->getActiveSheet()->getCell('E1')->getValue() == 'FECHA_SOLICITUD' &&
		$tablaExcel->getActiveSheet()->getCell('F1')->getValue() == 'FECHA_ATENCION' &&
		$tablaExcel->getActiveSheet()->getCell('G1')->getValue() == 'FECHA_VALIDACION' &&
		$tablaExcel->getActiveSheet()->getCell('H1')->getValue() == 'POSICION' &&
		$tablaExcel->getActiveSheet()->getCell('I1')->getValue() == 'CODIGO_SAP' &&
		$tablaExcel->getActiveSheet()->getCell('J1')->getValue() == 'DESCRIPCION' &&
		$tablaExcel->getActiveSheet()->getCell('K1')->getValue() == 'UNIDAD' &&
		$tablaExcel->getActiveSheet()->getCell('L1')->getValue() == 'CANTIDAD' &&
		$tablaExcel->getActiveSheet()->getCell('M1')->getValue() == 'LOTE' &&
		$tablaExcel->getActiveSheet()->getCell('N1')->getValue() == 'IND_SB' &&
		$tablaExcel->getActiveSheet()->getCell('O1')->getValue() == 'COD_ALM' &&
		$tablaExcel->getActiveSheet()->getCell('P1')->getValue() == 'FECHA_VALIDACIONSAP' &&
		$tablaExcel->getActiveSheet()->getCell('Q1')->getValue() == 'RESERVA' &&
		$tablaExcel->getActiveSheet()->getCell('R1')->getValue() == 'MES' &&
		$tablaExcel->getActiveSheet()->getCell('S1')->getValue() == 'ANIO' &&
		$tablaExcel->getActiveSheet()->getCell('T1')->getValue() == 'CARNET' &&
		$tablaExcel->getActiveSheet()->getCell('U1')->getValue() == 'DNI_TECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('V1')->getValue() == 'TECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('W1')->getValue() == 'CONTRATISTA' &&
		$tablaExcel->getActiveSheet()->getCell('X1')->getValue() == 'IDREGISTRO' &&
		$tablaExcel->getActiveSheet()->getCell('Y1')->getValue() == 'IDREGISTROCAB' &&
		$tablaExcel->getActiveSheet()->getCell('Z1')->getValue() == 'USUARIOSOLICITUD' &&
		$tablaExcel->getActiveSheet()->getCell('AA1')->getValue() == 'USUARIOVALIDADOR' &&
		$tablaExcel->getActiveSheet()->getCell('AB1')->getValue() == 'IDTECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('AC1')->getValue() == 'IDTRANSPORTISTA' &&
		$tablaExcel->getActiveSheet()->getCell('AD1')->getValue() == 'IDCONTRATISTA' &&
		$tablaExcel->getActiveSheet()->getCell('AE1')->getValue() == 'ESTADO' &&
		$tablaExcel->getActiveSheet()->getCell('AF1')->getValue() == 'ESTADOREG' &&
		$tablaExcel->getActiveSheet()->getCell('AG1')->getValue() == 'USUARIO_SOLICITUD' &&
		$tablaExcel->getActiveSheet()->getCell('AH1')->getValue() == 'USUARIO_ATENCION' &&
		$tablaExcel->getActiveSheet()->getCell('AI1')->getValue() == 'USUARIO_VALIDACION' &&
		$tablaExcel->getActiveSheet()->getCell('AJ1')->getValue() == 'TIPODESPACHO' &&
		$tablaExcel->getActiveSheet()->getCell('AK1')->getValue() == 'PRECIOUNITARIO' &&
		$tablaExcel->getActiveSheet()->getCell('AL1')->getValue() == 'PRECIOTOTAL' &&
		$tablaExcel->getActiveSheet()->getCell('AM1')->getValue() == '') {
				
			//Importando EquipoMateriales

			echo '<table class="table table-responsive table-sm table-striped table-bordered table-hover">';

			$i = 2;
			$a = 1;	
			$products = array();
			$cantidadfilas = 0;

			foreach ($tablaExcel->setActiveSheetIndex(0)->getRowIterator() as $row) {
				$codigo = $tablaExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
				$descripcion = $tablaExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();

				if(!empty($codigo)) {
					if (!in_array($codigo, $products)) {
					    array_push($products, $codigo);

					    $sql = "SELECT id FROM equipomaterial WHERE codigo = '" . $codigo . "' AND idempresa = " . $idempresa;	
						$rs = $cado->ejecutarConsulta($sql);			

						if ($rs->rowCount() == 0) {	
							$cantidadfilas++;	
							if($a == 1) {echo "<tr>";$a++;} else {$a++;}
							echo "<td>";
							echo '<div class="form-group">
					                <input type="text" class="form-control input-sm" readonly="readonly" name="codsap' . $cantidadfilas . '" id="codsap' . $cantidadfilas . '" maxlength="80" value="' .$codigo.'">
					            </div>';
					        echo '<div class="form-group">
					                <input type="text" class="form-control input-sm" readonly="readonly" name="descrp' . $cantidadfilas . '" id="descrp' . $cantidadfilas . '" maxlength="80" value="'.str_replace('"', ' ', $descripcion).'">
					            </div>';
					        echo '<div class="form-group">
					        		<select class="form-control input-sm" name="tip' . $cantidadfilas . '" id="tip' . $cantidadfilas . '">
										<option value="1">EQUIPO</option>
										<option value="2">MATERIAL</option>
					        		</select>
					            </div>';

							echo "</td>";					
							if($a == 4) {$a = 1;echo "</tr>";} 						
				        }
					} 
					$i++;	
				} else {			
					break;			
				}				
			}
			echo '<input type="hidden" name="cantidadnuevosproducto" id="cantidadnuevosproducto" value="' . $cantidadfilas . '"></form>';
			if ($cantidadfilas != 0) {
				echo '<font style="color:blue">TIENES ' . $cantidadfilas . ' NUEVOS PRODUCTOS. ANTES DE IMPORTAR LAS GUÍAS, DEBES REGISTRARLOS CON EL TIPO DE PRODUCTO (MATERIAL O EQUIPO). DE LO CONTRARIO PERDERÁS INFORMACIÓN.</font><br><br><button type="button" class="btn btn-info" id="CargarNuevosProductos">Registrar Nuevos Productos</button><br><br>';
			} else {
				
				echo '<font style="color:red">NO SE ENCONTRARON NUEVOS EQUIPOS O MATERIALES.</font><br>';

				//Importando Guías de Remisión

				echo '<table class="table table-responsive table-sm table-striped table-bordered table-hover">';

				$i = 2;
				$a = 1;	
				$cantidadfilas = 0;
				foreach ($tablaExcel->setActiveSheetIndex(1)->getRowIterator() as $row) {
					$codigo_guia = $tablaExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
					if(!empty($codigo_guia)) {
						$fechaentrega = $tablaExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();

						//idregistro-idregistrocab

						$registro = $tablaExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue() . '-' . $tablaExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue();
						$fechaentrega = substr($fechaentrega, 0, 10);

						$sql = "SELECT id FROM guiaremision WHERE numero = '$codigo_guia' AND idempresa = " . $idempresa;	
						$rs = $cado->ejecutarConsulta($sql);	

						//Compruebo si no existe la guia, de lo contrario, no se hace nada

						if ($rs->rowCount() == 0) {			
							if($a == 1) {echo "<tr>";$a++;} else {$a++;}
							echo "<td>";

							//Inserto la guía de remisión

							$sql = "INSERT INTO guiaremision(numero, fecha, registro, estado, idempresa) VALUES('$codigo_guia','$fechaentrega', '$registro', 0, $idempresa)";
							$cado->ejecutarConsulta($sql);
							echo 'CÓDIGO DE GUÍA : ' .$codigo_guia.'<br>';
							echo 'FECHA DE ENTREGA: '.$fechaentrega.'<br>';
							echo "</td>";
							if($a == 4) {$a = 1;echo "</tr>";} 
							$cantidadfilas++;
				        }	
				        $i++;	
					} else {
						break;			
					}				
				}
				if ($cantidadfilas != 0) {
					echo '<font style="color:green">' . $cantidadfilas . ' GUIAS CORRECTAMENTE IMPORTADAS.</font><br>';
				} else {
					echo '<font style="color:red">NO SE REGISTRARON GUÍAS.</font><br>';
				}	

				$i = 2;

				//Importando Detalles No Seriados

				$cantidadfilas = 0;
				foreach ($tablaExcel->setActiveSheetIndex(1)->getRowIterator() as $row) {
					$codigo_guia = $tablaExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
					$codigo_material = $tablaExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
					$cantidad = $tablaExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
					$idunico = $tablaExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue();

					//Compruebo si el código no está en blanco. Si lo está quiere decir que ya no existen registros y dejamos de importar

					if(!empty($codigo_guia)) {

						//Obtenemos el id de guia remisión, el id del equipomaterial (equipo o material) y el idunico del detalle para no insertarlo dos veces

						$sql1 = "SELECT id FROM guiaremision WHERE numero = '$codigo_guia' AND idempresa = $idempresa";	
						$sql2 = "SELECT id, tipo FROM equipomaterial WHERE codigo = '$codigo_material' AND idempresa = $idempresa";
						$sql3 = "SELECT idunico FROM guiaremisionequipomaterial 
								INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision
								WHERE idunico = '$idunico' 
								AND idempresa = $idempresa";	
						$rs1 = $cado->ejecutarConsulta($sql1);			
						$rs2 = $cado->ejecutarConsulta($sql2);	
						$rs3 = $cado->ejecutarConsulta($sql3);	

						//Solo si el idunico no existe hacemos las operaciones

						if ($rs3->rowCount() == 0) {

							//Solo si existe la guía de remisión y el equipomaterial a importar

							if ($rs1->rowCount() != 0 && $rs2->rowCount() != 0) {	
								foreach ($rs2 as $row) {$id_material = $row['id'];$tipo = $row['tipo'];break;}

								//Solo si es tipo 2 o material

								if($tipo == 2) {
									foreach ($rs1 as $row) {$id_guia = $row['id'];break;}

									//Insertamos el detalle de la guía de remisión

									$sql3 = "INSERT INTO guiaremisionequipomaterial(idguiaremision, idequipomaterial, cantidad, idunico) VALUES($id_guia, $id_material, $cantidad, '" . $idunico . "')";
									$rs3 = $cado->ejecutarConsulta($sql3);

									//Actualizamos el Stock de este material

									$sql4 = 'UPDATE equipomaterial SET stock = (stock + ' . $cantidad . ') WHERE equipomaterial.id = ' . $id_material . ' AND idempresa = ' . $idempresa;
									$rs4 = $cado->ejecutarConsulta($sql4);
									$cantidadfilas++;
								}						
							}
						}	
						$i++;	
					} else {			
						break;			
					}
				}

				if ($cantidadfilas != 0) {
					echo '<font style="color:green">' . $cantidadfilas . ' MATERIALES CORRECTAMENTE IMPORTADOS.</font><br>';
				} else {
					echo '<font style="color:red">NO SE REGISTRARON MATERIALES.</font><br>';
				}

				$i = 2;
				$cantidadfilas = 0;

				//Importando Detalles Seriados

				foreach ($tablaExcel->setActiveSheetIndex(2)->getRowIterator() as $row) {
					$codigo_guia = $tablaExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
					$codigo_equipo = $tablaExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
					$idserie = $tablaExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
					$serie = $tablaExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue() . '/' . $idserie;

					if(!empty($codigo_guia)) {

						//Comprobamos si existe la guía de remisión, el equipomaterial y el idunico del detalle de la guía

						$sql1 = "SELECT id FROM guiaremision WHERE numero = '$codigo_guia' AND idempresa = $idempresa";	
						$sql2 = "SELECT id FROM equipomaterial WHERE codigo = '$codigo_equipo' AND idempresa = $idempresa";	
						$sql3 = "SELECT idunico 
								FROM guiaremisionequipomaterial 
								INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision
								WHERE idunico = '$idserie'
								AND idempresa = $idempresa";
						$rs1 = $cado->ejecutarConsulta($sql1);			
						$rs2 = $cado->ejecutarConsulta($sql2);	
						$rs3 = $cado->ejecutarConsulta($sql3);	

						//Solo si no existe el idunico vamos a importar el equipo

						if ($rs3->rowCount() == 0) {

							//Solo si existe la guiaremision y el equipomaterial

							if ($rs1->rowCount() != 0 && $rs2->rowCount() != 0) {	
								foreach ($rs1 as $row) {$id_guia = $row['id'];break;}
								foreach ($rs2 as $row) {$id_equipo = $row['id'];break;}

								//Insertamos el detalle de la guía de remisión

								$sql3 = "INSERT INTO guiaremisionequipomaterial(idguiaremision, idequipomaterial, cantidad, serie, estado, idunico) VALUES($id_guia, $id_equipo, 1, '$serie', 'A', '$idserie')";
								$rs3 = $cado->ejecutarConsulta($sql3);

								//Actualizamos el stock del equipo

								$sql4 = 'UPDATE equipomaterial SET stock = (stock + 1) WHERE equipomaterial.id =' . $id_equipo . ' AND idempresa = ' . $idempresa;
								$rs4 = $cado->ejecutarConsulta($sql4);
								$cantidadfilas++;
							}
						}
						$i++;		
					} else {			
						break;			
					}		
				}

				if ($cantidadfilas != 0) {
					echo '<font style="color:green">' . $cantidadfilas . ' EQUIPOS CORRECTAMENTE IMPORTADOS.</font><br><br>';
				} else {
					echo '<font style="color:red">NO SE REGISTRARON EQUIPOS.</font><br><br>';
				}	
			}
		} else {
			echo '<font style="color:red">NO ES EL FORMATO CORRECTO PARA IMPORTAR EQUIPOS NI MATERIALES.</font><br><br>';
		}
		unlink($archivo_guardado);
	} catch (Exception $e) {
		echo '<font style="color:red">OCURRIÓ UN ERROR AL IMPORTAR.</font>';
	}	
}

////////////////////////////////////////////////////INSTALACIONES

if($tabla == 'previoInstalaciones') {
	try {
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		$nom_archivo_a_guardar = $_FILES['fileExcel']['name'];
		$archivo_copiado = $_FILES['fileExcel']['tmp_name'];
		$archivo_guardado = 'copia_' . $nom_archivo_a_guardar;

		copy($archivo_copiado, $archivo_guardado);

		$tablaExcel = ArchivoExcel::RecuperarTablaDeExcel($archivo_guardado);

		$tablaExcel->setActiveSheetIndex(0);

		//Comprobar si el archivo es del formato correcto

		if($tablaExcel->getActiveSheet()->getCell('A1')->getValue() == 'EECC' &&
		$tablaExcel->getActiveSheet()->getCell('B1')->getValue() == 'ZONAL' &&
		$tablaExcel->getActiveSheet()->getCell('C1')->getValue() == 'CENTRO' &&
		$tablaExcel->getActiveSheet()->getCell('D1')->getValue() == 'ALM' &&
		$tablaExcel->getActiveSheet()->getCell('E1')->getValue() == 'MES' &&
		$tablaExcel->getActiveSheet()->getCell('F1')->getValue() == 'NEGOCIO1' &&
		$tablaExcel->getActiveSheet()->getCell('G1')->getValue() == 'NEGOCIO2' &&
		$tablaExcel->getActiveSheet()->getCell('H1')->getValue() == 'REQ-O/S' &&
		$tablaExcel->getActiveSheet()->getCell('I1')->getValue() == 'TELEFONO' &&
		$tablaExcel->getActiveSheet()->getCell('J1')->getValue() == 'PETICION' &&
		$tablaExcel->getActiveSheet()->getCell('K1')->getValue() == 'FECHA_LIQUIDACION' &&
		$tablaExcel->getActiveSheet()->getCell('L1')->getValue() == 'CODIGO SAP 4.7' &&
		$tablaExcel->getActiveSheet()->getCell('M1')->getValue() == 'CODIGO SAP 7.3' &&
		$tablaExcel->getActiveSheet()->getCell('N1')->getValue() == 'DESCRIPCION' &&
		$tablaExcel->getActiveSheet()->getCell('O1')->getValue() == 'SERIEALTA' &&
		$tablaExcel->getActiveSheet()->getCell('P1')->getValue() == 'SERIEBAJA' &&
		$tablaExcel->getActiveSheet()->getCell('Q1')->getValue() == 'CANTIDAD' &&
		$tablaExcel->getActiveSheet()->getCell('R1')->getValue() == 'UM' &&
		$tablaExcel->getActiveSheet()->getCell('S1')->getValue() == 'ACTIVIDAD' &&
		$tablaExcel->getActiveSheet()->getCell('T1')->getValue() == 'PREFIJO' &&
		$tablaExcel->getActiveSheet()->getCell('U1')->getValue() == 'CLASE SERVICIO' &&
		$tablaExcel->getActiveSheet()->getCell('V1')->getValue() == 'O/T' &&
		$tablaExcel->getActiveSheet()->getCell('W1')->getValue() == 'OBSERVACIONES' &&
		$tablaExcel->getActiveSheet()->getCell('X1')->getValue() == 'CODSERV_CODMOTV' &&
		$tablaExcel->getActiveSheet()->getCell('Y1')->getValue() == 'CARNET' &&
		$tablaExcel->getActiveSheet()->getCell('Z1')->getValue() == 'CONTRATISTA' &&
		$tablaExcel->getActiveSheet()->getCell('AA1')->getValue() == 'ESTADO' &&
		$tablaExcel->getActiveSheet()->getCell('AB1')->getValue() == 'COD_LIQ' &&
		$tablaExcel->getActiveSheet()->getCell('AC1')->getValue() == 'COD_DET' &&
		$tablaExcel->getActiveSheet()->getCell('AD1')->getValue() == 'DESCRIPCIONLIQ' &&
		$tablaExcel->getActiveSheet()->getCell('AE1')->getValue() == 'CONDICION_LIQ' &&
		$tablaExcel->getActiveSheet()->getCell('AF1')->getValue() == 'CONDICION' &&
		$tablaExcel->getActiveSheet()->getCell('AG1')->getValue() == 'ATENDIDO' &&
		$tablaExcel->getActiveSheet()->getCell('AH1')->getValue() == 'INSTALADOS' &&
		$tablaExcel->getActiveSheet()->getCell('AI1')->getValue() == 'SUPERVISION' &&
		$tablaExcel->getActiveSheet()->getCell('AJ1')->getValue() == 'RANGO' &&
		$tablaExcel->getActiveSheet()->getCell('AK1')->getValue() == 'CLASE_SERVICIO' &&
		$tablaExcel->getActiveSheet()->getCell('AL1')->getValue() == 'MDF' &&
		$tablaExcel->getActiveSheet()->getCell('AM1')->getValue() == 'IDTECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('AN1')->getValue() == 'TECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('AO1')->getValue() == 'IDUSUARIO' &&
		$tablaExcel->getActiveSheet()->getCell('AP1')->getValue() == 'USUARIO' &&
		$tablaExcel->getActiveSheet()->getCell('AQ1')->getValue() == 'REPOSICIONMATERIAL' &&
		$tablaExcel->getActiveSheet()->getCell('AR1')->getValue() == 'ESTADOORDEN' &&
		$tablaExcel->getActiveSheet()->getCell('AS1')->getValue() == 'ESTADOREGISTRO' &&
		$tablaExcel->getActiveSheet()->getCell('AT1')->getValue() == 'FECHAREGISTRO' &&
		$tablaExcel->getActiveSheet()->getCell('AU1')->getValue() == 'COD_LIQ_WEB' &&
		$tablaExcel->getActiveSheet()->getCell('AV1')->getValue() == 'COD_DET_WEB' &&
		$tablaExcel->getActiveSheet()->getCell('AW1')->getValue() == 'DESCRIPCIONLIQ_WEB' &&
		$tablaExcel->getActiveSheet()->getCell('AX1')->getValue() == 'CONDICION_LIQ_WEB' &&
		$tablaExcel->getActiveSheet()->getCell('AY1')->getValue() == 'IDORDEN' && 
		$tablaExcel->getActiveSheet()->getCell('AZ1')->getValue() == '') {

			//Importando EquipoMateriales

			$i = 2;
			$ordenes = array();
			$cantidadfilas = 0;

			$retorno = '<hr>';

			foreach ($tablaExcel->setActiveSheetIndex(0)->getRowIterator() as $row) {
				$orden = $tablaExcel->getActiveSheet()->getCell('H' . $i)->getValue();
				
				//Mostrando Instalaciones

				if(!empty($orden)) {
					$fecha_liquidacion = $tablaExcel->getActiveSheet()->getCell('K' . $i)->getValue();
					$dia = substr($fecha_liquidacion, 0, 2);
					$mes = substr($fecha_liquidacion, 3, 2);
					$ano = substr($fecha_liquidacion, 6, 4);
					$fecha_liquidacion = $ano . '-' . $mes . '-' . $dia;
					$observacion = $tablaExcel->getActiveSheet()->getCell('W' . $i)->getValue();
					$carnet_tecnico = $tablaExcel->getActiveSheet()->getCell('Y' . $i)->getValue();
					$serie = $tablaExcel->getActiveSheet()->getCell('O' . $i)->getValue();
					$sap = $tablaExcel->getActiveSheet()->getCell('L' . $i)->getValue();
					$cantidad = $tablaExcel->getActiveSheet()->getCell('Q' . $i)->getValue();

					if (!in_array($orden, $ordenes)) {
						$sql = 'SELECT id FROM instalacion WHERE orden="' . $orden . '" AND idempresa = ' . $idempresa;
						$rs = $cado->ejecutarConsulta($sql);	
						if ($rs->rowCount() == 0) {
							$sql = 'SELECT persona.id, CONCAT(nombres, " ", apellidos) AS nombre 
									FROM persona 
									INNER JOIN usuario ON usuario.idpersona = persona.id
									WHERE id_AB="' . $carnet_tecnico . '"
									AND usuario.idempresa = ' . $idempresa;
							$rs = $cado->ejecutarConsulta($sql);	
							if ($rs->rowCount() != 0) {
								$estado = 'N';
								if ($sap != 'S/C') { $estado = 'C'; }
								foreach ($rs as $r) { $tecnico = $r['nombre']; $idtecnico = $r['id'];  break; }

								$retorno .= '<div class="col-md-6">
									<p>ORDEN: ' . $orden . '</p>
									<p>FECHA LIQUIDACIÓN: ' . $fecha_liquidacion . '</p>
									<p>TECNICO 1: ' . $tecnico . '</p>
									<p>TECNICO 2: ';

								$sql2 = "SELECT p.id AS idpersona, CONCAT(p.nombres, ' ', p.apellidos) AS nombre FROM usuario u INNER JOIN persona p ON u.idpersona = p.id WHERE u.tipo=2 AND u.idempresa = " . $idempresa;
								$rs2 = $cado->ejecutarConsulta($sql2);

								$retorno .= '<select name="tec2' . $i . '" id="tec2' . $i . '" class="form-control input-sm tecnicoadicional">';

								$retorno .= '<option value="0">No tiene</option>';

								foreach ($rs2 as $row2) {
									if($idtecnico != $row2['idpersona']) {
										$retorno .= '<option value="' . $row2['idpersona'] . '">' . $row2['nombre'] . '</option>';
									}									
								}
								$retorno .= '</select></p>';

								$retorno .= '<p>TECNICO 3: <select name="tec3' . $i . '" id="tec3' . $i . '" class="form-control input-sm tecnicoadicional">';

								$retorno .= '<option value="0">No tiene</option>';

								foreach ($rs2 as $row2) {
									if($idtecnico != $row2['idpersona']) {
										$retorno .= '<option value="' . $row2['idpersona'] . '">' . $row2['nombre'] . '</option>';
									}									
								}
								$retorno .= '</select></p><hr></div>';
							}
							array_push($ordenes, $orden);
							$cantidadfilas++;
						}						
				    }
				    $i++; 			
				} else {
					if($cantidadfilas == 0) {
						echo '<font style="color:blue">NO HAY INSTALACIONES PARA IMPORTAR.</font><br><br>';
					}				
					break;
				}					
			}
			echo $retorno;
		} else {
			echo '<font style="color:red">NO ES EL FORMATO CORRECTO PARA IMPORTAR LIQUIDACIONES.</font><br><br>';
		}			
		unlink($archivo_guardado);
	} catch (Exception $e) {
		echo '<font style="color:red">OCURRIÓ UN ERROR AL CARGAR INSTALACIONES.</font>';
	}
}

if($tabla == 'registroInstalaciones') {
	try {
		////////////////////////////
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		$nom_archivo_a_guardar = $_FILES['fileExcel']['name'];
		$archivo_copiado = $_FILES['fileExcel']['tmp_name'];
		$archivo_guardado = 'copia_' . $nom_archivo_a_guardar;

		copy($archivo_copiado, $archivo_guardado);

		$tablaExcel = ArchivoExcel::RecuperarTablaDeExcel($archivo_guardado);

		//Comprobar si el archivo es del formato correcto

		if($tablaExcel->getActiveSheet()->getCell('A1')->getValue() == 'EECC' &&
		$tablaExcel->getActiveSheet()->getCell('B1')->getValue() == 'ZONAL' &&
		$tablaExcel->getActiveSheet()->getCell('C1')->getValue() == 'CENTRO' &&
		$tablaExcel->getActiveSheet()->getCell('D1')->getValue() == 'ALM' &&
		$tablaExcel->getActiveSheet()->getCell('E1')->getValue() == 'MES' &&
		$tablaExcel->getActiveSheet()->getCell('F1')->getValue() == 'NEGOCIO1' &&
		$tablaExcel->getActiveSheet()->getCell('G1')->getValue() == 'NEGOCIO2' &&
		$tablaExcel->getActiveSheet()->getCell('H1')->getValue() == 'REQ-O/S' &&
		$tablaExcel->getActiveSheet()->getCell('I1')->getValue() == 'TELEFONO' &&
		$tablaExcel->getActiveSheet()->getCell('J1')->getValue() == 'PETICION' &&
		$tablaExcel->getActiveSheet()->getCell('K1')->getValue() == 'FECHA_LIQUIDACION' &&
		$tablaExcel->getActiveSheet()->getCell('L1')->getValue() == 'CODIGO SAP 4.7' &&
		$tablaExcel->getActiveSheet()->getCell('M1')->getValue() == 'CODIGO SAP 7.3' &&
		$tablaExcel->getActiveSheet()->getCell('N1')->getValue() == 'DESCRIPCION' &&
		$tablaExcel->getActiveSheet()->getCell('O1')->getValue() == 'SERIEALTA' &&
		$tablaExcel->getActiveSheet()->getCell('P1')->getValue() == 'SERIEBAJA' &&
		$tablaExcel->getActiveSheet()->getCell('Q1')->getValue() == 'CANTIDAD' &&
		$tablaExcel->getActiveSheet()->getCell('R1')->getValue() == 'UM' &&
		$tablaExcel->getActiveSheet()->getCell('S1')->getValue() == 'ACTIVIDAD' &&
		$tablaExcel->getActiveSheet()->getCell('T1')->getValue() == 'PREFIJO' &&
		$tablaExcel->getActiveSheet()->getCell('U1')->getValue() == 'CLASE SERVICIO' &&
		$tablaExcel->getActiveSheet()->getCell('V1')->getValue() == 'O/T' &&
		$tablaExcel->getActiveSheet()->getCell('W1')->getValue() == 'OBSERVACIONES' &&
		$tablaExcel->getActiveSheet()->getCell('X1')->getValue() == 'CODSERV_CODMOTV' &&
		$tablaExcel->getActiveSheet()->getCell('Y1')->getValue() == 'CARNET' &&
		$tablaExcel->getActiveSheet()->getCell('Z1')->getValue() == 'CONTRATISTA' &&
		$tablaExcel->getActiveSheet()->getCell('AA1')->getValue() == 'ESTADO' &&
		$tablaExcel->getActiveSheet()->getCell('AB1')->getValue() == 'COD_LIQ' &&
		$tablaExcel->getActiveSheet()->getCell('AC1')->getValue() == 'COD_DET' &&
		$tablaExcel->getActiveSheet()->getCell('AD1')->getValue() == 'DESCRIPCIONLIQ' &&
		$tablaExcel->getActiveSheet()->getCell('AE1')->getValue() == 'CONDICION_LIQ' &&
		$tablaExcel->getActiveSheet()->getCell('AF1')->getValue() == 'CONDICION' &&
		$tablaExcel->getActiveSheet()->getCell('AG1')->getValue() == 'ATENDIDO' &&
		$tablaExcel->getActiveSheet()->getCell('AH1')->getValue() == 'INSTALADOS' &&
		$tablaExcel->getActiveSheet()->getCell('AI1')->getValue() == 'SUPERVISION' &&
		$tablaExcel->getActiveSheet()->getCell('AJ1')->getValue() == 'RANGO' &&
		$tablaExcel->getActiveSheet()->getCell('AK1')->getValue() == 'CLASE_SERVICIO' &&
		$tablaExcel->getActiveSheet()->getCell('AL1')->getValue() == 'MDF' &&
		$tablaExcel->getActiveSheet()->getCell('AM1')->getValue() == 'IDTECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('AN1')->getValue() == 'TECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('AO1')->getValue() == 'IDUSUARIO' &&
		$tablaExcel->getActiveSheet()->getCell('AP1')->getValue() == 'USUARIO' &&
		$tablaExcel->getActiveSheet()->getCell('AQ1')->getValue() == 'REPOSICIONMATERIAL' &&
		$tablaExcel->getActiveSheet()->getCell('AR1')->getValue() == 'ESTADOORDEN' &&
		$tablaExcel->getActiveSheet()->getCell('AS1')->getValue() == 'ESTADOREGISTRO' &&
		$tablaExcel->getActiveSheet()->getCell('AT1')->getValue() == 'FECHAREGISTRO' &&
		$tablaExcel->getActiveSheet()->getCell('AU1')->getValue() == 'COD_LIQ_WEB' &&
		$tablaExcel->getActiveSheet()->getCell('AV1')->getValue() == 'COD_DET_WEB' &&
		$tablaExcel->getActiveSheet()->getCell('AW1')->getValue() == 'DESCRIPCIONLIQ_WEB' &&
		$tablaExcel->getActiveSheet()->getCell('AX1')->getValue() == 'CONDICION_LIQ_WEB' &&
		$tablaExcel->getActiveSheet()->getCell('AY1')->getValue() == 'IDORDEN' && 
		$tablaExcel->getActiveSheet()->getCell('AZ1')->getValue() == '') {

			//Importando EquipoMateriales

			$i = 2;
			$ordenes = array();
			$cantidadfilas = 0;

			foreach ($tablaExcel->setActiveSheetIndex(0)->getRowIterator() as $row) {
				$orden = $tablaExcel->getActiveSheet()->getCell('H' . $i)->getValue();
				if(isset($_GET['tec2' . $i])) {
					$idtecnico2 = $_GET['tec2' . $i];
				}

				if(isset($_GET['tec3' . $i])) {
					$idtecnico3 = $_GET['tec3' . $i];
				}
				//Importando Instalaciones 

				if(!empty($orden)) {
					$fecha_liquidacion = $tablaExcel->getActiveSheet()->getCell('K' . $i)->getValue();
					$dia = substr($fecha_liquidacion, 0, 2);
					$mes = substr($fecha_liquidacion, 3, 2);
					$ano = substr($fecha_liquidacion, 6, 4);
					$fecha_liquidacion = $ano . '-' . $mes . '-' . $dia;
					$observacion = $tablaExcel->getActiveSheet()->getCell('W' . $i)->getValue();
					$observacion = substr($observacion, 12);
					$carnet_tecnico = $tablaExcel->getActiveSheet()->getCell('Y' . $i)->getValue();
					$serie = $tablaExcel->getActiveSheet()->getCell('O' . $i)->getValue();
					$sap = $tablaExcel->getActiveSheet()->getCell('L' . $i)->getValue();
					$desc = $tablaExcel->getActiveSheet()->getCell('N' . $i)->getValue();

					//CANTIDAD REAL

					$cantidad = $tablaExcel->getActiveSheet()->getCell('Q' . $i)->getValue();

					//CANTIDAD COBRA

					$cantidadcobra = $tablaExcel->getActiveSheet()->getCell('AH' . $i)->getValue();

					//O/T

					$ot = $tablaExcel->getActiveSheet()->getCell('V' . $i)->getValue();

					//REQ-O/S

					$reqos = $tablaExcel->getActiveSheet()->getCell('H' . $i)->getValue();

					//TELEFONO

					$telefono = $tablaExcel->getActiveSheet()->getCell('I' . $i)->getValue();
					$actividad = $tablaExcel->getActiveSheet()->getCell('S' . $i)->getValue();
					$prefijo = $tablaExcel->getActiveSheet()->getCell('T' . $i)->getValue();
					
					if (!in_array($orden, $ordenes)) {
						$sql = 'SELECT id FROM instalacion WHERE orden="' . $orden . '" AND idempresa = ' . $idempresa;
						$rs = $cado->ejecutarConsulta($sql);	
						if ($rs->rowCount() == 0) {
							$sql = 'SELECT persona.id FROM persona
									INNER JOIN usuario ON usuario.idpersona = persona.id
									WHERE id_AB="' . $carnet_tecnico . '"
									AND usuario.idempresa = ' . $idempresa;
							$rs = $cado->ejecutarConsulta($sql);	
							if ($rs->rowCount() != 0) {
								$estado = 'N';
								if ($sap != 'S/C') { $estado = 'C'; }
								foreach ($rs as $r) { $idtecnico = $r['id']; break; }

								$sql = 'INSERT INTO instalacion(orden, ot, reqos, telefono, fecha_liquidacion, estado, observacion, actividad, prefijo, idtecnico, idtecnico2, idtecnico3, idempresa) VALUES("' . $orden . '", "' . $ot . '", "' . $reqos . '", "' . $telefono . '", "' . $fecha_liquidacion . '", "' . $estado . '", "' . $observacion . '", "' . $actividad . '", "' . $prefijo . '", ' . $idtecnico . ', ' . $idtecnico2 . ', ' . $idtecnico3 . ', ' . $idempresa . ')';
								$cado->ejecutarConsulta($sql);
							}
							array_push($ordenes, $orden);
							$cantidadfilas++;
						}						
				    }

				    //Importando Detalles de Instalaciones solo si existen	

				    if($sap != 'S/C') {	

				    	//Compruebo si existe el equipomaterial

				    	$sqlc = 'SELECT id FROM equipomaterial WHERE codigo = "' . $sap . '" AND idempresa = ' . $idempresa;	
				    	$rs_ = $cado->ejecutarConsulta($sqlc);	

				    	//Si existe serie, es equipo
				    	//Si no existe serie, es material

				    	if(strlen($serie) > 1) {

				    		//Comprobar solo si el equipo estuvo asignado

					    	$sql = 'SELECT guiaremisionequipomaterial.id FROM guiaremisionequipomaterial INNER JOIN equipomaterial WHERE serie LIKE "%' . $serie . '%" AND guiaremisionequipomaterial.estado = "T" AND equipomaterial.idempresa = ' . $idempresa;
							$rs = $cado->ejecutarConsulta($sql);

							//Si no está, se supone que no se ha insertado el detalle de instalacion

							if ($rs->rowCount() != 0 && $rs_->rowCount() != 0) { 

								//Insertar detalle de instalacion

								$sql = 'INSERT INTO instalacionequipomaterial(idequipomaterial, idinstalacion, cantidad, cantidadcobra) 
									VALUES(CONCAT("S",(SELECT guiaremisionequipomaterial.id FROM guiaremisionequipomaterial INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE serie LIKE "%' . $serie . '%" AND guiaremision.idempresa = ' . $idempresa . ')), 
									(SELECT id FROM instalacion WHERE orden="' . $orden . '" AND instalacion.idempresa = ' . $idempresa . '), 
									' . $cantidad . ', 
									' . $cantidadcobra . ')';
								//echo '--> ' . $sql . '<br>';
								$rs = $cado->ejecutarConsulta($sql);

								//Cambiamos estado de guiaremisionequipomaterial

								$sql = 'SELECT guiaremisionequipomaterial.id FROM guiaremisionequipomaterial INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE serie LIKE "%' . $serie . '%" AND guiaremision.idempresa = ' . $idempresa;
								$rs = $cado->ejecutarConsulta($sql);

								if($rs->rowCount() > 0 ) {
									foreach ($rs as $row) {
										$iid = $row['id'];
										break;
									}
									$sql = 'UPDATE guiaremisionequipomaterial SET estado = "I" WHERE id = "' . $iid . '"';
									$rs = $cado->ejecutarConsulta($sql);
								}									
							}
						} else {
 
							//Compruebo si no se ha insertado el detalle

							$sql = 'SELECT id FROM instalacionequipomaterial WHERE idequipomaterial = CONCAT("E",(SELECT id FROM equipomaterial WHERE codigo = "' . $sap . '" AND idempresa = ' . $idempresa . ' LIMIT 1)) AND idinstalacion = (SELECT id FROM instalacion WHERE orden="' . $orden . '" AND idempresa = ' . $idempresa . ' LIMIT 1)';
							//echo 'IDINSTAL ' . $sql . '<br>';
							$rs = $cado->ejecutarConsulta($sql);

							//Si no está, se supone que no se ha insertado la instalacionequipomaterial	

							if ($rs->rowCount() == 0 
								&& $rs_->rowCount() != 0) { 

								//Insertar instalacionequipomaterial

								$sql = 'INSERT INTO instalacionequipomaterial(idequipomaterial, idinstalacion, cantidad, cantidadcobra) VALUES(CONCAT("E", (SELECT id FROM equipomaterial WHERE (codigo LIKE "%' . $sap . '%" OR descripcion = "' . addcslashes($desc, '"') . '") AND idempresa = ' . $idempresa . ' LIMIT 1)), (SELECT id FROM instalacion WHERE orden="' . $orden . '" AND idempresa = ' . $idempresa . ' LIMIT 1), ' . $cantidad . ', ' . $cantidadcobra . ')';
								//echo '---> ' . $sql . '<br>';
								$rs = $cado->ejecutarConsulta($sql);											
							}
						}

						//Ya no se reduce, pues en las asignaciones ya se redujeron

						//Reducimos el Stock de Asignación Resumen solo si existe

						$sql = 'SELECT resasignacionequipomaterial.id 
								FROM resasignacion 
								INNER JOIN resasignacionequipomaterial ON resasignacionequipomaterial.idresasignacion = resasignacion.id
								WHERE idtecnico = (SELECT persona.id 
													FROM persona 
													INNER JOIN usuario ON usuario.idpersona = persona.id 
													WHERE id_AB = "' . $carnet_tecnico . '" 
													AND usuario.idempresa = ' . $idempresa . ') 
								AND idequipomaterial = (SELECT id 
													FROM equipomaterial 
													WHERE codigo = "' . $sap. '" 
													AND equipomaterial.idempresa = ' . $idempresa . ' LIMIT 1)';
				        $resultado = $cado->ejecutarConsulta($sql);
				        if($resultado->rowCount() != 0) {
				        	foreach ($resultado as $row) {
				        		$iid = $row['id'];
				        		break;
				        	}
				        	$sql = 'UPDATE resasignacionequipomaterial 
				        			SET cantidad = (CASE WHEN cantidad >= ' . $cantidad . ' THEN (cantidad - ' . $cantidad . ') ELSE cantidad END) 
				        			WHERE id = ' . $iid;
							$rs = $cado->ejecutarConsulta($sql);
				        } else {
				        	$sql = 'SELECT id FROM resasignacion WHERE idtecnico = (SELECT id FROM persona WHERE id_AB = "' . $carnet_tecnico . '")';
				        	$rs = $cado->ejecutarConsulta($sql);

				        	if($rs->rowCount() == 0) {
				        		$sql = 'INSERT INTO resasignacion(idtecnico) VALUES((SELECT id FROM persona WHERE id_AB = "' . $carnet_tecnico . '"))';
				        		$rs = $cado->ejecutarConsulta($sql);
				        	}

				        	$sql = "INSERT INTO resasignacionequipomaterial(idresasignacion, idequipomaterial, cantidad) VALUES(
				        		(SELECT id FROM resasignacion WHERE idtecnico = (SELECT id FROM persona WHERE id_AB = '" . $carnet_tecnico . "')),
				        		(SELECT id FROM equipomaterial WHERE codigo = '" . $sap. "' AND idempresa = " . $idempresa . " LIMIT 1), 
				        		0)";
				        	$rs = $cado->ejecutarConsulta($sql);

				        	$sql = 'UPDATE resasignacionequipomaterial 
				        			SET cantidad = (CASE WHEN cantidad >= ' . $cantidad . ' THEN (cantidad - ' . $cantidad . ') ELSE cantidad END) 
				        			WHERE idresasignacion = (SELECT id FROM resasignacion WHERE idtecnico = (SELECT id FROM persona WHERE id_AB = "' . $carnet_tecnico . '")) 
				        			AND idequipomaterial = (SELECT id FROM equipomaterial WHERE codigo = "' . $sap . '" AND idempresa = ' . $idempresa . ' LIMIT 1)';
							$rs = $cado->ejecutarConsulta($sql);
				        }								
				    }
				    $i++; 			
				} else {
					break;
				}						
			}

			if ($cantidadfilas != 0) {
				echo '<font style="color:green">' . $cantidadfilas . ' INSTALACIONES CORRECTAMENTE IMPORTADOS.</font><br>';
			} else {
				echo '<font style="color:red">NO SE REGISTRARON INSTALACIONES.</font><br>';
			}
		} else {
			echo '<font style="color:red">NO ES EL FORMATO CORRECTO PARA IMPORTAR LIQUIDACIONES.</font><br><br>';
		}
		unlink($archivo_guardado);
	} catch (Exception $e) {
		echo '<font style="color:red">OCURRIÓ UN ERROR AL IMPORTAR.</font>';
	}
}

////////////////////////////////////////////////////DEVOLUCIONES

if($tabla == 'Devoluciones') {
	try {
		set_time_limit(0);
		$nom_archivo_a_guardar = $_FILES['fileExcel']['name'];
		$archivo_copiado = $_FILES['fileExcel']['tmp_name'];
		$archivo_guardado = 'copia_' . $nom_archivo_a_guardar;

		copy($archivo_copiado, $archivo_guardado);

		$tablaExcel = ArchivoExcel::RecuperarTablaDeExcel($archivo_guardado);

		$tablaExcel->setActiveSheetIndex(1);

		//Comprobar si el archivo es del formato correcto

		if($tablaExcel->getActiveSheet()->getCell('A1')->getValue() == 'ZONAL' &&
		$tablaExcel->getActiveSheet()->getCell('B1')->getValue() == 'MOVIMIENTO' &&
		$tablaExcel->getActiveSheet()->getCell('C1')->getValue() == 'NRO_GUIA' &&
		$tablaExcel->getActiveSheet()->getCell('D1')->getValue() == 'OBSERVACIONES' &&
		$tablaExcel->getActiveSheet()->getCell('E1')->getValue() == 'FECHA_SOLICITUD' &&
		$tablaExcel->getActiveSheet()->getCell('F1')->getValue() == 'FECHA_ATENCION' &&
		$tablaExcel->getActiveSheet()->getCell('G1')->getValue() == 'FECHA_VALIDACION' &&
		$tablaExcel->getActiveSheet()->getCell('H1')->getValue() == 'POSICION' &&
		$tablaExcel->getActiveSheet()->getCell('I1')->getValue() == 'CODIGO_SAP' &&
		$tablaExcel->getActiveSheet()->getCell('J1')->getValue() == 'DESCRIPCION' &&
		$tablaExcel->getActiveSheet()->getCell('K1')->getValue() == 'UNIDAD' &&
		$tablaExcel->getActiveSheet()->getCell('L1')->getValue() == 'CANTIDAD' &&
		$tablaExcel->getActiveSheet()->getCell('M1')->getValue() == 'LOTE' &&
		$tablaExcel->getActiveSheet()->getCell('N1')->getValue() == 'IND_SB' &&
		$tablaExcel->getActiveSheet()->getCell('O1')->getValue() == 'COD_ALM' &&
		$tablaExcel->getActiveSheet()->getCell('P1')->getValue() == 'FECHA_VALIDACIONSAP' &&
		$tablaExcel->getActiveSheet()->getCell('Q1')->getValue() == 'RESERVA' &&
		$tablaExcel->getActiveSheet()->getCell('R1')->getValue() == 'MES' &&
		$tablaExcel->getActiveSheet()->getCell('S1')->getValue() == 'ANIO' &&
		$tablaExcel->getActiveSheet()->getCell('T1')->getValue() == 'CARNET' &&
		$tablaExcel->getActiveSheet()->getCell('U1')->getValue() == 'DNI_TECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('V1')->getValue() == 'TECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('W1')->getValue() == 'CONTRATISTA' &&
		$tablaExcel->getActiveSheet()->getCell('X1')->getValue() == 'IDREGISTRO' &&
		$tablaExcel->getActiveSheet()->getCell('Y1')->getValue() == 'IDREGISTROCAB' &&
		$tablaExcel->getActiveSheet()->getCell('Z1')->getValue() == 'USUARIOSOLICITUD' &&
		$tablaExcel->getActiveSheet()->getCell('AA1')->getValue() == 'USUARIOVALIDADOR' &&
		$tablaExcel->getActiveSheet()->getCell('AB1')->getValue() == 'IDTECNICO' &&
		$tablaExcel->getActiveSheet()->getCell('AC1')->getValue() == 'IDTRANSPORTISTA' &&
		$tablaExcel->getActiveSheet()->getCell('AD1')->getValue() == 'IDCONTRATISTA' &&
		$tablaExcel->getActiveSheet()->getCell('AE1')->getValue() == 'ESTADO' &&
		$tablaExcel->getActiveSheet()->getCell('AF1')->getValue() == 'ESTADOREG' &&
		$tablaExcel->getActiveSheet()->getCell('AG1')->getValue() == 'USUARIO_SOLICITUD' &&
		$tablaExcel->getActiveSheet()->getCell('AH1')->getValue() == 'USUARIO_ATENCION' &&
		$tablaExcel->getActiveSheet()->getCell('AI1')->getValue() == 'USUARIO_VALIDACION' &&
		$tablaExcel->getActiveSheet()->getCell('AJ1')->getValue() == '') {
				
			//Importando Devoluciones			

			//SOLO PARA MATERIALES

			$i = 2;

			$cantidadfilas = 0;
			foreach ($tablaExcel->setActiveSheetIndex(1)->getRowIterator() as $row) {
				$campo = $tablaExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
				$sap = $tablaExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
				$observacion = $tablaExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
				$cantidad = $tablaExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
				$registro = $tablaExcel->getActiveSheet()->getCell('X' . $i)->getCalculatedValue() . '-' . $tablaExcel->getActiveSheet()->getCell('Y' . $i)->getCalculatedValue();

				//¿HAY FILAS DISPONIBLES?

				if(!empty($campo)) {					

					$sql = "SELECT id FROM devolucion WHERE registro = '$registro' AND idempresa = $idempresa";	
					$rs = $cado->ejecutarConsulta($sql);
					$sql2 = "SELECT id, tipo FROM equipomaterial WHERE codigo = '$sap' AND idempresa = $idempresa";	
					$rs2 = $cado->ejecutarConsulta($sql2);			

					//¿EXISTE REGISTRO?

					if ($rs->rowCount() == 0) {

						//¿EXISTE CODIGOSAP?

						if ($rs2->rowCount() != 0) {	

							foreach ($rs2 as $row) {
								$tipo = $row['tipo'];
								$idequipomaterial = $row['id'];
								break;
							}

							//SOLO SI ES TIPO 2 (MATERIAL)

							if ($tipo == 2) {

								//CREAR DEVOLUCION Y REDUCIR STOCK DE MATERIAL

								$sql = 'INSERT INTO devolucion(observacion, idequipomaterial, registro, cantidad, idempresa) VALUES("' . $observacion . '", ' . $idequipomaterial . ', "' . $registro . '", ' . $cantidad . ', ' . $idempresa . ')';
								$cado->ejecutarConsulta($sql);


								$sql = 'SELECT stock FROM equipomaterial WHERE id = ' . $idequipomaterial . ' AND idempresa = ' . $idempresa;
								$rs = $cado->ejecutarConsulta($sql);

								//Comprobar solo si la resta es mayor a cero, de lo contrario no reduciomos stock

								if ($rs->rowCount() == 0) {
									foreach ($rs as $row) {
										$stock = $row['stock'];
										break;
									}
									if($stock - $cantidad >= 0) {
										$sql2 = 'UPDATE equipomaterial SET stock = (stock - ' . $cantidad . ') WHERE id = ' . $idequipomaterial . ' AND idempresa = ' . $idempresa;
										$cado->ejecutarConsulta($sql2);
									}								
								}
								$cantidadfilas++;
							}
						}
					}	
					$i++;	
				} else {			
					break;			
				}
			}

			//SOLO PARA EQUIPOS

			foreach ($tablaExcel->setActiveSheetIndex(2)->getRowIterator() as $row) {
				$campo = $tablaExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
				$sap = $tablaExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
				$serie = $tablaExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
				$observacion = $tablaExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
				$registro = $tablaExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue() . '/' . $tablaExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();

				//¿HAY FILAS DISPONIBLES?

				if(!empty($campo)) {			

					$sql = "SELECT guiaremisionequipomaterial.id FROM guiaremisionequipomaterial INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE serie LIKE '%" . $serie . "%' AND guiaremisionequipomaterial.estado = 'D' AND guiaremision.idempresa = " . $idempresa;	
					$rs = $cado->ejecutarConsulta($sql);			

					//¿EXISTE SERIE Y ESTÁ DEVUELTA?

					if ($rs->rowCount() == 0) {	

						$sql = "SELECT id FROM equipomaterial WHERE codigo = '" . $sap . "' AND idempresa = " . $idempresa;
						$rs = $cado->ejecutarConsulta($sql);

						$sql2 = "SELECT guiaremisionequipomaterial.id FROM guiaremisionequipomaterial INNER JOIN guiaremision ON guiaremision.id = guiaremisionequipomaterial.idguiaremision WHERE serie LIKE '%" . $serie . "%' AND idempresa = " . $idempresa;
						$rs2 = $cado->ejecutarConsulta($sql2);

						//COMPROBAMOS SI EXISTE EL EQUIPO Y LA SERIE

						if ($rs->rowCount() > 0 && $rs2->rowCount() > 0) {	

							foreach ($rs as $row) {
								$idequipomaterial = $row['id'];
								break;
							}

							foreach ($rs2 as $row) {
								$idguiaremisionequipomaterial = $row['id'];
								break;
							}

							//ES SERIE INSERTAR DEVOLUCION, CAMBIAR ESTADO A SERIE Y REDUCIR STOCK DE EQUIPO

							$sql = 'INSERT INTO devolucion(observacion, idequipomaterial, idguiaremisionequipomaterial, registro, cantidad, idempresa) VALUES("' . $observacion . '", ' . $idequipomaterial . ', ' . $idguiaremisionequipomaterial . ', "' . $registro . '", 1, ' . $idempresa . ')';
							$cado->ejecutarConsulta($sql);

							$sql2 = 'UPDATE guiaremisionequipomaterial SET estado = "D" WHERE id = ' . $idguiaremisionequipomaterial;
							$cado->ejecutarConsulta($sql2);

							$sql3 = 'UPDATE equipomaterial SET stock = (CASE WHEN stock >= 1 THEN (stock - 1) ELSE stock END) WHERE id = ' . $idequipomaterial . ' AND idempresa = ' . $idempresa;
							$cado->ejecutarConsulta($sql3);

							$cantidadfilas++;
						}
					}

					$i++;	
				} else {			
					break;			
				}
			}

			if ($cantidadfilas != 0) {
				echo '<font style="color:green">' . $cantidadfilas . ' DEVOLUCIONES CORRECTAMENTE IMPORTADOS.</font><br>';
			} else {
				echo '<font style="color:red">NO SE REGISTRARON DEVOLUCIONES.</font><br>';
			}
			
		} else {
			echo '<font style="color:red">NO ES EL FORMATO CORRECTO PARA IMPORTAR DEVOLUCIONES.</font><br><br>';
		}
		unlink($archivo_guardado);
	} catch (Exception $e) {
		echo '<font style="color:red">OCURRIÓ UN ERROR AL IMPORTAR.</font>';
	}	
}

/*

//Consulta para incializar la base de datos

UPDATE equipomaterial SET stock = 0;

DELETE FROM guiaremisionequipomaterial;
DELETE FROM guiaremision;

DELETE FROM actividad;
DELETE FROM prefijo;
DELETE FROM baremo;

DELETE FROM devolucion;

DELETE FROM asignacionequipomaterial;
DELETE FROM asignacion;

DELETE FROM instalacionequipomaterial;
DELETE FROM instalacion;

DELETE FROM resasignacionequipomaterial;
DELETE FROM resasignacion;

ALTER TABLE guiaremisionequipomaterial AUTO_INCREMENT = 1;
ALTER TABLE guiaremision AUTO_INCREMENT = 1;
ALTER TABLE instalacionequipomaterial AUTO_INCREMENT = 1;
ALTER TABLE instalacion AUTO_INCREMENT = 1;
ALTER TABLE asignacion AUTO_INCREMENT = 1;
ALTER TABLE asignacionequipomaterial AUTO_INCREMENT = 1;
*/	