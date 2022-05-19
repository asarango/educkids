<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamiento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-asistencia-comportamiento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
    
    <h3><u>Detalle de códigos de comportamiento</u></h3>
    
    

</div>
