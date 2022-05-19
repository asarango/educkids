<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPudDetalleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-plan-pud-detalle-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'pud_id') ?>

    <?= $form->field($model, 'tipo') ?>

    <?= $form->field($model, 'codigo') ?>

    <?= $form->field($model, 'contenido') ?>

    <?php // echo $form->field($model, 'pertenece_a_codigo') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
