<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisAsistenciaProfesor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-asistencia-profesor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'clase_id')->textInput() ?>

    <?= $form->field($model, 'hora_id')->textInput() ?>

    <?= $form->field($model, 'hora_ingresa')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'creado')->textInput() ?>

    <?= $form->field($model, 'modificado')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
