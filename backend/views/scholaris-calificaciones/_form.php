<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisCalificaciones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-calificaciones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idalumno')->textInput() ?>

    <?= $form->field($model, 'idactividad')->textInput() ?>

    <?= $form->field($model, 'idtipoactividad')->textInput() ?>

    <?= $form->field($model, 'idperiodo')->textInput() ?>

    <?= $form->field($model, 'calificacion')->textInput() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'criterio_id')->textInput() ?>

    <?= $form->field($model, 'estado_proceso')->textInput() ?>

    <?= $form->field($model, 'grupo_numero')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
