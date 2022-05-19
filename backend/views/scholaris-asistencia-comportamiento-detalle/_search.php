<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoDetalleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-asistencia-comportamiento-detalle-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'comportamiento_id') ?>

    <?= $form->field($model, 'codigo') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'cantidad_descuento') ?>

    <?php // echo $form->field($model, 'punto_descuento') ?>

    <?php // echo $form->field($model, 'total_x_unidad') ?>

    <?php // echo $form->field($model, 'code_fj') ?>

    <?php // echo $form->field($model, 'activo')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
