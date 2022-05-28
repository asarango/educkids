<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsPca */
/* @var $form yii\widgets\ActiveForm */

// echo '<pre>';
// print_r($modelIsmAreaMateria);
?>

<div class="kids-pca-form" style="margin-bottom:5px; padding:10px; padding-left:50px; padding-right:50px; font-size:12px ">

    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'font-size: 10px']
    ]); ?>

    <?= $form->field($model, 'ism_area_materia_id')->hiddenInput(['value' => $modelIsmAreaMateria->id])->label(false) ?>
    <div class="row">
        <div class="col-md-4 col-sm-4">
            <?= $form->field($model, 'carga_horaria_semanal')->textInput([
                'type' => 'number',
                'style' => 'font-size: 10px'              
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-4">
            <?= $form->field($model, 'numero_semanas_trabajo')->textInput([
                'type' => 'number',
                'style' => 'font-size: 10px'  
            ])->label('Nro. Semanas de trabajo') ?>
        </div>
        <div class="col-md-4 col-sm-4">
            <?= $form->field($model, 'imprevistos')->textInput([
                'type' => 'number',
                'style' => 'font-size: 10px'  
            ])->label('Nro. Imprevistos') ?>
        </div>
    </div>


    <?= $form->field($model, 'objetivos')->textarea(['rows' => 2, 'style' => 'font-size: 10px'  ]) ?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 2, 'style' => 'font-size: 10px'  ]) ?>

    <?= $form->field($model, 'bibliografia')->textarea(['rows' => 2, 'style' => 'font-size: 10px'  ]) ?>

    <?php
    if ($model->isNewRecord) {
        echo $form->field($model, 'estado')->hiddenInput(['maxlength' => true, 'value' => 'INICIANDO'])->label(false);
    } else {
        echo $form->field($model, 'estado')->hiddenInput(['maxlength' => true])->label(false);
    }
    ?>

    <?= $form->field($model, 'created_at')->hiddenInput(['value' => $today])->label(false) ?>

    <?= $form->field($model, 'created')->hiddenInput(['value' => $userLog])->label(false) ?>

    <?= $form->field($model, 'updated_at')->hiddenInput(['value' => $today])->label(false) ?>

    <?= $form->field($model, 'updated')->hiddenInput(['value' => $userLog])->label(false) ?>

    <div class="form-group" style="margin-top:10px">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>


