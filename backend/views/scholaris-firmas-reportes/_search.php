<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFirmasReportesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-firmas-reportes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'template_id') ?>

    <?= $form->field($model, 'codigo_reporte') ?>

    <?= $form->field($model, 'principal_cargo') ?>

    <?= $form->field($model, 'principal_nombre') ?>

    <?php // echo $form->field($model, 'secretaria_cargo') ?>

    <?php // echo $form->field($model, 'secretaria_nombre') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
