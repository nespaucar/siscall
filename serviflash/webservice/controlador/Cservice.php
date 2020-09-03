<?php session_start();
    require_once("../cado/ClaseWebservice.php");
    date_default_timezone_set("America/Lima");
    $owebservice = new Webservice();

    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");

    if($_GET['accion']=='COMPROBAR_CONEXION') {
        echo json_encode(array("rpta" => "CONECTADO"));
    } else if($_GET['accion']=='LISTA_SERVIDORES') {
        $servicio_id = $_POST["servicio_id"];
        $categoria_id = $_POST["categoria_id"];
        $latitudmia = $_POST["latitud"];
        $longitudmia = $_POST["longitud"];
        $usuario_id = $_POST["usuario_id"];
        $SQLidsDistintos = $_POST["SQLidsDistintos"];
        $orden = $_POST["orden"];
        $datos = array();

        $listaservidores = $owebservice->ListarServidores($servicio_id, $categoria_id, $usuario_id, $SQLidsDistintos, $orden, $latitudmia, $longitudmia);
        while($fila=$listaservidores->fetch()) {
            $localizacionservidor = $owebservice->LocalizacionServidor($fila['user_id']);
            $latitud = "";
            $longitud = "";
            $direccion = "";
            $disponible = "";
            $distancia = "-";
            if($localizacionservidor->rowCount() > 0) {
                while($filan=$localizacionservidor->fetch()){
                    $latitud = $filan['latitud'];
                    $longitud = $filan['longitud'];
                    $direccion = $filan['direccion'];
                    $disponible = $owebservice->ComprobarDisponibilidad($filan['updated_at']);
                    if($latitud !== "" && $longitud !== "" && $latitudmia !== "" && $longitudmia !== "") {
                        $distancia = "A " . $owebservice->DistanciaEntreCoordenadas($latitud, $longitud, $latitudmia, $longitudmia) . " M.";
                    }
                }
            }                
            array_push($datos, array(
                'detalle_id'    => utf8_encode($fila['detalle_id']), 
                'latitud'    => $latitud, 
                'disponible'    => $disponible, 
                'longitud'    => $longitud, 
                'direccion'    => $direccion,
                'distancia'    => $distancia,
                'estrellas'    => utf8_encode($fila['estrellas']), 
                'foto'    => utf8_encode($fila['foto']),
                'servicio_id'    => utf8_encode($fila['servicio_id']), 
                'user_id'    => utf8_encode($fila['user_id']), 
                'nombre'  => utf8_encode($fila['nombre']),
                'favorito'  => utf8_encode($fila['favorito']),
                'ruc'  => utf8_encode($fila['ruc']),
                'servicio'  => utf8_encode($fila['servicio'])
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='LISTA_SERVICIOS') {
        $datos = array();
        $filtro = $_POST["filtro"];
        $listaservicios = $owebservice->ListarServicios($filtro);
        while($fila=$listaservicios->fetch()) {
            array_push($datos, array(
                'id'    => utf8_encode($fila['id']),
                'foto'    => utf8_encode($fila['foto']), 
                'descripcion'    => utf8_encode($fila['descripcion']),
                'categoria_id'    => utf8_encode($fila['categoria_id']), 
                'categoria_descripcion'    => utf8_encode($fila['categoria_descripcion']), 
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='REGISTRAR_USUARIO') {
        $datos = array();
        $usuario_id = 0;
        $sesion_id = 0;
        $nombre=$_POST["nombre"];
        $dni=$_POST["dni"];
        $telefono=$_POST["telefono"];
        $codigo=$_POST["codigo"];
        $ruc=$_POST["ruc"];
        $latitud=$_POST["latitud"];
        $longitud=$_POST["longitud"];
        $direccion=$_POST["direccion"];
        $dniduplicado = $owebservice->DniDuplicado($dni);
        $rucduplicado = $owebservice->RucDuplicado($ruc);
        $telefonoduplicado = $owebservice->TelefonoDuplicado($telefono);
        $retorno = "3";
        if($dniduplicado->rowCount() == 0) {
            $retorno = "4";
            if($telefonoduplicado->rowCount() == 0) {
                $retorno = "5";
                if($rucduplicado->rowCount() == 0) {
                    //REGISTRAMOS AL USUARIO
                    $registrarusuario = $owebservice->RegistrarUsuario($nombre, $dni, $telefono, $codigo, $ruc);
                    if($registrarusuario->rowCount() == 0) {
                        $retorno = "2";
                    } else {
                        $retorno = "1";
                        while($fila=$registrarusuario->fetch()) {
                            $usuario_id = $fila['id'];
                        }
                        //REGISTRAMOS LA SESIÓN
                        $registrarsesion = $owebservice->RegistrarSesion($usuario_id, $latitud, $longitud, $direccion);
                        while($fila=$registrarsesion->fetch()) {
                            $sesion_id = $fila['id'];
                        }
                    }
                }
            }
        }
        $datos = array("rpta" => $retorno, "usuario_id" => $usuario_id, "sesion_id" => $sesion_id);
        echo json_encode($datos);
    } else if($_GET['accion']=='DATOS_USUARIO') {
        $usuario_id=$_POST["usuario_id"];
        $datos = array();
        $datosusuario = $owebservice->DatosUsuario($usuario_id);
        while($fila=$datosusuario->fetch()) {
            array_push($datos, array(
                "nombre" => utf8_encode($fila["nombre"]),
                "email" => utf8_encode($fila["email"])==utf8_encode($fila["dni"])?"":utf8_encode($fila["email"]),
                "telefono" => utf8_encode($fila["telefono"]),
                "dia_nacimiento" => $fila["dia_nacimiento"]==""?date("d"):utf8_encode($fila["dia_nacimiento"]),
                "mes_nacimiento" => $fila["mes_nacimiento"]==""?date("m"):utf8_encode($fila["mes_nacimiento"]),
                "ano_nacimiento" => $fila["ano_nacimiento"]==""?"1996":utf8_encode($fila["ano_nacimiento"]),
                "fecha_nueva" => $fila["ano_nacimiento"]==""?"S":"N",
                "foto" => utf8_encode($fila["foto"]),
                "sexo" => utf8_encode($fila["sexo"]),
                "estrellas" => utf8_encode($fila["estrellas"]),
                "ruc" => utf8_encode($fila["ruc"]),
                "contactados" => utf8_encode($fila["contactados"]),
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='ACTUALIZAR_FOTO_PERFIL') {
        $usuario_id=$_POST["usuario_id"];
        $foto= $_POST["foto"];
        $nombreimg = "PERFIL_" . $usuario_id . ".JPG";
        $path = "imagenes/fotos_perfil/" . $nombreimg;

        $actualizarfotoperfil = $owebservice->ActualizarFotoPerfil($usuario_id, $nombreimg);
        $retorno = "2";
        if($actualizarfotoperfil->rowCount() > 0) {
            file_put_contents("../".$path, base64_decode($foto));
            $bytesArchivo = file_get_contents("../".$path);
            $retorno = "1";
        }

        //ACTUALIZO EN BASE DE DATOS EL NOMBRE DE LA FOTO
        echo json_encode(array("rpta" => $retorno));
    } else if($_GET['accion']=='ACTUALIZAR_USUARIO') {
        $datos = array();
        $usuario_id = $_POST["usuario_id"];
        $email = $_POST["email"];
        $nombre = $_POST["nombre"];
        $sexo = $_POST["sexo"]==""?"NULL":"'".$_POST["sexo"]."'";
        $telefono = $_POST["telefono"];
        $ruc = $_POST["ruc"];

        $fn = "NULL";
        if($_POST["fecha_nacimiento"]!=="") {
            //Array fecha nacimiento
            $afn = explode("/", $_POST["fecha_nacimiento"]);
            $fn = "'" . $afn[2] . "-" . $afn[1] . "-" . $afn[0] . "'";
        }
        $retorno = "1";
        if($email !== "") {
            $retorno = "3";
            $correoduplicado = $owebservice->CorreoDuplicado($email, $usuario_id);
            if($correoduplicado->rowCount() == 0) {
                $retorno = "4";
                $telefonoduplicado = $owebservice->TelefonoDuplicado($telefono, $usuario_id);                    
                if($telefonoduplicado->rowCount() == 0) {
                    $retorno = "5";
                    $rucduplicado = $owebservice->RucDuplicado($ruc, $usuario_id);
                    if($rucduplicado->rowCount() == 0) {
                        //ACTUALIZAMOS AL USUARIO
                        $actualizarusuario = $owebservice->ActualizarUsuario($usuario_id, $email, $sexo, $telefono, $nombre, $fn, $ruc);
                        $retorno = "1";
                        if($actualizarusuario->rowCount() == 0) {
                            $retorno = "2";
                        }
                    }
                }
            }
            
        } else {
            $telefonoduplicado = $owebservice->TelefonoDuplicado($telefono, $usuario_id);
            if($telefonoduplicado->rowCount() == 0) {
                //ACTUALIZAMOS AL USUARIO
                $actualizarusuario = $owebservice->ActualizarUsuario($usuario_id, $email, $sexo, $telefono, $nombre, $fn, $ruc);
                if($actualizarusuario->rowCount() == 0) {
                    $retorno = "2";
                }
            }
        }
        $datos = array("rpta" => $retorno);
        echo json_encode($datos);
    } else if($_GET['accion']=='LISTA_SERVICIOS_CREADOS') {
        $datos = array();
        $usuario_id = $_POST['usuario_id'];
        $listaservicios = $owebservice->ListarServiciosCreados($usuario_id);
        while($fila=$listaservicios->fetch()){
            array_push($datos, array(
                'id'    => utf8_encode($fila['id']), 
                'servicio_id'    => utf8_encode($fila['sid']), 
                'descripcion'    => utf8_encode($fila['descripcion']), 
                'puntuacion'    => utf8_encode($fila['estrellas']), 
                'opiniones'    => utf8_encode($fila['opiniones']), 
                'foto'    => utf8_encode($fila['foto']),
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='REGISTRAR_FOTO_SERVIDOR') {
        $datos = array();
        $usuario_id = $_POST["usuario_id"];
        $servicio_id = $_POST["servicio_id"];
        $foto = $_POST["foto"];

        //NOMBRE RANDOM
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $nombrefoto = $usuario_id . "_" . $servicio_id . "_" . substr(str_shuffle($permitted_chars), 0, 16);
        $nombreimg = "FOTOSERV_" . $nombrefoto . ".JPG";
        $path = "imagenes/fotos_servidor_servicios/" . $nombreimg;
        $registrarfotoservidor = $owebservice->RegistrarFotoServidor($usuario_id, $servicio_id, $nombreimg);
        $retorno = "2";
        $idfoto = "0";
        if($registrarfotoservidor->rowCount() > 0) {
            file_put_contents("../".$path, base64_decode($foto));
            $bytesArchivo = file_get_contents("../".$path);
            $retorno = "1";
            //CAPTURO EL NUEVO ID
            while($fila=$registrarfotoservidor->fetch()) {
                $idfoto = utf8_encode($fila['mid']);
            }
        }
        $datos = array("rpta" => $retorno, "nombrefoto" => $nombreimg, "idfoto" => $idfoto);
        echo json_encode($datos);
    } else if($_GET['accion']=='LISTA_FOTOS_SERVICIOS_CREADOS') {
        $datos = array();
        $usuario_id = $_POST["usuario_id"];
        $servicio_id = $_POST["servicio_id"];
        $listafotosservicioscreados = $owebservice->ListaFotosServiciosCreados($usuario_id, $servicio_id);
        while($fila=$listafotosservicioscreados->fetch()){
            array_push($datos, array(
                'id'    => utf8_encode($fila['id']),
                'foto'    => utf8_encode($fila['foto']),
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='LOGUEARSE_WEB_SERVICE') {
        $tipo=0; 
        $tipo_usuario='';
        $user=$_POST["user"];
        $pass=$_POST["pass"];
        $latitud=$_POST["latitud"];
        $longitud=$_POST["longitud"];
        $direccion=$_POST["direccion"];
        $verificar = $owebservice->Loguearse($user,$pass);
        $retorno = "2";
        $user_id = "0";
        $sesion_id = "0";
        if($verificar->rowCount() > 0) {
            while($fila = $verificar->fetch()) {
                $user_id = $fila['id'];
                //REGISTRO NUEVA SESION
                $registrarsesion = $owebservice->RegistrarSesion($user_id, $latitud, $longitud, $direccion);
                if($registrarsesion->rowCount() > 0) {
                    while($fila = $registrarsesion->fetch()) {
                        $sesion_id = $fila['id'];
                        $retorno = "1";
                    }
                }
            }
        }
        $array = array(
            "rpta" => $retorno,
            "user_id" => $user_id,
            "sesion_id" => $sesion_id,
        );
        echo json_encode($array);
    } else if($_GET['accion']=='LISTA_CONTACTADOS') {
        $usuario_id=$_POST["usuario_id"];
        $SQLidsDistintos=$_POST["SQLidsDistintos"];
        $datos = array();
        $listacontactados = $owebservice->ListaContactados($usuario_id, $SQLidsDistintos);
        while($fila=$listacontactados->fetch()) {

            $d_fecha = $dias[strftime("%w", strtotime($fila["fecha_llamada"]))];
            $m_fecha = $meses[date("m", strtotime($fila["fecha_llamada"])) - 1];

            $fecha = $d_fecha . ", " . date("d", strtotime($fila["fecha_llamada"])) . " de " . $m_fecha . " del " . date("Y", strtotime($fila["fecha_llamada"]));

            $hora = date("h:i A", strtotime($fila["fecha_llamada"]));
            $tipo = "SALIDA";
            $nombre_contacto = $fila["nombre_receptor"];
            $telefono_contacto = $fila["telefono_receptor"];
            $foto = $fila["foto_receptor"];
            if($usuario_id==$fila["id_receptor"]) {
                $tipo = "ENTRADA";
                $nombre_contacto = $fila["nombre_contacto"];
                $telefono_contacto = $fila["telefono_contacto"];
                $foto = $fila["foto_contacto"];
            }
            //FORMATO DE FECHA
            array_push($datos, array(
                "id" => utf8_encode($fila["id"]),
                "servicio_id" => utf8_encode($fila["servicio_id"]),
                "user_id" => $fila["uid"]==$usuario_id?$fila["cid"]:$fila["uid"],
                "nombre" => utf8_encode($nombre_contacto),
                "fecha" => utf8_encode($fecha),
                "telefono" => utf8_encode($telefono_contacto),
                "hora" => utf8_encode($hora),
                "tipo" => utf8_encode($tipo),
                "foto" => utf8_encode($foto),
                "servicio" => utf8_encode($fila["nservicio"]==""?"-":$fila["nservicio"]),
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='ELIMINAR_FOTO_SERVICIO_CREADO') {
        $datos = array();
        $foto_id = $_POST["foto_id"];

        $eliminarfotoserviciocreado = $owebservice->EliminarFotoServicioCreado($foto_id);
        $retorno = "2";
        if($eliminarfotoserviciocreado->rowCount() > 0) {
            $retorno = "1";
        }
        $datos = array("rpta" => $retorno);
        echo json_encode($datos);
    } else if($_GET['accion']=='LISTA_CATEGORIAS') {
        $datos = array();
        $listacategorias = $owebservice->ListaCategorias();
        while($fila=$listacategorias->fetch()) {
            array_push($datos, array(
                "id" => utf8_encode($fila["id"]),
                "foto" => utf8_encode($fila["foto"]),
                "descripcion" => utf8_encode($fila["descripcion"]),
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='LISTA_SERVICIOS_CATEGORIA') {
        $categoria_id = $_POST["categoria_id"];
        $datos = array();
        $listaservicioscategoria = $owebservice->ListaServiciosCategoria($categoria_id);
        while($fila=$listaservicioscategoria->fetch()) {
            array_push($datos, array(
                "id" => utf8_encode($fila["id"]),
                "foto" => utf8_encode($fila["foto"]),
                "descripcion" => utf8_encode($fila["descripcion"]),
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='REGISTRAR_SERVICIO_CREADO') {
        $datos = array();
        $usuario_id = $_POST["usuario_id"];
        $servicio_id = $_POST["servicio_id"];
        $nombre_servicio = $_POST["nombre_servicio"];
        $descripcion = $_POST["descripcion"];
        $ubicacion = $_POST["ubicacion"];
        $horario = $_POST["horario"];

        if($servicio_id == "") {
            $categoria_id = 12; // OTROS
            $registrarservicio = $owebservice->RegistrarServicio($nombre_servicio, $categoria_id);
            if($registrarservicio->rowCount() > 0) {
                //OBTENGO SERVICIO_ID
                while($fila=$registrarservicio->fetch()) {
                    $servicio_id = utf8_encode($fila["mid"]);
                }
            }
        }

        $registrarserviciocreado = $owebservice->RegistrarServicioCreado($usuario_id, $servicio_id, $descripcion, $ubicacion, $horario);
        $retorno = "2";
        if($registrarserviciocreado->rowCount() > 0) {
            $retorno = "1";
        }

        $datos = array("rpta" => $retorno);
        echo json_encode($datos);
    } else if($_GET['accion']=='OBTENER_DATOS_SERVICIO_CREADO') {
        $usuario_id=$_POST["usuario_id"];
        $servicio_id=$_POST["servicio_id"];
        $datos = array();
        $datosserviciocreado = $owebservice->ObtenerDatosServicioCreado($usuario_id, $servicio_id);
        $retorno = "2";
        $nombre_servicio = "";
        $descripcion = "";
        $ubicacion = "";
        $horario = "";
        if($datosserviciocreado->rowCount() > 0) {
            $retorno = "1";
            while($fila=$datosserviciocreado->fetch()) {
               $nombre_servicio = utf8_encode($fila["nombre_servicio"]);
               $descripcion = utf8_encode($fila["descripcion"]);
               $ubicacion = utf8_encode($fila["ubicacion"]);
               $horario = utf8_encode($fila["horario"]);
            }
        }
        $datos = array(
            "rpta" => $retorno, 
            "nombre_servicio" => $nombre_servicio, 
            "descripcion" => $descripcion, 
            "ubicacion" => $ubicacion, 
            "horario" => $horario
        );
        echo json_encode($datos);
    } else if($_GET['accion']=='REGISTRAR_MODIFICACION_SERVICIO_CREADO') {
        $datos = array();
        $usuario_id = $_POST["usuario_id"];
        $servicio_id = $_POST["servicio_id"];
        $servicio_nuevo_id = $_POST["servicio_nuevo_id"];
        $nombre_servicio = $_POST["nombre_servicio"];
        $descripcion = $_POST["descripcion"];
        $ubicacion = $_POST["ubicacion"];
        $horario = $_POST["horario"];
        $estrellas = "";
        $opiniones = "";

        $retorno = "2";
        if($servicio_id == $servicio_nuevo_id) {
            $amsc = $owebservice->ActualizarModificacionServicioCreado($usuario_id, $servicio_id, $descripcion, $ubicacion, $horario);
            if($amsc->rowCount() > 0) {
                //OBTENGO SERVICIO_ID
                $retorno = "1";
            }
        } else {
            $eliminarserviciocreado = $owebservice->EliminarServicioCreado($usuario_id, $servicio_id);
            if($eliminarserviciocreado->rowCount() > 0) {
                $registrar = false;
                if($servicio_nuevo_id == "") {
                    $categoria_id = 12; // OTROS
                    $registrarservicio = $owebservice->RegistrarServicio($nombre_servicio, $categoria_id);
                    if($registrarservicio->rowCount() > 0) {
                        //OBTENGO SERVICIO_ID
                        while($fila=$registrarservicio->fetch()) {
                            $servicio_nuevo_id = utf8_encode($fila["mid"]);
                            $estrellas = "0";
                            $opiniones = "0";
                            $registrar = true;
                        }
                    }
                } else {
                    $registrar = true;
                }
                if($registrar) {
                    $registrarserviciocreado = $owebservice->RegistrarServicioCreado($usuario_id, $servicio_nuevo_id, $descripcion, $ubicacion, $horario);
                    if($registrarserviciocreado->rowCount() > 0) {
                        $retorno = "1";
                    }
                }   
            }
        }

        $datos = array("rpta" => $retorno, "id_nuevo_s" => $servicio_nuevo_id, "estrellas" => $estrellas, "opiniones" => $opiniones);
        echo json_encode($datos);
    } else if($_GET['accion']=='DATOS_SERVIDOR') {
        $detalleservicio_id=$_POST["detalleservicio_id"];
        $datos = array();
        $datosservidor = $owebservice->DatosServidor($detalleservicio_id);
        while($fila=$datosservidor->fetch()) {
            array_push($datos, array(
                "nombre" => utf8_encode($fila["nombre"]),
                "telefono" => utf8_encode($fila["telefono"]),
                "foto" => utf8_encode($fila["foto"]),
                "descripcion" => utf8_encode($fila["descripcion"]),
                "nombre_servicio" => utf8_encode($fila["nombre_servicio"]),
                "estrellas" => utf8_encode($fila["estrellas"]),
                "horario" => utf8_encode($fila["horario"]),
                "sexo" => utf8_encode($fila["sexo"]),
                "edad" => utf8_encode($owebservice->CalculaEdad($fila["edad"])),
                "direccion" => utf8_encode($fila["direccion"]),
                "ruc" => utf8_encode($fila["ruc"]),
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='ELIMINAR_SERVICIO_CREADO') {
        $usuario_id=$_POST["usuario_id"];
        $servicio_id=$_POST["servicio_id"];
        $datos = array();
        $eliminarserviciocreado = $owebservice->EliminarServicioCreado($usuario_id, $servicio_id);
        $retorno = "2";
        if($eliminarserviciocreado->rowCount() > 0) {
            $retorno = "1";
        }
        $datos = array(
            "rpta" => $retorno,
        );
        echo json_encode($datos);
    } else if($_GET['accion']=='LISTA_OPINIONES') {
        $detalleservicio_id=$_POST["detalleservicio_id"];
        $datos = array();
        $listaopiniones = $owebservice->ListaOpiniones($detalleservicio_id);
        while($fila=$listaopiniones->fetch()) {
            $d_fecha = $dias[strftime("%w", strtotime($fila["fecha"]))];
            $m_fecha = $meses[date("m", strtotime($fila["fecha"])) - 1];

            $fecha = $d_fecha . ", " . date("d", strtotime($fila["fecha"])) . " de " . $m_fecha . " del " . date("Y", strtotime($fila["fecha"]));
            array_push($datos, array(
                "id" => utf8_encode($fila["id"]),
                "nombre" => utf8_encode($fila["nombre"]),
                "foto" => utf8_encode($fila["foto"]),
                "opinion" => utf8_encode($fila["opinion"]),
                "puntuacion" => utf8_encode($fila["puntuacion"]),
                "antiguedad" => $fecha,
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='REGISTRAR_MENSAJE') {
        $servicio_id=$_POST["servicio_id"];
        $receptor_id=$_POST["receptor_id"];
        $emisor_id=$_POST["emisor_id"];
        $mensaje=$_POST["mensaje"];
        $datos = array();
        $registrarmensaje = $owebservice->RegistrarMensaje($servicio_id, $receptor_id, $emisor_id, $mensaje);
        $retorno = "2";
        if($registrarmensaje->rowCount() > 0) {
            $retorno = "1";
        }
        $datos = array(
            "rpta" => $retorno,
        );
        echo json_encode($datos);
    } else if($_GET['accion']=='REGISTRAR_LLAMADA') {
        $servicio_id=$_POST["servicio_id"];
        $receptor_id=$_POST["receptor_id"];
        $emisor_id=$_POST["emisor_id"];
        $datos = array();
        $registrarllamada = $owebservice->RegistrarLlamada($servicio_id, $receptor_id, $emisor_id);
        $retorno = "2";
        if($registrarllamada->rowCount() > 0) {
            $retorno = "1";
        }
        $datos = array(
            "rpta" => $retorno,
        );
        echo json_encode($datos);
    } else if($_GET['accion']=='LISTA_MENSAJES') {
        $usuario_id=$_POST["usuario_id"];
        $SQLidsDistintos=$_POST["SQLidsDistintos"];
        $datos = array();
        $listamensajes = $owebservice->ListaMensajes($usuario_id, $SQLidsDistintos);
        while($fila=$listamensajes->fetch()) {

            $d_fecha = $dias[strftime("%w", strtotime($fila["fecha"]))];
            $m_fecha = $meses[date("m", strtotime($fila["fecha"])) - 1];

            $fecha = $d_fecha . ", " . date("d", strtotime($fila["fecha"])) . " de " . $m_fecha . " del " . date("Y", strtotime($fila["fecha"]));

            $hora = date("h:i A", strtotime($fila["fecha"]));

            array_push($datos, array(
                "id" => utf8_encode($fila["id"]),
                "nombre" => utf8_encode($fila["nombre"]),
                "foto" => utf8_encode($fila["foto"]),
                "mensaje" => utf8_encode($fila["mensaje"]),
                "servicio" => utf8_encode($fila["servicio"]),
                "servicio_id" => utf8_encode($fila["servicio_id"]),
                "telefono" => utf8_encode($fila["telefono"]),
                "user_id" => utf8_encode($fila["user_id"]),
                "antiguedad" => $fecha . " A LAS " . $hora,
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='ESTADO_FAVORITO') {
        $usuario_id=$_POST["usuario_id"];
        $favorito_id=$_POST["favorito_id"];
        $servicio_id=$_POST["servicio_id"];
        $estado=$_POST["estado"];
        $est = "0";
        $datos = array();
        if($estado == "1") {
            $estadofavorito = $owebservice->EliminarFavorito($usuario_id, $favorito_id, $servicio_id);
        } else if($estado == "0") {
            $estadofavorito = $owebservice->CrearFavorito($usuario_id, $favorito_id, $servicio_id);
            $est = "1";
        }
        $retorno = "2";
        if($estadofavorito->rowCount() > 0) {
            $retorno = "1";
            $estado = "0";
        }
        $datos = array(
            "rpta" => $retorno,
            "estado" => $est,
        );
        echo json_encode($datos);
    } else if($_GET['accion']=='LISTA_FAVORITOS') {
        $usuario_id=$_POST["usuario_id"];
        $SQLidsDistintos=$_POST["SQLidsDistintos"];
        $datos = array();
        $listfavoritos = $owebservice->ListaFavoritos($usuario_id, $SQLidsDistintos);
        while($fila=$listfavoritos->fetch()) {
            array_push($datos, array(
                "id" => utf8_encode($fila["id"]),
                "nombre" => utf8_encode($fila["nombre"]),
                "favorito_id" => utf8_encode($fila["favorito_id"]),
                "foto" => utf8_encode($fila["foto"]),
                "servicio" => utf8_encode($fila["servicio"]),
                "servicio_id" => utf8_encode($fila["servicio_id"]),
                "estrellas" => utf8_encode($fila["nestrellas"]==NULL||$fila["nestrellas"]==""?"0":$fila["nestrellas"]),
                "telefono" => utf8_encode($fila["telefono"])
            ));
        }
        echo json_encode($datos);
    } else if($_GET['accion']=='RECUPERAR_CONTRA') {
        $telefono=trim($_POST["telefono"]);
        $dni=trim($_POST["dni"]);
        $datos = array();
        $retorno = "2";
        $contra = "";
        $consultacontra = $owebservice->RecuperarContra($telefono, $dni);
        if($consultacontra->rowCount() > 0) {
            $retorno = "1";
            while($fila=$consultacontra->fetch()) {
                $contra = $fila["password"];
            }
        }
        $datos = array(
            "rpta" => $retorno,
            "contra" => $contra,
        );
        echo json_encode($datos);
    } else if($_GET['accion']=='MODIFICAR_UBICACION') {
        $usuario_id=trim($_POST["usuario_id"]);
        $sesion_id=trim($_POST["sesion_id"]);
        $latitud=trim($_POST["latitud"]);
        $longitud=trim($_POST["longitud"]);
        $direccion=trim($_POST["direccion"]);
        $datos = array();
        $retorno = "2";
        $modificarubicacion = $owebservice->ModificarUbicacion($sesion_id, $usuario_id, $latitud, $longitud, $direccion);
        if($modificarubicacion->rowCount() > 0) {
            $retorno = "1";
        }
        $datos = array(
            "rpta" => $retorno,
        );
        echo json_encode($datos);
    } else if($_GET['accion']=='CREAR_NUEVA_SESION') {
        $usuario_id=trim($_POST["usuario_id"]);
        $latitud=trim($_POST["latitud"]);
        $longitud=trim($_POST["longitud"]);
        $direccion=trim($_POST["direccion"]);
        $datos = array();
        $retorno = "2";
        $sesion_id = "";
        $crearnuevasesion = $owebservice->RegistrarSesion($usuario_id, $latitud, $longitud, $direccion);
        if($crearnuevasesion->rowCount() > 0) {
            $retorno = "1";
            while($fila=$crearnuevasesion->fetch()) {
                $sesion_id = $fila["id"];
            }
        }
        $datos = array(
            "rpta" => $retorno,
            "sesion_id" => $sesion_id,
        );
        echo json_encode($datos);
    } else if($_GET['accion']=='REGISTRAR_OPINION') {
        $usuario_id=trim($_POST["usuario_id"]);
        $servidor_id=trim($_POST["servidor_id"]);
        $servicio_id=trim($_POST["servicio_id"]);
        $estrellas=trim($_POST["estrellas"]);
        $opinion=trim($_POST["opinion"]);

        $opinion_id = "";
        $nombre = "";
        $foto = "";
        $fecha = "";

        $datos = array();
        $retorno = "2";
        $registraropinion = $owebservice->RegistrarOpinion($usuario_id, $servidor_id, $servicio_id, $estrellas, $opinion);
        if($registraropinion->rowCount() > 0) {
            while($fila=$registraropinion->fetch()) {

                $d_fecha = $dias[strftime("%w", strtotime($fila["fecha"]))];
                $m_fecha = $meses[date("m", strtotime($fila["fecha"])) - 1];

                $fecha = $d_fecha . ", " . date("d", strtotime($fila["fecha"])) . " de " . $m_fecha . " del " . date("Y", strtotime($fila["fecha"]));

                $opinion_id = $fila["id"];
                $nombre = $fila["nombre"];
                $foto = $fila["foto"];

                //RECALCULAR ESTRELLAS
                //ACTUALIZO ESTRELLAS DE DETALLESERVICIO
                $recalculo1 = $owebservice->RecalcularEstrellas1($servidor_id, $servicio_id);
                if($recalculo1->rowCount() > 0) {
                    //ACTUALIZO ESTRELLAS DE USUARIO
                    $recalculo2 = $owebservice->RecalcularEstrellas2($servidor_id);
                    if($recalculo2->rowCount() > 0) {
                        $retorno = "1";
                    }
                }
            }
        }
        $datos = array(
            "rpta" => $retorno,
            "id" => $opinion_id,
            "nombre" => $nombre,
            "foto" => $foto,
            "fecha" => $fecha,
        );
        echo json_encode($datos);
    } else if($_GET['accion']=='QUITAR_FAVORITO') {
        $favorito_id=trim($_POST["id"]);
        $datos = array();
        $retorno = "2";
        $quitarfavorito = $owebservice->QuitarFavorito($favorito_id);
        if($quitarfavorito->rowCount() > 0) {
            $retorno = "1";
        }
        $datos = array(
            "rpta" => $retorno,
        );
        echo json_encode($datos);
    }
?>
