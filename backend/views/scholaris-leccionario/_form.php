<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisLeccionario */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");

?>

<div class="scholaris-leccionario-form">
    
    <div class="container">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    if ($model->isNewRecord) {
        echo $form->field($model, 'paralelo_id')->hiddenInput(['value' => $paralelo])->label(false);
    } else {
        echo $form->field($model, 'paralelo_id')->hiddenInput()->label(false);
    }
    ?>
    
    <?= $form->field($model, 'fecha')->widget(DatePicker::className(),[
        'options' => ['placeholder' => 'Select issue date ...'],
	'pluginOptions' => [
		'format' => 'yyyy-m-d',
		'todayHighlight' => true
	]
    ]) 
            ?>
    

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'total_clases')->hiddenInput(['value' => 0])->label(false);
    }else{
        echo $form->field($model, 'total_clases')->hiddenInput()->label(false);
    }
    
    ?>

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'total_revisadas')->hiddenInput(['value' => 0])->label(false);
    }else{
        $form->field($model, 'total_revisadas')->hiddenInput()->label(false);
    }
    ?>
    

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'usuario_crea')->hiddenInput(['value' => $usuario])->label(false);
    }else{
        echo $form->field($model, 'usuario_crea')->hiddenInput(['maxlength' => true])->label(false);
    }
    ?>

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'fecha_crea')->hiddenInput(['value' => $fecha])->label(false);
    }else{
        echo $form->field($model, 'fecha_crea')->hiddenInput()->label(false);
    }
    ?>

    <?= $form->field($model, 'usuario_actualiza')->hiddenInput(['value' => $usuario])->label(false); ?>

    <?= $form->field($model, 'fecha_actualiza')->hiddenInput(['value' => $fecha])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
