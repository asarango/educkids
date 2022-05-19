<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisArea */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$modelUsuario = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();
$fecha = date("Y-m-d H:i:s");

//print_r($model);


?>

    <div class="scholaris-area-form">

        <?php $form = ActiveForm::begin(); ?>

        <!--<? $form->field($model, 'create_uid')->hiddenInput(['value' => $modelUsuario->id])->label(false) ?> -->
        <?= $form->field($model, 'codigo')->textInput()->label('CÓDIGO') ?>
        
        
        <?php
            $var=[
                'TIPO-DISCAPACIDAD' => 'TIPO CATEGORIA',
                'TECNICAS-INSTRUMENTOS' => 'TÉCNICAS E INSTRUMENTOS',
                'RECURSOS-TECNICOS' => 'RECURSOS TÉCNICOS',
                'RECURSOS-HUMANOS' => 'RECURSOS HUMANOS',
                'RECURSOS-DIDACTICOS' => 'RECURSOS DIDÁCTICOS'
                ]
        ?>
        <?= $form->field($model, 'categoria')->dropDownList($var, ['prompt' => 'Seleccione Uno'])->label('CATEGORÍA') ?>
        
        <?= $form->field($model, 'nombre')->textInput()->label('CONTENIDO') ?>
        
        <?php
        if(!$model->isNewRecord){
            echo $form->field($model, 'estado')->checkBox(['label' => 'ESTADO', 'data-size' => 'small', 'class' => 'form-checkbox'
        , 'style' => 'margin-bottom:10px;margin-left:4px', 'id' => 'active']); ;
        }
        
        ?>
        
        
        
        

        <div class="form-group" style="margin-top: 10px">
            <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
