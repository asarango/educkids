<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClaseLibreta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-clase-libreta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'grupo_id')->textInput() ?>

    <?= $form->field($model, 'p1')->textInput() ?>

    <?= $form->field($model, 'p2')->textInput() ?>

    <?= $form->field($model, 'p3')->textInput() ?>

    <?= $form->field($model, 'pr1')->textInput() ?>

    <?= $form->field($model, 'pr180')->textInput() ?>

    <?= $form->field($model, 'ex1')->textInput() ?>

    <?= $form->field($model, 'ex120')->textInput() ?>

    <?= $form->field($model, 'q1')->textInput() ?>

    <?= $form->field($model, 'p4')->textInput() ?>

    <?= $form->field($model, 'p5')->textInput() ?>

    <?= $form->field($model, 'p6')->textInput() ?>

    <?= $form->field($model, 'pr2')->textInput() ?>

    <?= $form->field($model, 'pr280')->textInput() ?>

    <?= $form->field($model, 'ex2')->textInput() ?>

    <?= $form->field($model, 'ex220')->textInput() ?>

    <?= $form->field($model, 'q2')->textInput() ?>

    <?= $form->field($model, 'final_ano_normal')->textInput() ?>

    <?= $form->field($model, 'mejora_q1')->textInput() ?>

    <?= $form->field($model, 'mejora_q2')->textInput() ?>

    <?= $form->field($model, 'final_con_mejora')->textInput() ?>

    <?= $form->field($model, 'supletorio')->textInput() ?>

    <?= $form->field($model, 'remedial')->textInput() ?>

    <?= $form->field($model, 'gracia')->textInput() ?>

    <?= $form->field($model, 'final_total')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
