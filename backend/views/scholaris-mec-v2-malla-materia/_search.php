<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaMateriaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mec-v2-malla-materia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'codigo') ?>

    <?= $form->field($model, 'asignatura_id') ?>

    <?= $form->field($model, 'area_id') ?>

    <?= $form->field($model, 'imprime')->checkbox() ?>

    <?php // echo $form->field($model, 'es_cuantitativa')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
