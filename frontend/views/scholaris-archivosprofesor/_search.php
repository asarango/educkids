<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ScholarisArchivosprofesorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-archivosprofesor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idactividad') ?>

    <?= $form->field($model, 'archivo') ?>

    <?= $form->field($model, 'fechasubido') ?>

    <?= $form->field($model, 'nombre_archivo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
