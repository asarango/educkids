<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTomaAsistecia */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Justificacion de estudiante: ' . $modelNovedad->asistenciaProfesor->clase->course->name .
        ' - ' . $modelNovedad->asistenciaProfesor->clase->paralelo->name .
        ' / ' . $modelNovedad->asistenciaProfesor->clase->materia->name .
        ' / ' . $modelNovedad->asistenciaProfesor->clase->profesor->last_name .
        ' ' . $modelNovedad->asistenciaProfesor->clase->profesor->x_first_name.
        ' / ' . $modelNovedad->grupo->alumno->last_name.
        ' ' . $modelNovedad->grupo->alumno->first_name
;
$this->params['breadcrumbs'][] = $this->title;

$usuario = Yii::$app->user->identity->usuario;
$modelUsuario = backend\models\ResUsers::find()->where(['login' => $usuario])->one();
$fecha = date("Y-m-d H:i:s");
?>

<div class="scholaris-toma-asistecia-justificaralumno">

    <div class="container">
        <div class="alert alert-info">
            <strong>
                <?=
                $modelNovedad->comportamientoDetalle->comportamiento->nombre
                . ' '
                . $modelNovedad->comportamientoDetalle->codigo . ' .- '
                . $modelNovedad->comportamientoDetalle->nombre
                ?>
            </strong>
            <p>
                <?= $modelNovedad->observacion ?>
            </p>

        </div>


        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'novedad_id')->hiddenInput(['value' => $modelNovedad->id])->label(false) ?>
        <?= $form->field($model, 'fecha')->hiddenInput(['value' => $fecha])->label(false) ?>
        <?= $form->field($model, 'usuario_crea')->hiddenInput(['value' => $modelUsuario->id])->label(false) ?>
        <?= $form->field($model, 'codigo_persona')->hiddenInput(['value' => 0])->label(false) ?>
        <?= $form->field($model, 'tipo_persona')->hiddenInput(['value' => 0])->label(false) ?>
        <?= $form->field($model, 'motivo_justificacion')->textarea(['rows' => 3])?>
        <?php 
    $lista = \backend\models\ScholarisAsistenciaComportamientoDetalle::find()
            ->orderBy('comportamiento_id')
            ->all();
    $data = ArrayHelper::map($lista, 'id', 'nombre');
    
    echo $form->field($model, 'opcion_justificacion_id')->widget(Select2::className(),[
        'data' => $data,
            'options' => ['placeholder' => 'Seleccione Opcion de Cambio...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ]
    ]);
    ?>
        
         <?= $form->field($model, 'estado')->hiddenInput(['value' => 1])->label(false)?>

        <div class="form-group">
            <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>