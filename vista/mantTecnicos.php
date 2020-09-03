<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
include "../modelo/clsPersonal.php";

$id        = '';
$nombres   = '';
$apellidos = '';
$id_AB     = '';
$DNI       = '';
$direccion = '';
$telefono  = '';
$email     = '';

if ($_GET['accion'] == 'modificar') {
    $personal = new Personal();
    $rs       = $personal->cargardatospersona($_GET['id'], $_SESSION['idempresa']);
    foreach ($rs as $dato) {
        $id        = $dato[0];
        $nombres   = $dato[1];
        $apellidos = $dato[2];
        $id_AB     = $dato[3];
        $DNI       = $dato[4];
        $direccion = $dato[5];
        $telefono  = $dato[6];
        $email     = $dato[7];
    }
}
?>
<script>
    $(document).ready(function(){
        $('#nombres').focus();
    })
</script>
<div class="col-lg-12">
    <div class="col-lg-3">
        <div class="text-center">
            <br><br><img class="img img-responsive img-thumbnail" src="../assets/img/tecnico.jpg" alt="">
        </div>
    </div>
    <div class="col-lg-6">
        <h4 class="titulo" style="color: blue"><b><i class="icon-user-md"></i> &nbsp;<?php echo ucwords($_GET['accion']); ?></b></h4>
        <hr>
        <form role="form" id="formulario" action="../controlador/contPersonal.php?accion=<?php echo $_GET['accion']; ?>&id=<?php echo $id; ?>&tipo=2">
            <div class="form-group input-group">
                <span class="input-group-addon">Nombres</span>
                <input type="text" class="form-control input-sm" name="nombres" id="nombres" maxlength="80" value="<?php echo $nombres; ?>">
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">Apellidos</span>
                <input type="text" class="form-control input-sm" name="apellidos" id="apellidos" maxlength="130" value="<?php echo $apellidos; ?>">
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">Número de Carnet</span>
                <input type="text" class="form-control input-sm" name="id_AB" id="id_AB" maxlength="6" value="<?php echo $id_AB; ?>">
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">DNI</span>
                <input type="text" class="form-control input-sm" name="DNI" id="DNI" maxlength="8" value="<?php echo $DNI; ?>">
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">Dirección</span>
                <input type="text" class="form-control input-sm" name="direccion" id="direccion" maxlength="100" value="<?php echo $direccion; ?>">
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">Teléfono</span>
                <input type="text" class="form-control input-sm" name="telefono" id="telefono" maxlength="9" value="<?php echo $telefono; ?>">
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">Correo</span>
                <input type="text" class="form-control input-sm" name="email" id="email" maxlength="100" value="<?php echo $email; ?>">
            </div>
            <div class="row">
                <div class="col-lg-5"></div>
                <div class="col-lg-1">
                    <a href="#" class="grabar btn btn-success" data-bean='Tecnicos' data-accion="<?php echo $_GET['accion']; ?>"><i class="icon-save"></i> Grabar</a>
                </div>
            </div>
            <?php if ($_GET['accion'] == 'modificar') {?>
                <input type="hidden" id="id_AB_anterior" name="id_AB_anterior" value="<?php echo $id_AB; ?>">
                <input type="hidden" id="DNI_anterior" name="DNI_anterior" value="<?php echo $DNI; ?>">
                <input type="hidden" id="correo_anterior" name="correo_anterior" value="<?php echo $email; ?>">
            <?php }?>
        </form>
    </div>
    <div class="col-lg-3">
        <h4 class="titulo" style="color: #1FA463"><b><i class="icon-warning-sign"></i> &nbsp;Mensajes</b></h4>
        <hr>
        <div id="mensajes">
            <p style="color: blue;"><i class="icon-check"></i> Ingresa los datos requeridos.</p>
        </div>
    </div>
</div>
