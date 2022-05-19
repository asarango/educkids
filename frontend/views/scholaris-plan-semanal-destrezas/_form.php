<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanSemanalDestrezas */
/* @var $form yii\widgets\ActiveForm */


$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");

?>

<div class="scholaris-plan-semanal-destrezas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'curso_id')->hiddenInput(['value' => $curso])->label(false);
        }else{
            echo $form->field($model, 'curso_id')->hiddenInput()->label(false);
        }
         
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'faculty_id')->hiddenInput(['value' => $profesor])->label(false);
        }else{
            echo $form->field($model, 'faculty_id')->hiddenInput()->label(false);
        }
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'semana_id')->hiddenInput(['value' => $semana])->label(false);
        }else{
            echo $form->field($model, 'semana_id')->hiddenInput()->label(false);
        }
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'comparte_valor')->hiddenInput(['value' => $uso])->label(false);
        }else{
            echo $form->field($model, 'comparte_valor')->hiddenInput()->label(false);
        }
        ?>

    <?= $form->field($model, 'concepto')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'contexto')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pregunta_indagacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'enfoque')->textarea(['rows' => 6]) ?>

    <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false)->label(false); 
        }else{
            echo $form->field($model, 'creado_por')->hiddenInput(["rows" => 6])->label(false); 
        }
        ?>
        
        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false); 
        }else{
            echo $form->field($model, 'creado_fecha')->hiddenInput(["rows" => 6])->label(false); 
        }
        ?>

    <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

    <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
