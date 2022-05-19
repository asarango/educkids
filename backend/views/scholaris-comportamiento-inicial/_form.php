<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisComportamientoInicial */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");
?>

<div class="container">

    <div class="scholaris-comportamiento-inicial-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'inscription_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'faculty_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'q1')->dropDownList([
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            'D' => 'D',
            'E' => 'E',
        ]) ?>

        <?= $form->field($model, 'q2')->dropDownList([
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            'D' => 'D',
            'E' => 'E',
        ]) ?>

        <?= $form->field($model, 'creado_por')->hiddenInput(['maxlength' => true])->label(false) ?>

        <?= $form->field($model, 'creado_fecha')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

        <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
