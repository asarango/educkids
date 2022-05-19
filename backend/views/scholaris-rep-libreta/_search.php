<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisRepLibretaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-rep-libreta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'codigo') ?>

    <?= $form->field($model, 'usuario') ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'promedia') ?>

    <?= $form->field($model, 'tipo_uso_bloque') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'asignatura_id') ?>

    <?php // echo $form->field($model, 'asignatura') ?>

    <?php // echo $form->field($model, 'paralelo_id') ?>

    <?php // echo $form->field($model, 'alumno_id') ?>

    <?php // echo $form->field($model, 'area_id') ?>

    <?php // echo $form->field($model, 'p1') ?>

    <?php // echo $form->field($model, 'p2') ?>

    <?php // echo $form->field($model, 'p3') ?>

    <?php // echo $form->field($model, 'pr1') ?>

    <?php // echo $form->field($model, 'ex1') ?>

    <?php // echo $form->field($model, 'pr180') ?>

    <?php // echo $form->field($model, 'ex120') ?>

    <?php // echo $form->field($model, 'q1') ?>

    <?php // echo $form->field($model, 'p4') ?>

    <?php // echo $form->field($model, 'p5') ?>

    <?php // echo $form->field($model, 'p6') ?>

    <?php // echo $form->field($model, 'pr2') ?>

    <?php // echo $form->field($model, 'ex2') ?>

    <?php // echo $form->field($model, 'pr280') ?>

    <?php // echo $form->field($model, 'ex220') ?>

    <?php // echo $form->field($model, 'q2') ?>

    <?php // echo $form->field($model, 'nota_final') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
