<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisReporteNovedadesComportamientoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-reporte-novedades-comportamiento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'novedad_id') ?>

    <?= $form->field($model, 'bloque') ?>

    <?= $form->field($model, 'semana') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'hora') ?>

    <?php // echo $form->field($model, 'materia') ?>

    <?php // echo $form->field($model, 'estudiante') ?>

    <?php // echo $form->field($model, 'curso') ?>

    <?php // echo $form->field($model, 'paralelo') ?>

    <?php // echo $form->field($model, 'codigo') ?>

    <?php // echo $form->field($model, 'falta') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'justificacion') ?>

    <?php // echo $form->field($model, 'usuario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
