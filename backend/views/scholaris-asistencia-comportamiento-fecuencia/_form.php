<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoFecuencia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-asistencia-comportamiento-fecuencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'detalle_id')->hiddenInput(['value' => $detalleId])->label(false);
    }else{
        echo $form->field($model, 'detalle_id')->hiddenInput()->label(false);
    }
     ?>

    <?= $form->field($model, 'fecuencia')->textInput() ?>

    <?= $form->field($model, 'puntos')->textInput() ?>

    <?= $form->field($model, 'accion')->dropDownList(
            [
                0 => 'Sin accion',
                1 => 'Aumenta',
                -1 => 'Disminuye',
            ]
            ) ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'alerta')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
