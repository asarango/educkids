<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use backend\models\PlanArea;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanPciAsignaturas */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");

?>

<div class="plan-pci-asignaturas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pci_subnivel_id')->hiddenInput(['value' => $id])->label(false) ?>

    <?php
    $lista = PlanArea::find()
            ->select(["plan_curriculo_distribucion.id", "concat(nombre) as nombre"])
            ->innerJoin("plan_curriculo_distribucion","plan_curriculo_distribucion.area_id = plan_area.id")
            ->where(['nivel_id' => $modelPci->nivel_id])
            ->all();
    $listData = ArrayHelper::map($lista, 'id', 'nombre');
        
    echo $form->field($model, 'distribucion_id')->widget(Select2::className(),[
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione Asignatura...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
            ?>

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false);
    }else{
        echo $form->field($model, 'creado_por')->hiddenInput(['maxlength' => true])->label(false);
    }
    
    ?>

    <?php
    if($model->isNewRecord){
        echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false);
    }else{
        echo $form->field($model, 'creado_fecha')->hiddenInput()->label(false);
    }
    ?>

    <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

    <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
