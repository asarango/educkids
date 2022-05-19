<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClaseLibretaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-clase-libreta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'grupo_id') ?>

    <?= $form->field($model, 'p1') ?>

    <?= $form->field($model, 'p2') ?>

    <?= $form->field($model, 'p3') ?>

    <?php // echo $form->field($model, 'pr1') ?>

    <?php // echo $form->field($model, 'pr180') ?>

    <?php // echo $form->field($model, 'ex1') ?>

    <?php // echo $form->field($model, 'ex120') ?>

    <?php // echo $form->field($model, 'q1') ?>

    <?php // echo $form->field($model, 'p4') ?>

    <?php // echo $form->field($model, 'p5') ?>

    <?php // echo $form->field($model, 'p6') ?>

    <?php // echo $form->field($model, 'pr2') ?>

    <?php // echo $form->field($model, 'pr280') ?>

    <?php // echo $form->field($model, 'ex2') ?>

    <?php // echo $form->field($model, 'ex220') ?>

    <?php // echo $form->field($model, 'q2') ?>

    <?php // echo $form->field($model, 'final_ano_normal') ?>

    <?php // echo $form->field($model, 'mejora_q1') ?>

    <?php // echo $form->field($model, 'mejora_q2') ?>

    <?php // echo $form->field($model, 'final_con_mejora') ?>

    <?php // echo $form->field($model, 'supletorio') ?>

    <?php // echo $form->field($model, 'remedial') ?>

    <?php // echo $form->field($model, 'gracia') ?>

    <?php // echo $form->field($model, 'final_total') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
