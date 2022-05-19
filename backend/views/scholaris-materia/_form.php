<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use backend\models\ScholarisPeriodo;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMateria */
/* @var $form yii\widgets\ActiveForm */

$periodoId = \Yii::$app->user->identity->periodo_id;
$modelPeriodo = ScholarisPeriodo::find()
        ->where(['id' => $periodoId])
        ->one();
?>

<div class="scholaris-materia-form">

    <div style="padding-left: 50px; padding-right: 50px; ">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'create_uid')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'create_date')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'abreviarura')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'write_uid')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'write_date')->hiddenInput()->label(false) ?>

        <?php
        $lista = backend\models\ScholarisArea::find()
                ->where(['period_id' => $ultimoPeriodo])
                ->all();



        $data = yii\helpers\ArrayHelper::map($lista, 'id', 'name');
        echo $form->field($model, 'area_id')->widget(\kartik\select2\Select2::className(), [
            'data' => $data,
            'options' => ['placeholder' => 'Seleccione Area...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
        ?>

        <?php
        echo $form->field($model, 'color')->widget(ColorInput::className(), [
            'options' => ['placeholder' => 'Seleccione color ...'],
        ]);
        ?>

        <?= $form->field($model, 'tipo')->dropDownList(['Cuantitativo' => 'Cuantitativo', 'Cualitativo' => 'Cualitativo']) ?>

        <?= $form->field($model, 'tipo_materia_id')->hiddenInput(['value' => 1])->label(false) ?>

        <?= $form->field($model, 'peso')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'orden')->textInput() ?>

        <?= $form->field($model, 'promedia')->textInput()->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'nombre_mec')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'language_code')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'is_active')->checkbox() ?>

        <?php
            $listCurriculo = backend\models\CurriculoMecAsignatutas::find()->all();
            $data = yii\helpers\ArrayHelper::map($listCurriculo, 'id', 'name');
            //if($model->isNewRecord()){
                echo $form->field($model, 'curriculo_asignatura_id')->dropDownList(
                    $data, ['prompt' => 'Ingrese Asignatura de Currículo ...']
                ) ;
            //} 
            
        ?>
        
        <?php
            $listNivelCurriculo = backend\models\CurriculoMecNiveles::find()->all();
            $data = yii\helpers\ArrayHelper::map($listNivelCurriculo, 'id', 'name');
            //if($model->isNewRecord()){
                echo $form->field($model, 'curriculo_nivel_id')->dropDownList(
                    $data, ['prompt' => 'Ingrese Nivel de Currículo ...']
                ) ;
            //} 
            
        ?>



        <br>
        <div class="form-group">
            <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
