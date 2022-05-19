<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanSemanalDestrezasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-plan-semanal-destrezas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'curso_id') ?>

    <?= $form->field($model, 'faculty_id') ?>

    <?= $form->field($model, 'semana_id') ?>

    <?= $form->field($model, 'comparte_valor') ?>

    <?= $form->field($model, 'concepto') ?>

    <?php // echo $form->field($model, 'contexto') ?>

    <?php // echo $form->field($model, 'pregunta_indagacion') ?>

    <?php // echo $form->field($model, 'enfoque') ?>

    <?php // echo $form->field($model, 'creado_por') ?>

    <?php // echo $form->field($model, 'creado_fecha') ?>

    <?php // echo $form->field($model, 'actualizado_por') ?>

    <?php // echo $form->field($model, 'actualizado_fecha') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
