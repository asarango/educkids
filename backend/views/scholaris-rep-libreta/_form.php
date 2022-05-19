<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisRepLibreta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-rep-libreta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clase_id')->textInput() ?>

    <?= $form->field($model, 'promedia')->textInput() ?>

    <?= $form->field($model, 'tipo_uso_bloque')->textInput() ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'asignatura_id')->textInput() ?>

    <?= $form->field($model, 'asignatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paralelo_id')->textInput() ?>

    <?= $form->field($model, 'alumno_id')->textInput() ?>

    <?= $form->field($model, 'area_id')->textInput() ?>

    <?= $form->field($model, 'p1')->textInput() ?>

    <?= $form->field($model, 'p2')->textInput() ?>

    <?= $form->field($model, 'p3')->textInput() ?>

    <?= $form->field($model, 'pr1')->textInput() ?>

    <?= $form->field($model, 'ex1')->textInput() ?>

    <?= $form->field($model, 'pr180')->textInput() ?>

    <?= $form->field($model, 'ex120')->textInput() ?>

    <?= $form->field($model, 'q1')->textInput() ?>

    <?= $form->field($model, 'p4')->textInput() ?>

    <?= $form->field($model, 'p5')->textInput() ?>

    <?= $form->field($model, 'p6')->textInput() ?>

    <?= $form->field($model, 'pr2')->textInput() ?>

    <?= $form->field($model, 'ex2')->textInput() ?>

    <?= $form->field($model, 'pr280')->textInput() ?>

    <?= $form->field($model, 'ex220')->textInput() ?>

    <?= $form->field($model, 'q2')->textInput() ?>

    <?= $form->field($model, 'nota_final')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
