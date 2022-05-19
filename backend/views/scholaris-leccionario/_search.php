<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisLeccionarioSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-leccionario-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'paralelo_id') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'total_clases') ?>

    <?= $form->field($model, 'total_revisadas') ?>

    <?= $form->field($model, 'usuario_crea') ?>

    <?php // echo $form->field($model, 'fecha_crea') ?>

    <?php // echo $form->field($model, 'usuario_actualiza') ?>

    <?php // echo $form->field($model, 'fecha_actualiza') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
