<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisArchivosPud */
/* @var $form yii\widgets\ActiveForm */

$hoy = date("YmdHis");
$fecha = date("Y-m-d H:i:s");
$usuario = Yii::$app->user->identity->usuario;
?>

<div class="scholaris-archivos-pud-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'codigo')->hiddenInput(['value' => $hoy.$modelClase->id.'.pdf'])->label(false);
    }else{
        echo $form->field($model, 'codigo')->hiddenInput()->label(false);
    }
    ?>

    <?= $form->field($model, 'bloque_id')->hiddenInput(['value' => $modelBloque->id])->label(false) ?>

    <?= $form->field($model, 'clase_id')->hiddenInput(['value' => $modelClase->id])->label(false) ?>

    <?php // $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->fileInput() ?>

    <?= $form->field($model, 'tipo_documento')->dropDownList([
        'PUD' => 'PUD',
        'ANEXO' => 'ANEXO'
    ]) ?>

    <?= $form->field($model, 'estado')->hiddenInput(['value' => 'APROBADO'])->label(false) ?>

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false);
    }else{
        echo $form->field($model, 'creado_fecha')->hiddenInput()->label(false);
    }
    ?>

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false);
    }else{
        echo $form->field($model, 'creado_por')->hiddenInput(['maxlength' => true])->label(false);
    }
    ?>

    <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

    <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
