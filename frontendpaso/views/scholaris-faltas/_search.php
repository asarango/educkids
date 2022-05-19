<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFaltasYAtrasosParcialSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-faltas-yatrasos-parcial-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'alumno_id') ?>

    <?= $form->field($model, 'bloque_id') ?>

    <?= $form->field($model, 'atrasos') ?>

    <?= $form->field($model, 'faltas_justificadas') ?>

    <?php // echo $form->field($model, 'faltas_injustificadas') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
