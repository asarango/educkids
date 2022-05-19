<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaMateria */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mec-v2-malla-materia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo $form->field($model, 'codigo')->textInput(['maxlength' => true]);
    ?>

    <?php
    $lista = backend\models\ScholarisMecV2Asignatura::find()
            ->where(['tipo' => 'MATERIA'])
            ->orderBy('nombre')
            ->all();

    $data = ArrayHelper::map($lista, 'id', 'nombre');

    echo $form->field($model, 'asignatura_id')->widget(Select2::className(), [
        'data' => $data,
        'options' => ['placeholder' => 'Nombre Asignatura...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>
    
    <?= $form->field($model, 'tipo')->dropDownList([
        'normal' => 'normal',
        'proyectos' => 'proyectos',
        'comportamiento' => 'comportamiento',
        ['prompt' => 'Seleccione']
    ]) ?>

    
    <?= $form->field($model, 'orden')->textInput() ?>
    
    <?= $form->field($model, 'area_id')->hiddenInput(['value' => $modelArea->id])->label(false) ?>

    <?= $form->field($model, 'imprime')->checkbox() ?>

    <?= $form->field($model, 'es_cuantitativa')->checkbox() ?>
    
    <?= $form->field($model, 'promedia')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
