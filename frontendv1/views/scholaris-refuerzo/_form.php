<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisRefuerzo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-refuerzo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'grupo_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'bloque_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'orden_calificacion')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'promedio_normal')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'nota_refuerzo')->textInput() ?>

    <?= $form->field($model, 'nota_final')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
