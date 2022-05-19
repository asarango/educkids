<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use backend\models\PlanNivel;
use backend\models\PlanCurriculo;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanPciSubnivel */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");

?>

<div class="plan-pci-subnivel-form">

    <div class="container">
    
    
    <?php $form = ActiveForm::begin(); ?>

    <?php
    $lista = PlanNivel::find()->all();
    $listData = ArrayHelper::map($lista, 'id', 'nombre');
    echo $form->field($model, 'nivel_id')->widget(Select2::className(),[
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione subnivel...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
            ?>

    <?php
    $lista = PlanCurriculo::find()->all();
    $listData = ArrayHelper::map($lista, 'id', 'ano_incia');
    echo $form->field($model, 'curriculo_id')->widget(Select2::className(),[
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione currículo...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
            ?>

    <?= $form->field($model, 'estado')->dropDownList([
        'CREADO' => 'CREADO'
    ]) ?>

    <?php
    if($model->isNewRecord){
        echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(FALSE);
    }else{
        echo $form->field($model, 'creado_por')->hiddenInput(['maxlength' => true])->label(FALSE);
    }
    ?>
        
        <?php
    if($model->isNewRecord){
        echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(FALSE);
    }else{
        echo $form->field($model, 'creado_fecha')->hiddenInput(['maxlength' => true])->label(FALSE);
    }
    ?>


    <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(FALSE) ?>

    <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(FALSE) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>