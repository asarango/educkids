<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OpCourseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="op-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'create_uid') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'create_date') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'evaluation_type') ?>

    <?php // echo $form->field($model, 'write_uid') ?>

    <?php // echo $form->field($model, 'parent_id') ?>

    <?php // echo $form->field($model, 'write_date') ?>

    <?php // echo $form->field($model, 'section_moved0') ?>

    <?php // echo $form->field($model, 'x_template_id') ?>

    <?php // echo $form->field($model, 'x_capacidad') ?>

    <?php // echo $form->field($model, 'x_institute') ?>

    <?php // echo $form->field($model, 'section_moved1') ?>

    <?php // echo $form->field($model, 'orden') ?>

    <?php // echo $form->field($model, 'abreviatura') ?>

    <?php // echo $form->field($model, 'level_id') ?>

    <?php // echo $form->field($model, 'section_moved2') ?>

    <?php // echo $form->field($model, 'section_moved3') ?>

    <?php // echo $form->field($model, 'section_moved4') ?>

    <?php // echo $form->field($model, 'section_moved5') ?>

    <?php // echo $form->field($model, 'section_moved6') ?>

    <?php // echo $form->field($model, 'section_moved7') ?>

    <?php // echo $form->field($model, 'section_moved8') ?>

    <?php // echo $form->field($model, 'section') ?>

    <?php // echo $form->field($model, 'period_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
