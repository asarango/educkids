<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionDesagregacionCabecera */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="planificacion-desagregacion-cabecera-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'scholaris_materia_id')->textInput() ?>

    <?= $form->field($model, 'curriculo_mec_nivel_id')->textInput() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year_from')->textInput() ?>

    <?= $form->field($model, 'year_to')->textInput() ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <?= $form->field($model, 'comments')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
