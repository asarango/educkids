<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Distribucion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mec-v2-distribucion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'materia_id')->textInput() ?>

    <?= $form->field($model, 'curso_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
