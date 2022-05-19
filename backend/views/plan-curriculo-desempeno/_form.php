<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDesempeno */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-desempeno-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="container">
    
    <?= $form->field($model, 'destreza_id')->hiddenInput(['value' => $id])->label(FALSE) ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textarea(['rows' => 6]) ?>

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
