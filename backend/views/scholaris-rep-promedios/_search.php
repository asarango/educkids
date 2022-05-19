<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisRepPromediosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-rep-promedios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'codigo') ?>

    <?= $form->field($model, 'paralelo_id') ?>

    <?= $form->field($model, 'alumno_id') ?>

    <?= $form->field($model, 'nota_promedio') ?>

    <?= $form->field($model, 'nota_comportamiento') ?>

    <?php // echo $form->field($model, 'usuario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
