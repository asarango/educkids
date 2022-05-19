<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueSemanas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-bloque-semanas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
        $listData = ArrayHelper::map($modelBloques, 'id', 'name');
        echo $form->field($model, 'bloque_id')->widget(Select2::className(),[
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione bloque...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
    ?>

    <?= $form->field($model, 'semana_numero')->textInput() ?>

    <?= $form->field($model, 'nombre_semana')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_inicio')->widget(DatePicker::className(), [
                'name' => 'fecha_inicio',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Desde ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]);
    ?>

    <?= $form->field($model, 'fecha_finaliza')->widget(DatePicker::className(), [
                'name' => 'fecha_finaliza',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Hasta ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]);
    ?>

    <?= $form->field($model, 'estado')->hiddenInput(['value' => 1])->label(false) ?>

    <?php 
     if ($model->isNewRecord) {
        echo $form->field($model, 'fecha_limite_inicia')->widget(DatePicker::className(), [
                'name' => 'fecha_limite_inicia',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Líminite Inicia ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]);
     }else{
         echo $form->field($model, 'fecha_limite_inicia')->textInput();
     }
    ?>

    <?php 
    if ($model->isNewRecord) {
        echo $form->field($model, 'fecha_limite_tope')->widget(DatePicker::className(), [
                'name' => 'fecha_limite_tope',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Líminite Tope ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]);
    }else{
        echo $form->field($model, 'fecha_limite_tope')->textInput();
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
