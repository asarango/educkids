<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoEvaluacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-evaluacion-form">

    <div class="container">
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'distribucion_id')->hiddenInput(['value' => $id])->label(FALSE) ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'criterio_evaluacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'orientacion_metodologica')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
