<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFechasCierreAnio */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-fechas-cierre-anio-form">

    <div class="row">
        <div class="col-lg-4 col-md-4"></div>
        <div class="col-lg-4 col-md-4">
            <?php $form = ActiveForm::begin(); ?>

            <?php
            $data = ArrayHelper::map($modelPeriodo, 'id', 'nombre');
            echo $form->field($model, 'scholaris_periodo_id')->widget(Select2::className(), [
                'data' => $data,
                'options' => ['placeholder' => 'Seleccione Periodo...'],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ]
            ]);
            ?>

            <?= $form->field($model, 'fecha')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

            <div class="form-group">
                <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-4 col-md-4"></div>
    </div>



</div>
