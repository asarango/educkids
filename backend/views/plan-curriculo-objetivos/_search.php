<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoObjetivosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-objetivos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'distribucion_id') ?>

    <?= $form->field($model, 'codigo_ministerio') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'tipo_objetivo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
