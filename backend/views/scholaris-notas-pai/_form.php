<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisNotasPai */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-notas-pai-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'clase_id')->textInput() ?>

    <?= $form->field($model, 'alumno_id')->textInput() ?>

    <?= $form->field($model, 'alumno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quimestre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'scholaris_periodo_codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sumativa1_a')->textInput() ?>

    <?= $form->field($model, 'sumativa2_a')->textInput() ?>

    <?= $form->field($model, 'sumativa3_a')->textInput() ?>

    <?= $form->field($model, 'nota_a')->textInput() ?>

    <?= $form->field($model, 'sumativa1_b')->textInput() ?>

    <?= $form->field($model, 'sumativa2_b')->textInput() ?>

    <?= $form->field($model, 'sumativa3_b')->textInput() ?>

    <?= $form->field($model, 'nota_b')->textInput() ?>

    <?= $form->field($model, 'sumativa1_c')->textInput() ?>

    <?= $form->field($model, 'sumativa2_c')->textInput() ?>

    <?= $form->field($model, 'sumativa3_c')->textInput() ?>

    <?= $form->field($model, 'nota_c')->textInput() ?>

    <?= $form->field($model, 'sumativa1_d')->textInput() ?>

    <?= $form->field($model, 'sumativa2_d')->textInput() ?>

    <?= $form->field($model, 'sumativa3_d')->textInput() ?>

    <?= $form->field($model, 'nota_d')->textInput() ?>

    <?= $form->field($model, 'suma_total')->textInput() ?>

    <?= $form->field($model, 'final_homologado')->textInput() ?>

    <?= $form->field($model, 'creado')->textInput() ?>

    <?= $form->field($model, 'usuario_crea')->textInput() ?>

    <?= $form->field($model, 'actualizado')->textInput() ?>

    <?= $form->field($model, 'usuario_modifica')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
