<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueActividadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-bloque-actividad-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'create_uid') ?>

    <?= $form->field($model, 'create_date') ?>

    <?= $form->field($model, 'write_uid') ?>

    <?php // echo $form->field($model, 'write_date') ?>

    <?php // echo $form->field($model, 'quimestre') ?>

    <?php // echo $form->field($model, 'tipo') ?>

    <?php // echo $form->field($model, 'desde') ?>

    <?php // echo $form->field($model, 'hasta') ?>

    <?php // echo $form->field($model, 'orden') ?>

    <?php // echo $form->field($model, 'scholaris_periodo_codigo') ?>

    <?php // echo $form->field($model, 'tipo_bloque') ?>

    <?php // echo $form->field($model, 'dias_laborados') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'abreviatura') ?>

    <?php // echo $form->field($model, 'tipo_uso') ?>

    <?php // echo $form->field($model, 'bloque_inicia') ?>

    <?php // echo $form->field($model, 'bloque_finaliza') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
