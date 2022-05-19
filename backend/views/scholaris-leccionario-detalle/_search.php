<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisLeccionarioDetalleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-leccionario-detalle-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'paralelo_id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'hora_id') ?>

    <?php // echo $form->field($model, 'desde') ?>

    <?php // echo $form->field($model, 'hasta') ?>

    <?php // echo $form->field($model, 'asistencia_id') ?>

    <?php // echo $form->field($model, 'falta')->checkbox() ?>

    <?php // echo $form->field($model, 'atraso') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
