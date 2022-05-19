<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaDisribucion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mec-v2-malla-disribucion-form">

    <?php echo Html::beginForm(['create', 'post']); ?>
    
    <!--<input type="text" name="mate" value="<?= $modelMateria->id ?>">-->
    <input type="hidden" name="mate" value="<?= $modelMateria->id ?>">

    <div class="form-group">
        <select name="tipo" class="form-control" required="" 
                onclick="mostrarMaterias(this, '<?= $modelMateria->area->malla_id ?>', '<?= Url::to(["cajas-select/materiasmalla"]) ?>')">
            <option value="">Seleccione tipo ....</option>
            <option value="AREA">AREA</option>
            <option value="MATERIA">MATERIA</option>
        </select>   
    </div>

    <div class="form-group">
        <div id="materias"></div>
    </div>

    <?php echo Html::submitButton('Aceptar', ['class' => 'btn btn-primary']); ?>

    <?php echo Html::endForm(); ?>

</div>

<script>

    function mostrarMaterias(obj, malla, url) {
//        alert('ola k se');
        console.log(obj.value);
        console.log(url);

        var parametros = {
            "tipo": $(obj).val(),
            "malla": malla
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#materias").html(response);

            }
        });

    }

</script>