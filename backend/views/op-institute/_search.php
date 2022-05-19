<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OpInstituteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="op-institute-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'create_uid') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'create_date') ?>

    <?= $form->field($model, 'store_id') ?>

    <?php // echo $form->field($model, 'write_uid') ?>

    <?php // echo $form->field($model, 'write_date') ?>

    <?php // echo $form->field($model, 'direccion') ?>

    <?php // echo $form->field($model, 'codigo_amie') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'telefono') ?>

    <?php // echo $form->field($model, 'rector') ?>

    <?php // echo $form->field($model, 'secretario') ?>

    <?php // echo $form->field($model, 'inspector_general') ?>

    <?php // echo $form->field($model, 'celular') ?>

    <?php // echo $form->field($model, 'inscription_state') ?>

    <?php // echo $form->field($model, 'enrollment_deposit_message') ?>

    <?php // echo $form->field($model, 'codigo_distrito') ?>

    <?php // echo $form->field($model, 'enrollment_payment_way_message_year') ?>

    <?php // echo $form->field($model, 'enrollment_payment_way_message_month') ?>

    <?php // echo $form->field($model, 'name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
