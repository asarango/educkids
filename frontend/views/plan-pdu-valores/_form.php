<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\PlanPduParametros;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanPduValores */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-pdu-valores-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cabecera_id')->hiddenInput(['value' => $id])->label(false) ?>

    <?php 
    $lista = PlanPduParametros::find()
            ->where(['tipo_parametro' => 'VALOR INSTITUCIONAL', 'estado' => TRUE])
            ->all();

    $listData = ArrayHelper::map($lista, 'id', 'nombre');
    
    echo $form->field($model, 'parametro_id')->widget(Select2::className(),[
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione Parámetro...'],
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
