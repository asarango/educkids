<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisCalificaComportamiento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-califica-comportamiento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inscription_id')->textInput() ?>

    <?= $form->field($model, 'bloque_id')->textInput() ?>

    <?= $form->field($model, 'calificacion')->textInput() ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'creado_por')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'creado_fecha')->textInput() ?>

    <?= $form->field($model, 'actualizado_por')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'actualizado_fecha')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
