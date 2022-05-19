<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisHorariov2Cabecera */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-horariov2-cabecera-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'periodo_id')->hiddenInput(['value' =>  $periodo])->label(false) ?>
    
    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
