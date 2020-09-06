<?php //session_start();
$id     = $_SESSION['id'];
$cuenta = $_SESSION['cuenta'];
$email  = $_SESSION['email'];
?>

<script>
  $(document).ready(function () {
    (function ($) {
      $('.buscal').keyup(function () {
        var rex = new RegExp($(this).val(), 'i');
        $('.detal tbody tr').hide();
        $('.detal tbody tr').filter(function () {
          return rex.test($(this).text());
        }).show();
      })
    }(jQuery));
    $("#valortexto").keypress(function(tecla) {
      if(tecla.charCode === 39 || tecla.charCode === 34) {
        return false;
      }
    });
  });

  $(document).ready(function () {
    (function ($) {
      $('.buscal2').keyup(function () {
        var rex = new RegExp($(this).val(), 'i');
        $('.detal2 tbody tr').hide();
        $('.detal2 tbody tr').filter(function () {
          return rex.test($(this).text());
        }).show();
      })
    }(jQuery));
  }); 

  // Lógica para la configucacion del mensaje 
  function addCampo() {
    var campoAdd = $("#campotexto").val();
    var campo = $("#valortexto").val() + " " + campoAdd + "";
    $("#valortexto").val(campo);
    $("#valortexto").focus();
  }

  function guardarMensaje() {
    // Llamado AJAX para guardar configuracion
    if($("#valortexto").val() === "") {
      $('#mensajeConfigTexto').html('<p style="color: red;"><i class="icon-check"></i>No puedes dejar el mensaje vacio.</p>');
      $("#valortexto").focus();
    } else if($("#mainadmin").val() === "") {
      $('#mensajeConfigTexto').html('<p style="color: red;"><i class="icon-check"></i>Debes escoger un administrador para que le lleguen los mensajes.</p>');
    } else {
      $.ajax({
        type: "POST",
        url: "../controlador/contTelefonos.php?accion=nuevoConfiguracionMensaje&mensaje=" + $("#valortexto").val() + "&principal=" + $("#mainadmin").val(),
        beforeSend: function() {
          $("#mensajeConfigTexto").html('<p style="color: blue;">Cargando...</p>');
        },
        success: function(a) {
          $('#mensajeConfigTexto').html(a);
        }
      });
    }
  }

  function inicializarConfiguracionMensaje() {
    // Llamado AJAX para guardar configuracion
    $.ajax({
      type: "POST",
      url: "../controlador/contTelefonos.php?accion=inicializarConfiguracionMensaje",
      success: function(a) {
        $('#valortexto').val(a);
        $('#mensajeConfigTexto').html("");
        $('#zonamainadmin').html("");
        cargarSelectAdministradores();
      }
    });
  }

  function cargarSelectAdministradores() {
    $.ajax({
      type: "GET",
      url: "../controlador/contPersonal.php?accion=cargarSelectAdministradores",
      success: function(a) {
        $('#zonamainadmin').html(a);
        $('#mainadmin').chosen({
          width: "100%",
        });
      }
    });
  }
</script>

<!-- Modal de Eliminar -->

<div id="deleteModal" class="modal fade" role="dialog">
    <div class="modal-dialog  modalChico" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
            <div class="col-md-9">
              <h4 class="titulo modal-title"><b style="color:red;"><i class="icon-warning-sign"></i> Eliminar??</b></h4>
            </div>
          </div>
        </div>
        <div class="modal-body">          
          <div id="mens_resultado"></div>
          <div id="mens_alerta">
            <h4 class="titulo modal-title"><b style="color:blue;">Estás seguro que deseas eliminar <b id="table"></b>: <br> <b style="color:green" id="nombre_cc"></b>? <hr> <b style="color:orange">Se eliminarán todos sus datos. Esto repercutirá en la integridad de los datos.</b></b></h4>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
          <button id="elimina2" type="button" class="btn btn-success">Aceptar</button>
        </div>
    </div>
  </div>
</div>

<!-- Fin Modal Eliminar -->

<!-- Modal Cambio de Contraseña -->

<div id="changePassModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
              <div class="col-md-12">
                <button type="button" class="close"><font id="cargando"></font></button>
                <h5 class="titulo modal-title"><b style="color:green;"><i class="icon-edit"></i> Cambio de Contraseña</b></h5>
            </div>
          </div>
          <div class="modal-body" id="mens_elim">
            <form role="form">
              <div class="form-group">
                <label for="passAnt label-sm">Contraseña Anterior</label>
                <input type="password" class="form-control input-sm" id="passAnt"
                    placeholder="Contraseña Anterior" autofocus="" autocomplete="on">
                <span class="spn_form_personal" id="spn_passant">Ingresa Contraseña Anterior.</span>
              </div>
              <div class="form-group">
                <label for="passNue label-sm">Contraseña Nueva</label>
                <input type="password" class="form-control input-sm" id="passNue"
                    placeholder="Contraseña Nueva">
                <span class="spn_form_personal" id="spn_passnue"></span>
              </div>
              <div class="form-group">
                <label for="passNue2 label-sm">Repite</label>
                <input type="password" class="form-control input-sm" id="passNue2"
                    placeholder="Contraseña Nueva">
              </div>
              <div class="form-group">
                <div class="col-md-12 text-center">
                  <font class="text-center titulo" id="sms_changepass"></font>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            <button id="changePass" type="button" class="btn btn-success">Aceptar</button>
          </div>
      </div>
    </div>
</div>

<!-- Fin Modal Cambio de Contraseña -->

<!-- Modal Propiedades de usuario -->

<div id="propUsuModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">            
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-9">
                        <button type="button" class="close"><font id="cargando"></font></button>
                        <h3 class="titulo modal-title"><b style="color:green;"><i class="icon-edit"></i> Propiedades de tu Usuario <b id="usuario" style="color: red"></b></b></h3>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-7">
                    <div id="propuustecnicos">
                      <div class="row">
                          <div class="col-md-6">
                              <h3 class="titulo"><b style="color:blue;">Habilitación</h3>
                          </div>
                          <div class="col-md-6">
                              <div class="text-center">
                                  <br>
                                  <div id="divestadousuario"></div>
                              </div>
                          </div>
                      </div>
                      <hr>
                      <div class="row">
                          <div class="col-md-6">
                              <h3 class="titulo"><b style="color:blue;">Reseteo</h3>
                          </div>
                          <div class="col-md-6">
                              <div class="text-center">
                                  <br>
                                  <div id="divresetearclave"></div>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="text-center">
                              <h5 class="titulo" id="mensajeResetClave" style="color:green;"></h5>
                          </div>
                      </div>
                    </div>
                    <div id="propusupropio">
                      <h4 class="titulo" style="color: blue;"><b><i class="icon-user-md"></i> Cambio de Contraseña</b></h4>
                      <br>
                      <form role="form" id="formularioCambioClave" action="">
                        <div class="form-group input-group">
                            <span class="input-group-addon">Clave actual</span>
                            <input type="password" class="form-control input-sm" name="claveactual" id="claveactual">
                        </div>
                        <h5 id="spanclaveactual" style="color: red;"></h5>
                        <hr>
                        <div class="form-group input-group">
                            <span class="input-group-addon">Clave nueva x1</span>
                            <input type="password" class="form-control input-sm" name="clavenueva1" id="clavenueva1">
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon">Clave nueva x2</span>
                            <input type="password" class="form-control input-sm" name="clavenueva2" id="clavenueva2">
                        </div>
                        <h5 class="titulo" id="spanclavenueva" style="color: red;"></h5>
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-1">
                                <a href="#" class="grabar btn btn-success" data-bean="CambioClave"><i class="icon-save"></i> Grabar</a>
                            </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="col-md-5">
                      <img src="../assets/img/user.png" class="img img-responsive">
                  </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Fin Modal Propiedades de usuario -->

<!-- Modal de Configuración de Mensaje -->

<div id="modalConfiguracionMensaje" class="modal fade modal-lg" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
            <div class="col-md-9">
              <h4 class="titulo modal-title"><b style="color:green;"><i class="icon-pencil"></i> Configuración de Mensaje de Texto</b></h4>
            </div>
          </div>
        </div>
        <div class="modal-body">
          <form role="form" action="../controlador/contPersonal.php?accion=" onsubmit="return false;">
            <div class="form-group input-group" id="zonamainadmin"></div>
            <div class="form-group input-group">
              <span class="input-group-addon">Campo</span>
              <select type="text" class="form-control input-sm" name="campotexto" id="campotexto">
                <option value="[nombre]">Nombre de cliente</option>
                <option value="[numero]">Numero de Telefono</option>
              </select>
              <span class="input-group-addon" onclick="addCampo();" style="background-color: green; color: white; cursor: pointer;">+ Agregar</span>
            </div>
            <span class="input-group-addon">Estructura del mensaje</span>
            <div class="row">
              <div class="form-group">
                <div class="col-md-12">
                  <textarea type="text" class="form-control input-sm" style="font-size: 15px;" name="valortexto" id="valortexto" maxlength="300" rows="6"></textarea>
                </div>
              </div>              
            </div>
            <div class="row">
              <div class="text-center">
                <h5 class="titulo" id="mensajeConfigTexto"></h5><br>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-5"></div>
              <div class="col-lg-1">
                <a href="#" onclick="guardarMensaje();" class="grabarMensaje btn btn-success"><i class="icon-save"></i> Grabar</a>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
  </div>
</div>

<!-- Fin Modal Eliminar -->