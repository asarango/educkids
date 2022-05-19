<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-form">
    <div class="container">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ano_incia')->textInput() ?>

    <?= $form->field($model, 'ano_finaliza')->textInput() ?>

    <?= $form->field($model, 'estado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
