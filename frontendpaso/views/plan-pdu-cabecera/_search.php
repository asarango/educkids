<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanPduCabeceraSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-pdu-cabecera-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'asignatura_curriculo_id') ?>

    <?= $form->field($model, 'bloque_id') ?>

    <?= $form->field($model, 'periodos') ?>

    <?php // echo $form->field($model, 'coordinador_id') ?>

    <?php // echo $form->field($model, 'vicerrector_id') ?>

    <?php // echo $form->field($model, 'planificacion_titulo') ?>

    <?php // echo $form->field($model, 'objetivo_por_nivel_id') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'creado_por') ?>

    <?php // echo $form->field($model, 'creado_fecha') ?>

    <?php // echo $form->field($model, 'actualizado_por') ?>

    <?php // echo $form->field($model, 'actualizado_fecha') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
