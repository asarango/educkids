<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisActividadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-actividad-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'create_date') ?>

    <?= $form->field($model, 'write_date') ?>

    <?= $form->field($model, 'create_uid') ?>

    <?= $form->field($model, 'write_uid') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'archivo') ?>

    <?php // echo $form->field($model, 'descripcion_archivo') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'inicio') ?>

    <?php // echo $form->field($model, 'fin') ?>

    <?php // echo $form->field($model, 'tipo_actividad_id') ?>

    <?php // echo $form->field($model, 'bloque_actividad_id') ?>

    <?php // echo $form->field($model, 'a_peso') ?>

    <?php // echo $form->field($model, 'b_peso') ?>

    <?php // echo $form->field($model, 'c_peso') ?>

    <?php // echo $form->field($model, 'd_peso') ?>

    <?php // echo $form->field($model, 'paralelo_id') ?>

    <?php // echo $form->field($model, 'materia_id') ?>

    <?php // echo $form->field($model, 'calificado') ?>

    <?php // echo $form->field($model, 'tipo_calificacion') ?>

    <?php // echo $form->field($model, 'tareas') ?>

    <?php // echo $form->field($model, 'hora_id') ?>

    <?php // echo $form->field($model, 'actividad_original') ?>

    <?php // echo $form->field($model, 'semana_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
