<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMateriaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-materia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'create_uid') ?>

    <?= $form->field($model, 'create_date') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'write_uid') ?>

    <?php // echo $form->field($model, 'write_date') ?>

    <?php // echo $form->field($model, 'area_id') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'tipo_materia_id') ?>

    <?php // echo $form->field($model, 'peso') ?>

    <?php // echo $form->field($model, 'orden') ?>

    <?php // echo $form->field($model, 'promedia') ?>

    <?php // echo $form->field($model, 'nombre_mec') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
