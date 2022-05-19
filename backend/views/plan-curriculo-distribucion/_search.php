<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDistribucionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-distribucion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nivel_id') ?>

    <?= $form->field($model, 'curriculo_id') ?>

    <?= $form->field($model, 'area_id') ?>

    <?= $form->field($model, 'jefe_area_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
