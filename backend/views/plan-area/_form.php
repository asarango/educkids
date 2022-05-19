<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\color\ColorInput;
use yii\helpers\ArrayHelper;
use backend\models\PlanAreaSup;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanArea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-area-form">

    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

        <?php
        $lista = PlanAreaSup::find()->all();
        $listData = ArrayHelper::map($lista, 'id', 'nombre');
        echo $form->field($model, 'area_id')->widget(Select2::className(), [
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione area...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?php
        echo $form->field($model, 'color')->widget(ColorInput::classname(), [
            'options' => ['placeholder' => 'Select color ...'],
        ]);
        ?>

        <?= $form->field($model, 'en_ministerio')->checkbox() ?>



        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
