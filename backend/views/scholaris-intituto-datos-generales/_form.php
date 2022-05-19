<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisIntitutoDatosGenerales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-intituto-datos-generales-form">
    
    <div class="container">
        
    

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'instituto_id')->hiddenInput(['value' => $id])->label(FALSE) ?>

    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo_amie')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'provincia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'canton')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parroquia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sitio_web')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sostenimiento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'regimen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modalidad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'niveles_curriculares')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subniveles')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'distrito')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'circuito')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jornada')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'horario_trabajo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'genero')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ejecucion_desde')->textInput() ?>

    <?= $form->field($model, 'ejecucion_hasta')->textInput() ?>

    <?= $form->field($model, 'financiamiento')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>