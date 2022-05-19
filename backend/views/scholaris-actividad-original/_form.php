<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisActividad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-actividad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'create_date')->textInput() ?>

    <?= $form->field($model, 'write_date')->textInput() ?>

    <?= $form->field($model, 'create_uid')->textInput() ?>

    <?= $form->field($model, 'write_uid')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textInput() ?>

    <?= $form->field($model, 'archivo')->textInput() ?>

    <?= $form->field($model, 'descripcion_archivo')->textInput() ?>

    <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inicio')->textInput() ?>

    <?= $form->field($model, 'fin')->textInput() ?>

    <?= $form->field($model, 'tipo_actividad_id')->textInput() ?>

    <?= $form->field($model, 'bloque_actividad_id')->textInput() ?>

    <?= $form->field($model, 'a_peso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'b_peso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'c_peso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'd_peso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paralelo_id')->textInput() ?>

    <?= $form->field($model, 'materia_id')->textInput() ?>

    <?= $form->field($model, 'calificado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo_calificacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tareas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hora_id')->textInput() ?>

    <?= $form->field($model, 'actividad_original')->textInput() ?>

    <?= $form->field($model, 'semana_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
