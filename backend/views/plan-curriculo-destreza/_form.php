<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\PlanCurriculoBloque;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDestreza */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-destreza-form">

    <div class="container">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'distribucion_id')->hiddenInput(['value' => $id])->label(FALSE) ?>

        <?php
        $lista = PlanCurriculoBloque::find()->all();
        $listData = ArrayHelper::map($lista, 'id', 'nombre');

        echo $form->field($model, 'bloque_id')->widget(Select2::className(), [
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione bloque...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
