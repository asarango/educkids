<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTareaInicial */
/* @var $form yii\widgets\ActiveForm */


$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");
?>


<div class="padre-formulariosubir">

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=
                    Url::to(['listaactividadesinicial', 'id' => $modelAlumno->id,
                        'paralelo' => $modelActividad->clase->paralelo_id])
                    ?>">Volver</a></li>                
                <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                                
                <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
                <li class="breadcrumb-item active" aria-current="page">ACTIVIDAD: <?= $modelActividad->titulo ?></li>
            </ol>
        </nav> 

        <div class="row">

            <div class="col-md-4"></div>
            <div class="col-md-4">
                
                <div class="alert-warning">
                    <strong>Por favor subir el archivo !!!</strong>
                </div>
                
                <?php
                $form = ActiveForm::begin([
                            'options' => ['enctype' => 'multipart/form-data']
                ]);
                ?>

                <?= $form->field($model, 'tarea_inicial_id')->hiddenInput(['value' => $modelActividad->id])->label(false) ?>

                <?= $form->field($model, 'alumno_id')->hiddenInput(['value' => $modelAlumno->id])->label(false) ?>

                <?= $form->field($model, 'archivo')->fileInput() ?>

                <?= $form->field($model, 'calificacion')->hiddenInput()->label(false) ?>

                <?= $form->field($model, 'detalle_calificacion')->hiddenInput()->label(false) ?>

                <?= $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

                <?= $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

                <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

                <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>




                    <?php if (!Yii::$app->request->isAjax) { ?>
                    <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Subir archivo' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                <?php } ?>

<?php ActiveForm::end(); ?>
            </div>

            <div class="col-md-4"></div>

        </div>



    </div>
