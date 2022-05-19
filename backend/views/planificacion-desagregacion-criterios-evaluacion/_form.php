<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionDesagregacionCriteriosEvaluacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="planificacion-desagregacion-criterios-evaluacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cabecera_id')->textInput() ?>

    <?= $form->field($model, 'criterio_evaluacion_id')->textInput() ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
