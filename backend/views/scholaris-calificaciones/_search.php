<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisCalificacionesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-calificaciones-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idalumno') ?>

    <?= $form->field($model, 'idactividad') ?>

    <?= $form->field($model, 'idtipoactividad') ?>

    <?= $form->field($model, 'idperiodo') ?>

    <?php // echo $form->field($model, 'calificacion') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'criterio_id') ?>

    <?php // echo $form->field($model, 'estado_proceso') ?>

    <?php // echo $form->field($model, 'grupo_numero') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
