<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTableroSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-tablero-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'curso') ?>

    <?= $form->field($model, 'paralelo') ?>

    <?= $form->field($model, 'apellido_profesor') ?>

    <?= $form->field($model, 'nombre_profesor') ?>

    <?php // echo $form->field($model, 'p1') ?>

    <?php // echo $form->field($model, 'p2') ?>

    <?php // echo $form->field($model, 'p3') ?>

    <?php // echo $form->field($model, 'ex1') ?>

    <?php // echo $form->field($model, 'p4') ?>

    <?php // echo $form->field($model, 'p5') ?>

    <?php // echo $form->field($model, 'p6') ?>

    <?php // echo $form->field($model, 'ex2') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
