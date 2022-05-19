<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanPduEjes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profesor-inicio-form">
    
    <h3>Creando mi clase nueva</h3>

    <?php $form = ActiveForm::begin(); ?>

   

    <?php
    
    $listData = ArrayHelper::map($modelCursos, 'id', 'name');
    echo $form->field($model, 'idcurso')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione Curso...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>
    
    
    <?php
    
    $listData = ArrayHelper::map($modelHorario, 'id', 'descripcion');
    echo $form->field($model, 'asignado_horario')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione Horario...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>
    
    <?php
    
    $listData = ArrayHelper::map($modelComparte, 'valor', 'nombre');
    echo $form->field($model, 'tipo_usu_bloque')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione Bloques que comparte...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>
    
    <?php
    echo $form->field($model, 'todos_alumnos')->dropDownList([
        1 => 'SI',
        2 => 'NO',
    ]);
    ?>
    
    <?php
    
    $listData = ArrayHelper::map($modelMaterias, 'id', 'materia');
    echo $form->field($model, 'malla_materia')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione materia de la institucion...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>
    
    <?php
    
    $listData = ArrayHelper::map($modelMateriasCurriculo, 'id', 'materia');
    echo $form->field($model, 'materia_curriculo_codigo')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione materia del curriculo...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>
    
       
    <?php
    
    $listData = ArrayHelper::map($modelCursosCurriculo, 'codigo', 'nombre');
    echo $form->field($model, 'codigo_curso_curriculo')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione curso del curriculo...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>
    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
