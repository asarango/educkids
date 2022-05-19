<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFaltasYAtrasosParcial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-faltas-yatrasos-parcial-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'alumno_id')->textInput() ?>

    <?= $form->field($model, 'bloque_id')->textInput() ?>

    <?= $form->field($model, 'atrasos')->textInput() ?>

    <?= $form->field($model, 'faltas_justificadas')->textInput() ?>

    <?= $form->field($model, 'faltas_injustificadas')->textInput() ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
