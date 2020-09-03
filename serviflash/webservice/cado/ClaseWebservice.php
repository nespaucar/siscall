<?php
require_once('conexion.php');
date_default_timezone_set("America/Lima");

class Webservice {

	function ListarServidores($servicio_id, $categoria_id, $usuario_id, $SQLidsDistintos, $orden, $latitud, $longitud) {
	    $ocado = new cado();

	    if($orden != "distancia") {
			$sql = "SELECT 
			    (CASE 
			        WHEN (SELECT favorito_id 
				            FROM favorito f 
				            WHERE f.favorito_id = d.user_id
				            AND f.servicio_id = s.id
				            AND f.user_id = '$usuario_id' 
				            AND f.estado = 'A'
				            LIMIT 1) > 0 
			     		THEN '1'
			        ELSE '0' 
			    END)
				AS favorito, d.id as detalle_id, d.estrellas, u.foto, d.servicio_id, d.user_id, u.nombre, s.descripcion as servicio, u.ruc
		    FROM detalleservicio d 
		    INNER JOIN servicio s ON s.id = d.servicio_id
		    INNER JOIN categoria c ON c.id = s.categoria_id
		    INNER JOIN user u ON u.id = d.user_id WHERE";

		    $todos = false;

		    if($servicio_id!=="0") {
		    	$sql .= " s.id = " . $servicio_id;
		    	$todos = true;
		    }

		    if($categoria_id!=="0") {
		    	if($todos) {
		    		$sql .= " AND";
		    	}
		    	$sql .= " c.id = " . $categoria_id;
		    	$todos = true;
		    }

		    if(!$todos) {
		    	$sql .= " 1 = 1";
		    }

		    $sql .= " AND s.estado = 'A' 
		    			AND u.estado = 'A' 
		    			AND c.estado = 'A' 
		    			AND d.estado = 'A' 
		    			AND u.id != '" . $usuario_id . "' 
		    			" . $SQLidsDistintos . " 
		    			ORDER BY " . $orden . " ASC
		    			LIMIT 9";
	    } else {
	    	//SOLO PARA CUANDO PIDEN ORDENAR POR DISTANCIAS
		    $sql = "SELECT   
				(CASE
			    	WHEN bl.latitud != '' AND bl.longitud != '' THEN
			            (6371000 * 2 * ASIN(
			                    SQRT(
			                        COS(RADIANS($latitud)) * COS(RADIANS(bl.latitud)) * POW(SIN((RADIANS($longitud) - RADIANS(bl.longitud))/2), 2) +
			                        POW(SIN((RADIANS($latitud) - RADIANS(bl.latitud))/2), 2)
			                    )
			                )
			            )
			        ELSE 
			        	'-'
			    END)
				AS distancia, MAX(bl.id), bl.user_id, 
				(CASE 
					WHEN (SELECT favorito_id 
						FROM favorito f 
						WHERE f.favorito_id = bl.user_id
						AND f.servicio_id = s.id
						AND f.user_id = '$usuario_id' 
						AND f.estado = 'A'
						LIMIT 1) > 0 
					    THEN '1'
					ELSE '0' 
				END) AS favorito, d.id as detalle_id, d.estrellas, u.foto, d.servicio_id, d.user_id, u.nombre, s.descripcion AS servicio, u.ruc
			FROM blogueo bl
			INNER JOIN user u ON bl.user_id = u.id
			INNER JOIN detalleservicio d ON d.user_id = u.id
			INNER JOIN servicio s ON s.id = d.servicio_id
			INNER JOIN categoria c ON c.id = s.categoria_id WHERE";

			$todos = false;

		    if($servicio_id!=="0") {
		    	$sql .= " s.id = " . $servicio_id;
		    	$todos = true;
		    }

		    if($categoria_id!=="0") {
		    	if($todos) {
		    		$sql .= " AND";
		    	}
		    	$sql .= " c.id = " . $categoria_id;
		    	$todos = true;
		    }

		    if(!$todos) {
		    	$sql .= " 1 = 1";
		    }

		    $sql .= " AND s.estado = 'A' 
		    			AND u.estado = 'A' 
		    			AND c.estado = 'A' 
		    			AND d.estado = 'A' 
		    			AND u.id != '" . $usuario_id . "' 
		    			" . $SQLidsDistintos . " 
		    			GROUP BY detalle_id
						ORDER BY distancia ASC
		    			LIMIT 9";
		    echo $sql;
		}
	    
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function ListarServicios($filtro) {
	    $ocado=new cado();
	    $sql="SELECT s.id, s.descripcion, s.foto, c.descripcion AS categoria_descripcion, c.id AS categoria_id 
	    FROM servicio s
	    INNER JOIN categoria c ON c.id = s.categoria_id
	    WHERE s.descripcion LIKE '%" . $filtro . "%'
	    AND s.estado = 'A' 
	    AND c.estado = 'A'
	    ORDER BY s.descripcion
	    LIMIT 10";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function ListarServiciosCreados($usuario_id) {
	    $ocado=new cado();
	    $sql="SELECT d.id, s.descripcion, s.foto, s.id AS sid, d.estrellas, (SELECT COUNT(o.id) FROM opinion o WHERE o.user_id = '$usuario_id' AND o.servicio_id = d.servicio_id) AS opiniones 
	    FROM detalleservicio AS d
	    INNER JOIN servicio AS s ON s.id = d.servicio_id
	    WHERE user_id = '$usuario_id'
	    AND d.estado = 'A'
	    AND s.estado = 'A'
	    ORDER BY s.descripcion";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function RegistrarUsuario($nombre, $dni, $telefono, $codigo, $ruc) {
		$tipo = "P";
		if($ruc != "") {
			$tipo = "E";
		}
	    $ocado=new cado();
	    $sql="INSERT INTO user(estrellas, name, email, password, estado, nombre, dni, ruc, tipo, telefono, created_at, updated_at, email_verified_at)
            VALUES(0, '$dni', '$dni', '$codigo', 'A', '$nombre', '$dni', '$ruc', '$tipo', '$telefono', NOW(), NOW(), NOW());";
	    $ejecutar=$ocado->ejecutar($sql);
	    $sql="SELECT id 
	    FROM user 
	    WHERE dni = '$dni';";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function RegistrarSesion($usuario_id, $latitud, $longitud, $direccion) {
	    $ocado=new cado();
	    $sql="INSERT INTO blogueo(user_id, latitud, longitud, direccion, fecha, created_at, updated_at)
            VALUES('$usuario_id', '$latitud', '$longitud', '$direccion', NOW(), NOW(), NOW());";
	    $ejecutar=$ocado->ejecutar($sql);
	    $sql="SELECT id 
	    FROM blogueo WHERE user_id = '$usuario_id' 
	    ORDER BY created_at DESC 
	    LIMIT 1;";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function DniDuplicado($dni, $usuario_id = "") {
	    $ocado=new cado();
	    $sql="SELECT id 
	    FROM user 
	    WHERE dni = '$dni'";
	    if($usuario_id!=="") {
	    	$sql .= " AND id != " . $usuario_id;
	    }
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function RucDuplicado($ruc, $usuario_id = "") {
	    $ocado=new cado();
	    $sql="SELECT id 
	    FROM user 
	    WHERE ruc = '$ruc'";
	    if($usuario_id!=="") {
	    	$sql .= " AND id != " . $usuario_id;
	    }
	    $sql .= " AND ruc != NULL 
	    		 AND ruc != ''";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function TelefonoDuplicado($telefono, $usuario_id = "") {
	    $ocado=new cado();
	    $sql="SELECT id 
	    FROM user 
	    WHERE telefono = '$telefono'";
	    if($usuario_id!=="") {
	    	$sql .= " AND id != " . $usuario_id;
	    }
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function CorreoDuplicado($email, $usuario_id = "") {
	    $ocado=new cado();
	    $sql="SELECT id 
	    FROM user 
	    WHERE email = '$email'";
	    if($usuario_id!=="") {
	    	$sql .= " AND id != " . $usuario_id;
	    }
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function DatosUsuario($usuario_id) {
	    $ocado=new cado();
	    $sql="SELECT nombre, (SELECT DISTINCT COUNT(l.id) FROM llamada l WHERE l.receptor_id = '$usuario_id' OR l.emisor_id = '$usuario_id') AS contactados, dni, ruc, email, telefono, DAY(fecha_nacimiento) AS dia_nacimiento, MONTH(fecha_nacimiento) AS mes_nacimiento, YEAR(fecha_nacimiento) AS ano_nacimiento, foto, sexo, estrellas
	    FROM user 
	    WHERE id = '$usuario_id';";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function ActualizarFotoPerfil($usuario_id, $path) {
	    $ocado=new cado();
	    $sql="UPDATE user SET foto = '$path', updated_at = NOW()
	    WHERE id = '$usuario_id'";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function ActualizarUsuario($usuario_id, $email, $sexo, $telefono, $nombre, $fecha_nacimiento, $ruc) {
		$tipo = "P";
		if($ruc !== "") {
			$tipo = "E";
		}
		$ocado=new cado();
	    $sql="UPDATE user SET ruc = '$ruc', tipo = '$tipo', telefono = '$telefono', nombre = '$nombre', sexo = $sexo, fecha_nacimiento = $fecha_nacimiento, updated_at = NOW()";
	    if($email!=="") {
	    	$sql .= ", email = '" . $email . "'";
	    } else {
	    	$sql .= ", email = dni";
	    }
	    $sql.=" WHERE id = '" . $usuario_id. "'";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function RegistrarFotoServidor($usuario_id, $servicio_id, $nombrefoto) {
		$ocado=new cado();
	    $sql="INSERT INTO foto(estado, foto, user_id, servicio_id, created_at, updated_at)
            VALUES('A', '$nombrefoto', '$usuario_id', '$servicio_id', NOW(), NOW());";
	    $ejecutar=$ocado->ejecutar($sql);
	    if($ejecutar->rowCount() > 0) {
	    	$sql = "SELECT MAX(id) AS mid FROM foto
	    	WHERE user_id = '$usuario_id' AND servicio_id = '$servicio_id';";
	    	$ejecutar=$ocado->ejecutar($sql);
	    	return $ejecutar;
	    }
	    return null;	    
	}

	function ListaFotosServiciosCreados($usuario_id, $servicio_id) {
	    $ocado=new cado();
	    $sql="SELECT id, foto
	    FROM foto
	    WHERE user_id = '$usuario_id' 
	    AND estado = 'A'
	    AND servicio_id = '$servicio_id'";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
	}

	function Loguearse($usu, $pass){
        $ocado=new cado();
        $sql="SELECT id FROM user WHERE estado = 'A' AND name = '$usu' AND password = '$pass'";
        $ejecutar=$ocado->ejecutar($sql);
        return $ejecutar;
    }

    function ListaContactados($usuario_id, $SQLidsDistintos) {
    	$ocado=new cado();
	    $sql="SELECT l.id, l.servicio_id, c.id AS cid, u.id AS uid, l.emisor_id AS id_contacto, s.descripcion AS nservicio, c.nombre AS nombre_contacto, c.telefono AS telefono_contacto, c.foto AS foto_contacto, l.receptor_id AS id_receptor, u.nombre AS nombre_receptor, u.telefono AS telefono_receptor, u.foto AS foto_receptor, l.created_at as fecha_llamada
	    FROM llamada l 
	    INNER JOIN user c ON c.id = l.emisor_id
	    INNER JOIN user u ON u.id = l.receptor_id
	    INNER JOIN servicio s ON s.id = l.servicio_id
	    WHERE (l.emisor_id = '$usuario_id' OR l.receptor_id = '$usuario_id')
	    AND s.estado = 'A'
	    AND l.estado = 'A'
	    AND u.estado = 'A'
	    AND c.estado = 'A'
	    " . $SQLidsDistintos . "
	    ORDER BY l.created_at DESC
	    LIMIT 10";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function EliminarFotoServicioCreado($foto_id) {
    	$ocado=new cado();
	    $sql="UPDATE foto SET estado = 'I', updated_at = NOW()
	    WHERE id = '$foto_id'";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function ListaCategorias() {
    	$ocado=new cado();
	    $sql="SELECT id, descripcion, foto
	    FROM categoria
	    ORDER BY descripcion
	    AND categoria.estado = 'A'";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function ListaServiciosCategoria($categoria_id) {
    	$ocado=new cado();
	    $sql="SELECT servicio.id, servicio.descripcion, servicio.foto
	    FROM servicio
	    INNER JOIN categoria ON categoria.id = servicio.categoria_id";
	    
	    if($categoria_id!=="0") {
	    	$sql.=" WHERE categoria_id = " . $categoria_id;
	    }

	    $sql.=" AND servicio.estado = 'A' AND categoria.estado = 'A' ORDER BY servicio.descripcion";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function RegistrarServicio($nombre_servicio, $categoria_id) {
    	$ocado=new cado();
	    $sql="INSERT INTO servicio(estado, descripcion, foto, categoria_id, created_at, updated_at)
            VALUES('A', '$nombre_servicio', CONCAT('SERVICIO_0', '.JPG'), '$categoria_id', NOW(), NOW());";
	    $ejecutar=$ocado->ejecutar($sql);
	    $sql="SELECT MAX(id) AS mid 
	    FROM servicio;";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function RegistrarServicioCreado($usuario_id, $servicio_id, $descripcion, $ubicacion, $horario) {
    	$ocado=new cado();
	    $sql="INSERT INTO detalleservicio(estado, descripcion, estrellas, contactados, disponibilidad, ubicacion, servicio_id, user_id, created_at, updated_at)
            VALUES('A', '$descripcion', 0, 0, '$horario', '$ubicacion', '$servicio_id', '$usuario_id', NOW(), NOW());";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function ObtenerDatosServicioCreado($usuario_id, $servicio_id) {
    	$ocado=new cado();
	    $sql="SELECT s.descripcion AS nombre_servicio, d.descripcion, d.ubicacion, d.disponibilidad AS horario
	    FROM detalleservicio d
	    INNER JOIN servicio s ON s.id = d.servicio_id
	    WHERE d.user_id = '$usuario_id' 
	    AND d.servicio_id = '$servicio_id' 
	    AND d.estado = 'A' 
	    AND s.estado = 'A'
	    LIMIT 1";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function ActualizarModificacionServicioCreado($usuario_id, $servicio_id, $descripcion, $ubicacion, $horario) {
    	$ocado=new cado();
	    $sql="UPDATE detalleservicio SET descripcion = '$descripcion', ubicacion = '$ubicacion', disponibilidad = '$horario', updated_at = NOW()	    
	    WHERE user_id = '$usuario_id'
	    AND servicio_id = '$servicio_id'
	    AND estado = 'A'";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function EliminarServicioCreado($usuario_id, $servicio_id) {
    	$ocado=new cado();
	    $sql="UPDATE detalleservicio SET estado = 'I', updated_at = NOW()
	    WHERE user_id = '$usuario_id'
	    AND servicio_id = '$servicio_id'
	    AND estado = 'A'";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function DatosServidor($detalleservicio_id) {
    	$ocado=new cado();
	    $sql="SELECT u.ruc, u.nombre, d.ubicacion AS direccion, u.fecha_nacimiento AS edad, u.sexo, d.disponibilidad AS horario, u.telefono, u.foto, d.descripcion, s.descripcion AS nombre_servicio, d.estrellas
	    FROM user u
	    INNER JOIN detalleservicio d ON d.user_id = u.id 
	    INNER JOIN servicio s ON d.servicio_id = s.id
	    WHERE d.id = '$detalleservicio_id'
	    AND s.estado = 'A'
	    AND d.estado = 'A';";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function ListaOpiniones($detalleservicio_id) {
    	$ocado=new cado();
    	$servicio_id = "(SELECT servicio_id FROM detalleservicio WHERE id = " . $detalleservicio_id . " LIMIT 1)";
    	$user_id = "(SELECT user_id FROM detalleservicio WHERE id = " . $detalleservicio_id . " LIMIT 1)";
	    $sql="SELECT o.id, u.nombre, o.descripcion AS opinion, o.estrellas AS puntuacion, u.foto, o.fecha
	    FROM opinion o
	    INNER JOIN user u ON o.opinador_id = u.id
	    WHERE o.servicio_id = $servicio_id
	    AND o.user_id = $user_id
	    ORDER BY o.created_at DESC";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function RegistrarMensaje($servicio_id, $receptor_id, $emisor_id, $mensaje) {
    	$ocado=new cado();
	    $sql="INSERT INTO mensaje(estado, mensaje, emisor_id, receptor_id, servicio_id, fecha, created_at, updated_at)
            VALUES('A', '$mensaje', '$emisor_id', '$receptor_id', '$servicio_id', NOW(), NOW(), NOW());";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function RegistrarLlamada($servicio_id, $receptor_id, $emisor_id) {
    	$ocado=new cado();
	    $sql="INSERT INTO llamada(estado, tipo, emisor_id, receptor_id, servicio_id, fecha, created_at, updated_at)
            VALUES('A', 'S', '$emisor_id', '$receptor_id', '$servicio_id', NOW(), NOW(), NOW());";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function ListaMensajes($usuario_id, $SQLidsDistintos) {
    	$ocado=new cado();
	    $sql="SELECT m.id, s.descripcion AS servicio, e.nombre, e.foto, m.mensaje, m.fecha, e.telefono, e.id AS user_id, s.id AS servicio_id
	    FROM mensaje m
	    INNER JOIN user e ON e.id = m.emisor_id
	    INNER JOIN user r ON r.id = m.receptor_id
	    INNER JOIN servicio s ON s.id = m.servicio_id
	    WHERE m.receptor_id = '$usuario_id'
	    AND m.estado = 'A'
	    AND e.estado = 'A'
	    AND r.estado = 'A'
	    AND s.estado = 'A'
	    " . $SQLidsDistintos . "
	    ORDER BY m.created_at
	    LIMIT 15";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function EliminarFavorito($usuario_id, $favorito_id, $servicio_id) {
    	$ocado=new cado();
	    $sql="UPDATE favorito SET estado = 'I', deleted_at = NOW()	    
	    WHERE user_id = '$usuario_id'
	    AND servicio_id = '$servicio_id'
	    AND favorito_id = '$favorito_id'
	    AND estado = 'A'";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function CrearFavorito($usuario_id, $favorito_id, $servicio_id) {
    	$ocado=new cado();
	    $sql="INSERT INTO favorito(estado, user_id, favorito_id, servicio_id, fecha, created_at, updated_at)
            VALUES('A', '$usuario_id', '$favorito_id', '$servicio_id', NOW(), NOW(), NOW());";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function ListaFavoritos($usuario_id, $SQLidsDistintos) {
    	$ocado=new cado();
	    $sql="SELECT f.id, fa.nombre, (SELECT d.estrellas FROM detalleservicio d WHERE d.user_id = f.favorito_id AND d.servicio_id = s.id AND f.user_id = '$usuario_id' AND d.estado = 'A' LIMIT 1) AS nestrellas, fa.foto, s.descripcion AS servicio, fa.telefono, f.favorito_id, f.servicio_id
	    FROM favorito f
	    INNER JOIN user u ON u.id = f.user_id
	    INNER JOIN user fa ON fa.id = f.favorito_id
	    INNER JOIN servicio s ON s.id = f.servicio_id
	    WHERE f.user_id = '$usuario_id'
	    AND u.estado = 'A'
	    AND fa.estado = 'A'
	    AND s.estado = 'A'
	    AND f.estado = 'A'
	    " . $SQLidsDistintos . "
	    ORDER BY s.descripcion
	    LIMIT 15";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function RecuperarContra($telefono, $dni) {
    	$ocado=new cado();
	    $sql="SELECT password
	    FROM user
	    WHERE telefono = '$telefono'
	    AND dni = '$dni'
	    AND estado = 'A'
	    LIMIT 1";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function ModificarUbicacion($sesion_id, $usuario_id, $latitud, $longitud, $direccion) {
    	$ocado=new cado();
	    $sql="UPDATE blogueo SET fecha = NOW(), latitud = '$latitud', longitud = '$longitud', direccion = '$direccion', updated_at = NOW()
	    WHERE user_id = '$usuario_id'
	    AND id = '$sesion_id'";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function LocalizacionServidor($usuario_id) {
    	$ocado=new cado();
	    $sql="SELECT latitud, longitud, direccion, updated_at
	    FROM blogueo
	    WHERE id = (SELECT MAX(dd.id) FROM blogueo dd WHERE dd.user_id = '" . $usuario_id . "')
	    AND user_id = '$usuario_id'
	    LIMIT 1";
	    $ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function ComprobarDisponibilidad($fechaFinal) {
	    $d = strtotime($fechaFinal) - strtotime(date("Y-m-d H:i:s"));
	    $disponible = 0;
	    if($d <= 4 && $d >= -4) {
	    	$disponible = 1;
	    }
	    return $disponible;
	}
	
	function DistanciaEntreCoordenadas($lat0, $lng0, $lat1, $lng1) {
	    $rlat0 = deg2rad($lat0);
        $rlng0 = deg2rad($lng0);
        $rlat1 = deg2rad($lat1);
        $rlng1 = deg2rad($lng1);
        
        $latDelta = $rlat1 - $rlat0;
        $lonDelta = $rlng1 - $rlng0;
        
        //EN METROS
        
	    $distance2 = 6371000 * 2 * asin(
            sqrt(
                cos($rlat0) * cos($rlat1) * pow(sin($lonDelta / 2), 2) +
                pow(sin($latDelta / 2), 2)
            )
        );
        
        return round($distance2, 1, PHP_ROUND_HALF_UP);
	}

	function RegistrarOpinion($usuario_id, $servidor_id, $servicio_id, $estrellas, $opinion) {
		$ocado=new cado();
	    $sql="INSERT INTO opinion(descripcion, estrellas, fecha, estado, created_at, updated_at, user_id, opinador_id, servicio_id)
            VALUES('$opinion', '$estrellas', NOW(), 'A', NOW(), NOW(), '$servidor_id', '$usuario_id', '$servicio_id');";
       	$ejecutar=$ocado->ejecutar($sql);
       	if($ejecutar->rowCount() > 0) {
	    	$sql = "SELECT o.id, u.nombre, u.foto, o.fecha 
			FROM opinion o
			INNER JOIN user u ON u.id = o.opinador_id
	    	WHERE o.user_id = '$servidor_id' 
	    	AND o.opinador_id = '$usuario_id';";
	    	$ejecutar=$ocado->ejecutar($sql);
	    	return $ejecutar;
	    }
	    return null;
	}

	function QuitarFavorito($favorito_id) {
    	$ocado=new cado();
	    $sql="UPDATE favorito SET estado = 'I', updated_at = NOW()
	    WHERE id = '$favorito_id'";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function RecalcularEstrellas1($servidor_id, $servicio_id) {
    	$ocado=new cado();
	    $sql="UPDATE detalleservicio d SET d.estrellas = (SELECT ROUND(AVG(o.estrellas)) FROM opinion o WHERE o.user_id = '$servidor_id' AND o.servicio_id = '$servicio_id' AND o.estado = 'A'), d.updated_at = NOW()	    
	    WHERE d.user_id = '$servidor_id'
	    AND d.servicio_id = '$servicio_id'
	    AND d.estado = 'A'";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }

    function RecalcularEstrellas2($servidor_id) {
    	$ocado=new cado();
	    $sql="UPDATE user u SET u.estrellas = (SELECT ROUND(AVG(d.estrellas)) FROM detalleservicio d WHERE d.user_id = '$servidor_id' AND d.estado = 'A'), u.updated_at = NOW()	    
	    WHERE u.id = '$servidor_id'
	    AND u.estado = 'A'";
       	$ejecutar=$ocado->ejecutar($sql);
	    return $ejecutar;
    }
    
    function CalculaEdad($fecha) {
    	if($fecha  === "") {
    		return "-";
    	}
	    list($Y,$m,$d) = explode("-",$fecha);
	    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
	}
}
/*SELECT   
	(CASE
    	WHEN bl.latitud != '' AND bl.longitud != '' THEN
            ROUND((6371000 * 2 * ASIN(
                    SQRT(
                        COS(RADIANS(-79.83481)) * COS(RADIANS(bl.latitud)) * POW(SIN((RADIANS(-6.7474923) - RADIANS(bl.longitud))/2), 2) +
                        POW(SIN((RADIANS(-79.83481) - RADIANS(bl.latitud))/2), 2)
                    )
                )
            ), 1)
        ELSE 
        	'-'
    END)
	AS distancia, MAX(bl.id), bl.user_id, 
	(CASE 
		WHEN (SELECT favorito_id 
			FROM favorito f 
			WHERE f.favorito_id = bl.user_id
			AND f.servicio_id = s.id
			AND f.user_id = '$usuario_id' 
			AND f.estado = 'A'
			LIMIT 1) > 0 
		    THEN '1'
		ELSE '0' 
	END) AS favorito, d.id as detalle_id, u.foto, d.servicio_id, d.user_id, u.nombre, s.descripcion AS servicio, u.ruc
FROM blogueo bl
INNER JOIN user u ON bl.user_id = u.id
INNER JOIN detalleservicio d ON d.user_id = u.id
INNER JOIN servicio s ON s.id = d.servicio_id
INNER JOIN categoria c ON c.id = s.categoria_id
GROUP BY detalle_id
ORDER BY distancia*/

?>