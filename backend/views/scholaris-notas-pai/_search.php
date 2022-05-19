<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisNotasPaiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-notas-pai-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'alumno_id') ?>

    <?= $form->field($model, 'alumno') ?>

    <?= $form->field($model, 'quimestre') ?>

    <?php // echo $form->field($model, 'scholaris_periodo_codigo') ?>

    <?php // echo $form->field($model, 'sumativa1_a') ?>

    <?php // echo $form->field($model, 'sumativa2_a') ?>

    <?php // echo $form->field($model, 'sumativa3_a') ?>

    <?php // echo $form->field($model, 'nota_a') ?>

    <?php // echo $form->field($model, 'sumativa1_b') ?>

    <?php // echo $form->field($model, 'sumativa2_b') ?>

    <?php // echo $form->field($model, 'sumativa3_b') ?>

    <?php // echo $form->field($model, 'nota_b') ?>

    <?php // echo $form->field($model, 'sumativa1_c') ?>

    <?php // echo $form->field($model, 'sumativa2_c') ?>

    <?php // echo $form->field($model, 'sumativa3_c') ?>

    <?php // echo $form->field($model, 'nota_c') ?>

    <?php // echo $form->field($model, 'sumativa1_d') ?>

    <?php // echo $form->field($model, 'sumativa2_d') ?>

    <?php // echo $form->field($model, 'sumativa3_d') ?>

    <?php // echo $form->field($model, 'nota_d') ?>

    <?php // echo $form->field($model, 'suma_total') ?>

    <?php // echo $form->field($model, 'final_homologado') ?>

    <?php // echo $form->field($model, 'creado') ?>

    <?php // echo $form->field($model, 'usuario_crea') ?>

    <?php // echo $form->field($model, 'actualizado') ?>

    <?php // echo $form->field($model, 'usuario_modifica') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
