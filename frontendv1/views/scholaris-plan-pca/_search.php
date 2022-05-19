<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPcaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-plan-pca-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'malla_materia_curriculo_id') ?>

    <?= $form->field($model, 'malla_materia_institucion_id') ?>

    <?= $form->field($model, 'curso_curriculo_id') ?>

    <?= $form->field($model, 'curso_institucion_id') ?>

    <?php // echo $form->field($model, 'docentes') ?>

    <?php // echo $form->field($model, 'paralelos') ?>

    <?php // echo $form->field($model, 'nivel_educativo') ?>

    <?php // echo $form->field($model, 'carga_horaria_semanal') ?>

    <?php // echo $form->field($model, 'semanas_trabajo') ?>

    <?php // echo $form->field($model, 'aprendizaje_imprevistos') ?>

    <?php // echo $form->field($model, 'total_semanas_clase') ?>

    <?php // echo $form->field($model, 'total_periodos') ?>

    <?php // echo $form->field($model, 'revisado_por') ?>

    <?php // echo $form->field($model, 'aprobado_por') ?>

    <?php // echo $form->field($model, 'creado_por') ?>

    <?php // echo $form->field($model, 'creado_fecha') ?>

    <?php // echo $form->field($model, 'actualizado_por') ?>

    <?php // echo $form->field($model, 'actualizado_fecha') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
