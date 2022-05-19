<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisImagenes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-imagenes-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?php
        if($model->isNewRecord){
            echo $form->field($model, 'codigo')->textInput(['maxlength' => true]);
        }else{
            echo $form->field($model, 'codigo')->hiddenInput(['maxlength' => true])->label(false);
        }
    ?>

    <?php //echo $form->field($model, 'nombre_archivo')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'fileImagen')->fileInput() ?>    

    <?= $form->field($model, 'alto_pixeles')->textInput() ?>

    <?= $form->field($model, 'ancho_pixeles')->textInput() ?>

    <?= $form->field($model, 'detalle')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'imagen_educandi')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Cargar Imagen', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
