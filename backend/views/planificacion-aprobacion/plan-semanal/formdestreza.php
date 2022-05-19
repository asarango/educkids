<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanPduEjes */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");

?>

<div class="plan-semanal-formdestreza">

    <div class="container">
        <h3>Destrezas de plan semanal</h3>

        <?php $form = ActiveForm::begin(); ?>

        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'curso_id')->textInput(['value' => $curso_id]);
        }else{
            echo $form->field($model, 'curso_id')->textInput();
        }
         
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'faculty_id')->textInput(['value' => $faculty_id]);
        }else{
            echo $form->field($model, 'faculty_id')->textInput();
        }
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'semana_id')->textInput(['value' => $semana_id]);
        }else{
            echo $form->field($model, 'semana_id')->textInput();
        }
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'comparte_valor')->textInput(['value' => $comparte_valor]);
        }else{
            echo $form->field($model, 'comparte_valor')->textInput();
        }
        ?>
        
        
        <?= $form->field($model, 'concepto')->textarea(["rows" => 6]); ?>
        
        <?= $form->field($model, 'contexto')->textarea(["rows" => 6]); ?>
        
        <?= $form->field($model, 'pregunta_indagacion')->textarea(["rows" => 6]); ?>
        
        <?= $form->field($model, 'enfoque')->textarea(["rows" => 6]); ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'creado_por')->textarea(['value' => $usuario])->label(false); 
        }else{
            echo $form->field($model, 'creado_por')->textarea(["rows" => 6]); 
        }
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'creado_fecha')->textarea(['value' => $fecha])->label(false); 
        }else{
            echo $form->field($model, 'creado_fecha')->textarea(["rows" => 6]); 
        }
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'actualizado_por')->textarea(['value' => $usuario])->label(false); 
        }else{
            echo $form->field($model, 'actualizado_por')->textarea(["rows" => 6]); 
        }
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'actualizado_fecha')->textarea(['value' => $fecha])->label(false); 
        }else{
            echo $form->field($model, 'actualizado_fecha')->textarea(["rows" => 6]); 
        }
        ?>


        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
