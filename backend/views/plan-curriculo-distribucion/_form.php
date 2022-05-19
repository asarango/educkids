<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\PlanNivel;
use backend\models\PlanCurriculo;
use backend\models\PlanArea;
use backend\models\OpFaculty;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDistribucion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-distribucion-form">
    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <?php
        $lista = PlanNivel::find()->all();
        $listData = ArrayHelper::map($lista, 'id', 'nombre');
        echo $form->field($model, 'nivel_id')->widget(Select2::className(), [
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione nivel...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?php
        $lista = PlanCurriculo::find()->all();
        $listData = ArrayHelper::map($lista, 'id', 'ano_incia');
        echo $form->field($model, 'curriculo_id')->widget(Select2::className(), [
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione currículo...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?php
        $lista = PlanArea::find()->all();
        $listData = ArrayHelper::map($lista, 'id', 'nombre');
        echo $form->field($model, 'area_id')->widget(Select2::className(), [
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Área...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?php 
        $lista = OpFaculty::find()->all();
        $listData = ArrayHelper::map($lista, 'id', 'last_name');
        
        echo $form->field($model, 'jefe_area_id')->widget(Select2::className(),[
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Jefe de Área...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); 
                
                ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
