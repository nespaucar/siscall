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

<!-- Modal de Paquete -->

<div id="listarPaquete" class="modal" role="dialog">
    <div class="modal-dialog modalMedio" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="row">
              <div class="col-md-9">
                <div class="row">
                  <div class="col-md-5">
                    <h4 class="titulo modal-title"><b style="color:blue;"><i class="icon-briefcase"></i> PAQUETE</b> <b id="numpaquetin"></b></h4>
                  </div>
                  <div class="col-md-7" style="text-align: center">
                    <b id="alertaExisteProducto"></b>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="modal-body cuerpolargo">
          <div class="col-md-12">
            <b style="color:green;">Elige los productos para tu paquete:</b>
            <div class="cargarDatos"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
  </div>
</div>

<!-- Fin Modal Paquete -->

<!-- Modal Importar -->

<div id="cargarExcel" class="modal fade" role="dialog">
  <div class="modal-dialog modalMedio">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
              <div class="col-md-12">
                <button type="button" class="close"><font id="cargando"></font></button>
                <h3 class="modal-title" style="font-family: 'Oswald', sans-serif;"><b style="color:green;"><i class="icon-file"></i> Importar <font id="beanFileExcel"></font></b></h3>
            </div>
          </div>
          <div class="modal-body cuerpolargo" id="mens_elim">
            <form id="formularionuevosproductos" enctype="multipart/form-data">
              <div class="form-group">
                <label for="label-sm">INDICACIÓN</label>
                <span style="color: green">Seleccione el archivo con extensión .xlsx que se necesita para importar los datos indicados.</span>
              </div>
              <div class="form-group">
                <label for="label-sm">Seleccione archivo</label>
                <input type="file" id="fileExcel" name="fileExcel" class="form-control input-sm">         
              </div>
              <div class="form-group">
                <p id="mensajeFileExcel"></p>                
              </div>
              <div class="form-group text-center">
                <p id="mensajeFinalFileExcel"></p>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button id="aceptarFileExcel" type="button" class="btn btn-success">Importar</button>
          </div>
      </div>
    </div>
</div>

<!-- Fin Modal Importar -->

<!-- Modal VerEquiposMateriales -->

<div id="detallesequiposmateriales" class="modal fade" role="dialog">
  <div class="modal-dialog modalMedio">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
              <div class="col-md-12">
                <button type="button" class="close"><font id="cargando"></font></button>
                <h3 class="modal-title" style="font-family: 'Oswald', sans-serif;"><b style="color:green;"><i class="glyphicon glyphicon-list-alt"></i> Materiales de Guía de Remisión N° <font id="beanGuiaRemision"></font></b></h3>
            </div>
          </div>
          <div class="modal-body cuerpolargo">                       
            <div class="form-group text-center">
              <table class="table table-sm table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="color: black" width="5%">#</th>
                        <th style="color: black">CANT.</th>
                        <th style="color: black">COD_SAP</th>
                        <th style="color: black">DESCRIPCIÓN</th>
                        <th style="color: black">TIPO</th>
                        <th style="color: black">SERIE</th>
                        <th style="color: black">ESTADO</th>
                    </tr>
                </thead>
                <tbody id="mens_detalleGuiaRemision"></tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">CERRAR</button>
          </div>
      </div>
    </div>
</div>

<!-- Fin Modal VerEquiposMateriales -->

<!-- Modal listarDetallesAsignacion -->

<div id="listarDetallesAsignacion" class="modal fade" role="dialog">
  <div class="modal-dialog modalMedio">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
              <div class="col-md-12">
                <button type="button" class="close"><font id="cargando"></font></button>
                <h3 class="modal-title" style="font-family: 'Oswald', sans-serif;"><b style="color:green;"><i class="glyphicon glyphicon-list-alt"></i> Materiales de Asignación N° <font id="beanAsignacion"></font></b></h3>
            </div>
          </div>
          <div class="modal-body cuerpolargo">            
            <div class="form-group text-center">
              <table class="table table-sm table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="color: black" width="5%">#</th>
                        <th style="color: black">CANT.</th>
                        <th style="color: black">COD_SAP</th>
                        <th style="color: black">DESCRIPCIÓN</th>
                        <th style="color: black">TIPO</th>
                        <th style="color: black">SERIE</th>
                        <th style="color: black">ESTADO</th>
                    </tr>
                </thead>
                <tbody id="mens_detalleAsignacion"></tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">CERRAR</button>
          </div>
      </div>
    </div>
</div>

<!-- Fin Modal listarDetallesAsignacion -->

<!-- Modal listarDetallesEquipoMaterial -->

<div id="listarDetallesEquipoMaterial" class="modal fade" role="dialog">
  <div class="modal-dialog modalMedio">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
              <div class="col-md-12">
                <button type="button" class="close"><font id="cargando"></font></button>
                <h3 class="modal-title" style="font-family: 'Oswald', sans-serif;"><b style="color:green;"><i class="glyphicon glyphicon-list-alt"></i> Catálogo de equipos: <font id="beanDetallesEquipoMaterial"></font></b></h3>
            </div>
          </div>
          <div class="modal-body cuerpolargo"> 
            <div class="form-group input-group">
                <span class="input-group-addon">Filtrar</span>
                <input type="text" class="buscal form-control input-sm">
            </div>           
            <div class="form-group text-center">
              <table class="table table-sm table-striped table-bordered table-hover detal">
                <thead>
                    <tr>
                        <th style="color: black" width="5%">#</th>
                        <th style="color: black">CANT.</th>
                        <th style="color: black">COD_SAP</th>
                        <th style="color: black">DESCRIPCIÓN</th>
                        <th style="color: black">TIPO</th>
                        <th style="color: black">SERIE</th>
                        <th style="color: black">ESTADO</th>
                    </tr>
                </thead>
                <tbody id="mens_detalleDetallesEquipoMaterial"></tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">CERRAR</button>
          </div>
      </div>
    </div>
</div>

<!-- Fin Modal listarDetallesEquipoMaterial -->

<!-- Modal listarDetallesInstalacion -->

<div id="listarDetallesInstalacion" class="modal fade" role="dialog">
  <div class="modal-dialog modalMedio">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
              <div class="col-md-12">
                <button type="button" class="close"><font id="cargando"></font></button>
                <h3 class="modal-title" style="font-family: 'Oswald', sans-serif;"><b style="color:green;"><i class="glyphicon glyphicon-list-alt"></i> Materiales de Orden N° <font id="beanInstalacion"></font></b></h3>
            </div>
          </div>
          <div class="modal-body cuerpolargo">            
            <div class="form-group text-center">
              <table class="table table-sm table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="color: black" width="5%">#</th>
                        <th style="color: black">CANT.</th>
                        <th style="color: black">COD_SAP</th>
                        <th style="color: black">DESCRIPCIÓN</th>
                        <th style="color: black">TIPO</th>
                        <th style="color: black">SERIE</th>
                        <th style="color: black">ESTADO</th>
                    </tr>
                </thead>
                <tbody id="mens_detalleInstalacion"></tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">CERRAR</button>
          </div>
      </div>
    </div>
</div>

<!-- Fin Modal listarDetallesInstalacion -->

<!-- Modal resAsignacionesModal -->

<div id="resAsignacionesModal" class="modal fade" role="dialog">
  <div class="modal-dialog modalMedio">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-md-12">
          <button type="button" class="close"><font id="cargando"></font></button>
          <h3 class="modal-title" style="font-family: 'Oswald', sans-serif;"><b style="color:green;"><i class="glyphicon glyphicon-list-alt"></i> Resumen Asignaciones: <font id="beanCarnetTecnico"></font></b></h3>
        </div>
      </div>
      <div class="modal-body cuerpolargo">
        <div id="carousel-ejemplo" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner" role="listbox">
            <div class="item itemcillo active">
              <div class="form-group input-group">
                  <span class="input-group-addon">Filtrar</span>
                  <input type="text" class="form-control input-sm buscal">
              </div>           
              <div class="form-group text-center">
                <table class="table table-sm table-striped table-bordered table-hover detal">
                  <thead>
                      <tr>
                          <th style="color: black" width="5%">#</th>
                          <th style="color: black">COD_SAP</th>
                          <th style="color: black">EQUIPO O MATERIAL</th>
                          <th style="color: black">TIPO</th>
                          <th style="color: black">CANTIDAD</th>
                          <th style="color: black">DETALLES</th>
                      </tr>
                  </thead>
                  <tbody id="mens_resAsignacionesModal"></tbody>
                </table>
              </div>
            </div>
            <div class="item">
              <a href="#carousel-ejemplo" class="btn btn-info btn-xs" data-slide="prev"><div class="retorno glyphicon glyphicon-chevron-left"></div> Atras</a>
              CODIGO: <b id="codigequipomaterial" style="color: green"></b> DESCRIPCION: <b id="descequipomaterial" style="color: green"></b> TIPO: <b id="tipequipomaterial" style="color: green"></b> CANTIDAD EN POSESION: <b id="cantequipomaterial" style="color: green"></b>
              <hr>
              <div class="form-group input-group">
                  <span class="input-group-addon">Filtrar</span>
                  <input type="text" class="form-control input-sm buscal2">
              </div>           
              <div class="form-group text-center">
                <table class="table table-sm table-striped table-bordered table-hover detal2">
                  <thead>
                      <tr>
                          <th style="color: black" width="5%">#</th>
                          <th style="color: black">N ASIG.</th>
                          <th style="color: black">FECHA DE ENTREGA</th>
                          <th style="color: black">POSESION</th>
                          <th style="color: black">CANTIDAD</th>
                          <th style="color: black">SERIE</th>
                          <th style="color: black">ESTADO</th>
                      </tr>
                  </thead>
                  <tbody id="TablaDetallesResAsignacionxEquipomaterial"></tbody>
                </table>
              </div>
            </div> 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">CERRAR</button>
      </div>
    </div>
  </div>
</div>

<!-- Fin Modal resAsignacionesModal -->

<!-- Modal aumentarStock -->

<div id="aumentarStock" class="modal fade" role="dialog">
  <div class="modal-dialog modalMedio">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
              <div class="col-md-12">
                <button type="button" class="close"><font id="cargando"></font></button>
                <h3 class="modal-title" style="font-family: 'Oswald', sans-serif;"><b style="color:green;"><i class="glyphicon glyphicon-list-alt"></i> Guía de Remisión Manual de Materiales <font id="beanCarnetTecnico"></font></b></h3>
            </div>
          </div>
          <div class="modal-body cuerpolargo"> 
            <div id="divasmaterial"></div>
            <div class="form-group input-group">
                <span class="input-group-addon">Cantidad</span>
                <input type="text" class="form-control input-sm" name="ascantidad" id="ascantidad">
                <span class="input-group-btn">
                  <a href="#" class="btn btn-success btn-sm aumplus"><i class="icon-plus"></i></a>
              </span>
            </div>           
            <div class="form-group text-center">
              <b id="alertaExisteProduct"></b>
              <br><br>
              <table class="table table-sm table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="color: black" width="25%">COD_SAP</th>
                        <th style="color: black" width="60%">DESCRIPCION DE MATERIAL</th>
                        <th style="color: black" width="10%">CANTIDAD</th>
                        <th style="color: black" width="5%"></th>
                    </tr>
                </thead>
                <tbody id="mens_aumentarStock"></tbody>
              </table>
              <div class="text-center">
                <a href="#" class="btn btn-success" id="anguia">Añadir Guía</a>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">CERRAR</button>
          </div>
      </div>
    </div>
</div>

<!-- Fin Modal previoInstalaciones -->