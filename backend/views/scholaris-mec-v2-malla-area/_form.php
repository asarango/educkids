<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaArea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mec-v2-malla-area-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?php
    $lista = backend\models\ScholarisMecV2Asignatura::find()
            ->where(['tipo' => 'AREA'])
            ->orderBy('nombre')
            ->all();

    $data = ArrayHelper::map($lista, 'id', 'nombre');

    echo $form->field($model, 'asignatura_id')->widget(Select2::className(), [
        'data' => $data,
        'options' => ['placeholder' => 'Nombre Área...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>

    <?= $form->field($model, 'orden')->textInput() ?>
    
    <?= $form->field($model, 'malla_id')->hiddenInput(['value' => $modelMalla->id])->label(false) ?>

    <?= $form->field($model, 'imprime')->checkbox() ?>

    <?= $form->field($model, 'es_cuantitativa')->checkbox() ?>
    
    <?= $form->field($model, 'promedia')->checkbox() ?>
    
    <?= $form->field($model, 'tipo')->dropDownList([
            'NORMAL' => 'NORMAL',
            'PROYECTOS' => 'PROYECTOS',
            'COMPORTAMIENTO' => 'COMPORTAMIENTO'
            ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
