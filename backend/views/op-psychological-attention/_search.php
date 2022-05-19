<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OpPsychologicalAttentionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="op-psychological-attention-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'attended_faculty_id') ?>

    <?= $form->field($model, 'create_date') ?>

    <?= $form->field($model, 'detail') ?>

    <?= $form->field($model, 'departament_id') ?>

    <?php // echo $form->field($model, 'course_id') ?>

    <?php // echo $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'create_uid') ?>

    <?php // echo $form->field($model, 'employee_id') ?>

    <?php // echo $form->field($model, 'external_derivation_id') ?>

    <?php // echo $form->field($model, 'student_id') ?>

    <?php // echo $form->field($model, 'violence_modality_id') ?>

    <?php // echo $form->field($model, 'attention_type_id') ?>

    <?php // echo $form->field($model, 'agreements') ?>

    <?php // echo $form->field($model, 'violence_type_id') ?>

    <?php // echo $form->field($model, 'violence_reason_id') ?>

    <?php // echo $form->field($model, 'attended_student_id') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'attended_parent_id') ?>

    <?php // echo $form->field($model, 'write_date') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'write_uid') ?>

    <?php // echo $form->field($model, 'special_need_id') ?>

    <?php // echo $form->field($model, 'substance_use_id') ?>

    <?php // echo $form->field($model, 'parallel_id') ?>

    <?php // echo $form->field($model, 'special_attention')->checkbox() ?>

    <?php // echo $form->field($model, 'persona_lidera') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
