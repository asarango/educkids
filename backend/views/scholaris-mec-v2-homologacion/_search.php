<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2HomologacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mec-v2-homologacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'distribucion_id') ?>

    <?= $form->field($model, 'tipo') ?>

    <?= $form->field($model, 'codigo_tipo') ?>

    <?= $form->field($model, 'nombre_tipo') ?>

    <?php // echo $form->field($model, 'profesor_nombre') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
