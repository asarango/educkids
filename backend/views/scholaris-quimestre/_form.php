<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisQuimestre */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-quimestre-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo_quimestre')->dropDownList([
      'normal' => 'normal',  
      'extra' => 'extra',  
      'recuperacion' => 'recuperacion',  
    ]) ?>

    <?= $form->field($model, 'orden')->textInput() ?>

    <?= $form->field($model, 'estado')->dropDownList([
        'activo' => 'activo',
        'inactivo' => 'inactivo',
    ]) ?>

    <?= $form->field($model, 'abreviatura')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
