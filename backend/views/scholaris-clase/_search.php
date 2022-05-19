<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-clase-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idmateria') ?>

    <?= $form->field($model, 'idprofesor') ?>

    <?= $form->field($model, 'idcurso') ?>

    <?= $form->field($model, 'paralelo_id') ?>

    <?php // echo $form->field($model, 'peso') ?>

    <?php // echo $form->field($model, 'periodo_scholaris') ?>

    <?php // echo $form->field($model, 'promedia') ?>

    <?php // echo $form->field($model, 'asignado_horario') ?>

    <?php // echo $form->field($model, 'tipo_usu_bloque') ?>

    <?php // echo $form->field($model, 'todos_alumnos') ?>

    <?php // echo $form->field($model, 'malla_materia') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
