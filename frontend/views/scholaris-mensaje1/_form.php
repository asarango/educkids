<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMensaje1 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mensaje1-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mensaje')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'estado')->checkbox() ?>

    <?= $form->field($model, 'autor_usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'para_usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
