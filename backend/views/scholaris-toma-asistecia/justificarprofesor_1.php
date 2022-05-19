<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTomaAsistecia */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Justificacion profesor: '.$modelClase->course->name.' - '.$modelClase->paralelo->name.
        ' / '.$modelClase->materia->name.
        ' / '.$modelClase->profesor->last_name.' '.$modelClase->profesor->x_first_name.
        ' / '.$fecharegistro . ' - '. $modelHora->nombre
        ;
$this->params['breadcrumbs'][] = $this->title;

$usuario = Yii::$app->user->identity->usuario;
$modelUsuario = backend\models\ResUsers::find()->where(['login' => $usuario])->one();
$fecha = date("Y-m-d H:i:s");

?>

<div class="scholaris-toma-asistecia-justificarprofesor">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'asistencia_id')->hiddenInput(['value' => 0])->label(false) ?>
    <?= $form->field($model, 'fecha')->hiddenInput(['value' => $fecha])->label(false) ?>
    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'usuario_crea')->hiddenInput(['value' => $modelUsuario->id])->label(false);
    }else{
        echo $form->field($model, 'usuario_crea')->hiddenInput()->label(false);
    }
    ?>
    
    <?= $form->field($model, 'codigo_persona')->hiddenInput(['value' => $modelClase->idprofesor])->label(false)?>
    
    <?= $form->field($model, 'tipo_persona')->hiddenInput(['value' => 1])->label(false)?>
    
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
    
    <?= $form->field($model, 'estado')->hiddenInput()->label(false)->label(false)?>
    <?= $form->field($model, 'fecha_registro')->hiddenInput(['value' => $fecharegistro])->label(false)?>
    <?= $form->field($model, 'hora_registro')->hiddenInput(['value' => $hora])->label(false)?>
    <?= $form->field($model, 'tiempo_justificado')->textInput()?>

    

    <div class="form-group">
<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
