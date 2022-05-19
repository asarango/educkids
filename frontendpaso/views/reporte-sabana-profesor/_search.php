<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisAsistenciaProfesorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-asistencia-profesor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'hora_id') ?>

    <?= $form->field($model, 'hora_ingresa') ?>

    <?= $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'creado') ?>

    <?php // echo $form->field($model, 'modificado') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
