<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisRefuerzoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-refuerzo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'grupo_id') ?>

    <?= $form->field($model, 'bloque_id') ?>

    <?= $form->field($model, 'orden_calificacion') ?>

    <?= $form->field($model, 'promedio_normal') ?>

    <?php // echo $form->field($model, 'nota_refuerzo') ?>

    <?php // echo $form->field($model, 'nota_final') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
