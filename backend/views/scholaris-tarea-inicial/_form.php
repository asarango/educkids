<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTareaInicial */
/* @var $form yii\widgets\ActiveForm */


$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");
?>

<div class="scholaris-tarea-inicial-form">


    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <?php
            $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data']
            ]);
            ?>

            <?= $form->field($model, 'clase_id')->hiddenInput(['value' => $clase])->label(false) ?>

            <?= $form->field($model, 'quimestre_codigo')->hiddenInput(['value' => $quimestre])->label(false) ?>

            <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'fecha_inicio')->widget(DatePicker::className(), [
                    'name' => 'fecha_inicio',
                    'value' => date('d-M-Y', strtotime('+2 days')),
                    'options' => ['placeholder' => 'Seleccione feha de inicio ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]);
            } else {
                echo $form->field($model, 'fecha_inicio')->textInput();
            }
            ?>

            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'fecha_entrega')->widget(DatePicker::className(), [
                    'name' => 'fecha_inicio',
                    'value' => date('d-M-Y', strtotime('+2 days')),
                    'options' => ['placeholder' => 'Seleccione feha de inicio ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]);
            } else {
                echo $form->field($model, 'fecha_entrega')->textInput();
            }
            ?>

            <?php // $form->field($model, 'nombre_archivo')->textInput(['maxlength' => true]) ?>
            <?php 
                if($model->isNewRecord){
                    echo $form->field($model, 'nombre_archivo')->fileInput();
                }else{
                    echo $form->field($model, 'nombre_archivo')->hiddenInput()->label(false);
                }
            ?>

            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false);
            } else {
                echo $form->field($model, 'creado_por')->hiddenInput()->label(false);
            }
            ?>

            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false);
            } else {
                echo $form->field($model, 'creado_fecha')->hiddenInput()->label(false);
            }
            ?>

            <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

            <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>


            <?php if (!Yii::$app->request->isAjax) { ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            <?php } ?>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-4"></div>
    </div>



</div>
