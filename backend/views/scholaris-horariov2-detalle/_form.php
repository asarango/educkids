<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisHorariov2Detalle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-horariov2-detalle-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'cabecera_id')->hiddenInput(['value' => $modelCabecera->id])->label(false);
        }else{
            echo $form->field($model, 'cabecera_id')->hiddenInput(['value' => $model->cabecera_id])->label(false);
        }
         
            
    ?>

    
    <?php 
        $listaDias = \backend\models\ScholarisHorariov2Dia::find()->orderBy('numero')->all();
        $dataD = ArrayHelper::map($listaDias, 'id', 'nombre');
        
        echo $form->field($model, 'dia_id')->widget(Select2::className(),[
            'data' => $dataD,
            'options' => ['placeholder' => 'Seleccione Dia...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]); 
    ?>
    
    <?php 
        $listaHoras = backend\models\ScholarisHorariov2Hora::find()
                ->select(['id', "concat(sigla,' ',desde,' ',hasta) as sigla"])
                ->orderBy('numero')
                ->all();
        $dataH = ArrayHelper::map($listaHoras,'id', 'sigla');
        echo $form->field($model, 'hora_id')->widget(Select2::className(),[
            'data' => $dataH,
            'options' => ['placeholder' => 'Seleccione Hora...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
