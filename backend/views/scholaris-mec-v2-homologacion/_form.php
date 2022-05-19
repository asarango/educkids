<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Homologacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mec-v2-homologacion-form">

    <?php echo Html::beginForm(['create', 'post']); ?>

    <div class="col-md-3">
        <?php
        echo Select2::widget([
            'name' => 'tipo',
            'value' => 0,
            'data' => ['AREA' => 'AREA', 'ASIGNATURA' => 'ASIGNATURA'],
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione tipo de recurso...',
                'onchange' => 'recurso(this,"' . Url::to(['recurso']) . '",' . $modelDisti->id . ',' . $modelDisti->curso_id . ');',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>
    </div>

    <div class="col-md-3" id="recursoDiv"></div>

    <input type="hidden" name="distribucionId" id="distribucion_id" value="<?= $modelDisti->id ?>">

    <?php echo Html::submitButton('Aceptar', ['class' => 'btn btn-primary']); ?>
    <?php echo Html::endForm(); ?>


</div>


<script>
    function recurso(obj, url, materia, curso) {
        var parametros = {
            "tipo": $(obj).val(),
            "materia": materia,
            "curso": curso
        };

        console.log(parametros);

        $.ajax({
            data: parametros,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                $("#recursoDiv").html(response);
            }
        });
    }
</script>
