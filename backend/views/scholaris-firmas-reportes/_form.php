<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFirmasReportes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-firmas-reportes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $data = ArrayHelper::map($modelTemplates, 'id', 'name');
    echo $form->field($model, 'template_id')->widget(Select2::className(), [
        'data' => $data,
        'options' => ['placeholder' => 'Seleccione Curso...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ]
    ]);
    ?>

    <?= $form->field($model, 'codigo_reporte')->hiddenInput(['value' => 'MEC'])->label(false) ?>
    
    <?php
    
    $data = ArrayHelper::map($modelInstitutos, 'id', 'name');
    echo $form->field($model, 'instituto_id')->widget(Select2::className(), [
        'data' => $data,
        'options' => ['placeholder' => 'Seleccione Instituto...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ]
    ])
    ?>
    
    <?= $form->field($model, 'principal_cargo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'principal_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'secretaria_cargo')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'secretaria_nombre')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
<?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
