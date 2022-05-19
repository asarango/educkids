<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Rol */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="rol-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rol')->textInput(['maxlength' => true],['class' => 'form-group']) ?>
    
    <?php 
        // echo $form->field($model, 'rolOperaciones')->checkboxList(
        //         $listaOperaciones,
        //         ['separator' => ' // ']
        //         ); 
    ?>

<br>
    <div class="form-group">
        <?php // echo Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-outline-success':'btn btn-outline-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
