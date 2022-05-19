<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDestrezaEvaluar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-destreza-evaluar-form">

    <div class="container">
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'evaluacion_id')->hiddenInput(['value' => $id])->label(FALSE) ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'destreza')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tipo_destreza')->dropDownList([
                                                 'IMPRESCINDIBLE' => 'IMPRESCINDIBLE',
                                                 'DESEABLE' => 'DESEABLE',
                                              ]) 
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
