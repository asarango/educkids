<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\ResUsers;
use kartik\select2\Select2;
use backend\models\ScholarisTipoActividad;
use backend\models\ScholarisHorariov2Horario;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisActividad */
/* @var $form yii\widgets\ActiveForm */


$hoy = date("Y-m-d H:i:s");
$usuarioLogueado = Yii::$app->user->identity->usuario;
$modelUsuarios = ResUsers::find()->where(['login' => $usuarioLogueado])->one();

$usuario = $modelUsuarios->id;
$this->title = 'Datos de la nueva Actividad';

//print_r($modelSemana);
//die();


if($modelSemana <> '0'){
    $semanaDet = $modelSemana->id;
}else{    
    $semanaDet = 0;
}


//if($modelSemana == 0){
//    $semanaDet = 0;
//}else{
//    $semanaDet = $modelSemana->id;
//}

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Regresar', ['create', "claseId" => $modelClase->id, 'bloqueId' => $bloque]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>

<div class="scholaris-actividad-_formcreate">

    <div class="container">

        <h3><?= Html::encode($this->title) ?></h3>

        <?php $form = ActiveForm::begin(); ?>

        <!--ocultos--> 

        <?= $form->field($model, 'create_date')->hiddenInput(['value' => $hoy])->label(FALSE) ?>

        <?= $form->field($model, 'write_date')->hiddenInput(['value' => $hoy])->label(FALSE) ?>

        <?= $form->field($model, 'create_uid')->hiddenInput(['value' => $usuario])->label(FALSE) ?>

        <?= $form->field($model, 'write_uid')->hiddenInput(['value' => $usuario])->label(FALSE) ?>

        <?= $form->field($model, 'color')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

        <?= $form->field($model, 'inicio')->hiddenInput(['value' => $inicio])->label(FALSE) ?>

        <?= $form->field($model, 'fin')->hiddenInput(['value' => $inicio])->label(FALSE) ?>

        <?= $form->field($model, 'bloque_actividad_id')->hiddenInput(['value' => $bloque])->label(FALSE) ?>

        <?= $form->field($model, 'a_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

        <?= $form->field($model, 'b_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

        <?= $form->field($model, 'c_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

        <?= $form->field($model, 'd_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

        <?= $form->field($model, 'paralelo_id')->hiddenInput(['value' => $modelClase->id])->label(FALSE) ?>

        <?= $form->field($model, 'materia_id')->hiddenInput(['value' => $modelClase->idmateria])->label(FALSE) ?>                

        <?= $form->field($model, 'actividad_original')->hiddenInput(['value' => 0])->label(FALSE) ?>

        <?= $form->field($model, 'semana_id')->hiddenInput(['value' => $semanaDet])->label(FALSE) ?>

        <?= $form->field($model, 'archivo')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'descripcion_archivo')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'tipo_calificacion')->hiddenInput(['value' => $tipo])->label(FALSE) ?>    

        <!--fin ocultos-->

        <div class="row">
            <div class="col-md-4">
                <?php
                $listData = ArrayHelper::map($modelInsumo, 'id', 'nombre_nacional');
                echo $form->field($model, 'tipo_actividad_id')->widget(Select2::className(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Seleccione Insumo...'],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'calificado')->dropDownList(['SI' => 'SI', 'NO' => 'NO']) ?>
            </div>
            <div class="col-md-4">
                <?php
                $listData = ArrayHelper::map($horas, 'id', 'sigla');
                echo $form->field($model, 'hora_id')->dropDownList($listData, ['prompt' => 'Seleccione Hora...'])
                ?>
            </div>
        </div>





        <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('TÍTULO - ENSEÑANZA') ?>



        <?= $form->field($model, 'descripcion')->textarea(['rows' => '6'])->label('DESCRIPCIÓN - ACTIVIDADES') ?>

        <?= $form->field($model, 'tareas')->textarea(['rows' => '6'])->label('TAREAS') ?>




        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>
</div>
