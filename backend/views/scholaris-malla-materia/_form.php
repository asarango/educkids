<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisMateria;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaMateria */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-malla-materia-form">
    
    <div class="container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'malla_area_id')->hiddenInput(['value' => $modelArea->id])->label(false) ?>

    <?php
    $lista = ScholarisMateria::find()
            ->select(["scholaris_materia.id", "concat(scholaris_materia.name,' ',scholaris_area.period_id) as name"])
            ->innerJoin("scholaris_area","scholaris_area.id = scholaris_materia.area_id")
            ->all();
    $listData = ArrayHelper::map($lista, 'id', 'name');

    echo $form->field($model, 'materia_id')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione Asignatura...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>

    <?= $form->field($model, 'total_porcentaje')->textInput() ?>
    
    <?= $form->field($model, 'tipo')->dropDownList([
        'NORMAL' => 'NORMAL',
        'OPTATIVAS' => 'OPTATIVAS',
        'PROYECTOS' => 'PROYECTOS',
        'DHI' => 'DHI',
        'COMPORTAMIENTO' => 'COMPORTAMIENTO'
    ]) ?>

    <?= $form->field($model, 'orden')->textInput() ?>
    
    <?= $form->field($model, 'se_imprime')->checkbox() ?>

    <?= $form->field($model, 'promedia')->checkbox() ?>
  

    <?= $form->field($model, 'es_cuantitativa')->checkbox() ?>

    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>