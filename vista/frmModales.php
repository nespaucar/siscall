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
  });  
</script>

<script>
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
                <h5 class="modal-title"><b style="color:green;"><i class="icon-edit"></i> Cambio de Contraseña</b></h5>
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
                  <font class="text-center" id="sms_changepass"></font>
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
                        <h3 class="modal-title" style="font-family: 'Oswald', sans-serif;"><b style="color:green;"><i class="icon-edit"></i> Propiedades de tu Usuario <b id="usuario" style="color: red"></b></b></h3>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-7">
                    <div id="propuustecnicos">
                      <div class="row">
                          <div class="col-md-6">
                              <h3><b style="color:blue;font-family: 'Oswald', sans-serif;"><i class="glyphicon glyphicon-remove-circle"></i> Habilitación</h3>
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
                              <h3><b style="color:blue;font-family: 'Oswald', sans-serif;"><i class="glyphicon glyphicon-screenshot"></i> Reseteo</h3>
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
                              <h5 id="mensajeResetClave" style="color:green;font-family: 'Oswald', sans-serif;"></h5>
                          </div>
                      </div>
                    </div>
                    <div id="propusupropio">
                      <h4 style="color: blue;font-family: 'Oswald', sans-serif;"><b><i class="icon-user-md"></i> Cambio de Contraseña</b></h4>
                      <br>
                      <form role="form" id="formularioCambioClave" action="">
                        <div class="form-group input-group">
                            <span class="input-group-addon">Clave actual</span>
                            <input type="password" class="form-control input-sm" name="claveactual" id="claveactual">
                        </div>
                        <h5 id="spanclaveactual" style="color: red;font-family: 'Oswald', sans-serif;"></h5>
                        <hr>
                        <div class="form-group input-group">
                            <span class="input-group-addon">Clave nueva x1</span>
                            <input type="password" class="form-control input-sm" name="clavenueva1" id="clavenueva1">
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon">Clave nueva x2</span>
                            <input type="password" class="form-control input-sm" name="clavenueva2" id="clavenueva2">
                        </div>
                        <h5 id="spanclavenueva" style="color: red;font-family: 'Oswald', sans-serif;"></h5>
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