<?php
session_start();
header("Cache-Control: no-cache");
header("Pragma: no-cache");
include "seguridad.php";
include "../modelo/clsEquipoMaterial.php";

$id          = '';
$codigo      = '';
$descripcion = '';
$tipo        = '';

if ($_GET['accion'] == 'modificar') {
    $equipomaterial = new EquipoMaterial();
    $rs             = $equipomaterial->cargardatosequipomaterial($_GET['id'], $_SESSION['idempresa']);
    foreach ($rs as $dato) {
        $id          = $dato[0];
        $codigo      = $dato[1];
        $descripcion = $dato[2];
        $tipo        = $dato[3];
    }
}
?>
<script>
    $(document).ready(function(){
        $('#codigo').focus();
        <?php if ($_GET['accion'] == 'modificar') {?>
            $('#tipo').val('<?php echo $tipo; ?>');
        <?php }?>
    })
</script>
<div class="col-lg-12">
    <div class="col-lg-3">
        <div class="text-center">
            <br><br><img class="img img-responsive img-thumbnail" src="../assets/img/equipomaterial.jpg" alt="">
        </div>
    </div>
    <div class="col-lg-6">
        <h4 class="titulo" style="color: blue"><b><i class="icon-user-md"></i> &nbsp;<?php echo ucwords($_GET['accion']); ?></b></h4>
        <hr>
        <form role="form" id="formulario" action="../controlador/contEquipoMaterial.php?accion=<?php echo $_GET['accion']; ?>&id=<?php echo $id; ?>">
            <div class="form-group input-group">
                <span class="input-group-addon">Código</span>
                <input type="text" class="form-control input-sm" name="codigo" id="codigo" maxlength="11" value="<?php echo $codigo; ?>">
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">Descripción</span>
                <input type="text" class="form-control input-sm" name="descripcion" id="descripcion" maxlength="100" value="<?php echo $descripcion; ?>">
            </div>
            <div class="form-group input-group">
                <span class="input-group-addon">Tipo</span>
                <select class="form-control input-sm" name="tipo" id="tipo">
                    <option value="1">EQUIPO</option>
                    <option value="2">MATERIAL</option>
                </select>
            </div>
            <div class="row">
                <div class="col-lg-5"></div>
                <div class="col-lg-1">
                    <a href="#" class="grabar btn btn-success" data-bean='EquiposMateriales' data-accion="<?php echo $_GET['accion']; ?>"><i class="icon-save"></i> Grabar</a>
                </div>
            </div>
            <?php if ($_GET['accion'] == 'modificar') {?>
                <input type="hidden" id="codigo_anterior" name="codigo_anterior" value="<?php echo $codigo; ?>">
                <input type="hidden" id="descripcion_anterior" name="descripcion_anterior" value="<?php echo $descripcion; ?>">
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
