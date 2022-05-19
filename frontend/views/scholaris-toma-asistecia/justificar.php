<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTomaAsistecia */
/* @var $form yii\widgets\ActiveForm */


$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");


$this->title = 'Justificación: '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Toma Asistecias', 'url' => ['detalle', 'id' => $model->toma_id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="scholaris-toma-asistecia-justificar">

    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">

            <?= $form->field($model, 'toma_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'alumno_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'asiste')->checkbox() ?>

            <div class="col-md-6">
                <?= $form->field($model, 'atraso')->checkbox() ?>
                <?= $form->field($model, 'atraso_justificado')->checkbox() ?>
                <?= $form->field($model, 'atraso_observacion_justificacion')->textarea() ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'falta')->checkbox() ?>
                <?= $form->field($model, 'falta_justificada')->checkbox() ?>
                <?= $form->field($model, 'falta_observacion_justificacion')->textarea() ?>
            </div>

        </div>

        <?= $form->field($model, 'creado_por')->hiddenInput()->label() ?>
        <?= $form->field($model, 'creado_fecha')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>
        <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>




</div>
