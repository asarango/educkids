<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaMateriaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-malla-materia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'malla_area_id') ?>

    <?= $form->field($model, 'materia_id') ?>

    <?= $form->field($model, 'se_imprime')->checkbox() ?>

    <?= $form->field($model, 'promedia')->checkbox() ?>

    <?php // echo $form->field($model, 'total_porcentaje') ?>

    <?php // echo $form->field($model, 'es_cuantitativa')->checkbox() ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'orden') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
