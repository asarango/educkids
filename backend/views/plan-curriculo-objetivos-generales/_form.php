<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoObjetivosGenerales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-objetivos-generales-form">

    <div class="container">
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'evaluacion_id')->hiddenInput(['value' => $id])->label(FALSE) ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'objetivo_general')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
