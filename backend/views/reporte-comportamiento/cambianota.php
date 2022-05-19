<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoCambionota */

$this->title = 'Modificando Nota de comportamiento: ' . $model->alumno->last_name . ' ' . $model->alumno->first_name . ' ' . $model->alumno->middle_name;
$this->params['breadcrumbs'][] = ['label' => 'Motivos de cambio de notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="scholaris-asistencia-comportamiento-cambionota-create">



    <div class="container">
        <div class="reporte-comportamiento-cambianota-form">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'calificacion')->textInput(['maxlength' => true]) ?>

            <?php
            
            $listData = yii\helpers\ArrayHelper::map($modelMotivos, 'nombre', 'nombre');
            
            echo $form->field($model, 'observacion')->widget(Select2::className(), [
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Horario...'],
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

    </div>
</div>