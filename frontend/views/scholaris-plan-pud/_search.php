<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPudSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-plan-pud-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'clase_id') ?>

    <?= $form->field($model, 'bloque_id') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'fecha_inicio') ?>

    <?php // echo $form->field($model, 'fecha_finalizacion') ?>

    <?php // echo $form->field($model, 'objetivo_unidad') ?>

    <?php // echo $form->field($model, 'ac_necesidad_atendida') ?>

    <?php // echo $form->field($model, 'ac_adaptacion_aplicada') ?>

    <?php // echo $form->field($model, 'ac_responsable_dece') ?>

    <?php // echo $form->field($model, 'bibliografia') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <?php // echo $form->field($model, 'quien_revisa_id') ?>

    <?php // echo $form->field($model, 'quien_aprueba_id') ?>

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
