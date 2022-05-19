<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoDetalle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-asistencia-comportamiento-detalle-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        if($model->isNewRecord){
            echo $form->field($model, 'comportamiento_id')->hiddenInput(['value' => $id])->label(false);
        }else{
            echo $form->field($model, 'comportamiento_id')->hiddenInput()->label(false);
        }
         
    ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cantidad_descuento')->hiddenInput(['value' => 0])->label(false) ?>

    <?= $form->field($model, 'punto_descuento')->hiddenInput(['value' => 0])->label(false) ?>

    <?= $form->field($model, 'total_x_unidad')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'code_fj')->hiddenInput(['maxlength' => true])->label(false) ?>
    
    <?= $form->field($model, 'limite')->textInput() ?>
    
    <?= $form->field($model, 'activo')->dropDownList(
            [true => 'SI', false => 'NO']
            ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
