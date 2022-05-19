<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisCalificacionesInicialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Observación de archivos: ' . $model->alumno->last_name . ' '
        . $model->alumno->first_name . ' ' . $model->alumno->middle_name
        . ' / ' . $model->archivo
;

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-tarea-inicial-recibir">
    <div class="alert alert-info">
        <strong><?= $model->tareaInicial->titulo ?></strong>
    </div>

    <div class="container">

        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'tarea_inicial_id')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'alumno_id')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'archivo')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'calificacion')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'detalle_calificacion')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'creado_por')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'creado_fecha')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'actualizado_por')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'actualizado_fecha')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'observacion_profesor')->textarea(['rows' => 6]) ?>

                

                

                <div class="form-group">
                    <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-md-2"></div>
        </div>


    </div>

</div>

