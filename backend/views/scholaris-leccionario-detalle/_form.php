<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisLeccionarioDetalle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-leccionario-detalle-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'paralelo_id')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'clase_id')->textInput() ?>

    <?= $form->field($model, 'hora_id')->textInput() ?>

    <?= $form->field($model, 'desde')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hasta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'asistencia_id')->textInput() ?>

    <?= $form->field($model, 'falta')->checkbox() ?>

    <?= $form->field($model, 'atraso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
