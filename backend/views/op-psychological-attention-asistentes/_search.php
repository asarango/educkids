<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OpPsychologicalAttentionAsistentesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="op-psychological-attention-asistentes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'create_uid') ?>

    <?= $form->field($model, 'create_date') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'write_uid') ?>

    <?php // echo $form->field($model, 'psychological_attention_id') ?>

    <?php // echo $form->field($model, 'write_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
