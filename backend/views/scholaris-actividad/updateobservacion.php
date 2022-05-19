<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\ResUsers;
use kartik\select2\Select2;
use backend\models\ScholarisTipoActividad;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisActividad */
/* @var $form yii\widgets\ActiveForm */


$fecha = date("Y-m-d H:i:s");
$usuarioLogueado = Yii::$app->user->identity->usuario;
$modelUsuarios = ResUsers::find()->where(['login' => $usuarioLogueado])->one();

$usuario = $modelUsuarios->id;

$this->title = 'Calificación de actividades';

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <!--<li class="breadcrumb-item"><a href="#">Home</a></li>-->
        <li class="breadcrumb-item">
            <?php echo Html::a('Acciones Calificaciones', ['anularcalificaciones', "id" => $model->idactividad]); ?>
        </li>

        <li class="breadcrumb-item active" aria-current="page">
            <?= 
                $model->alumno->last_name.' '.$model->alumno->first_name.' '.$model->alumno->middle_name.' / '
                .$this->title 
            ?>
        </li>
    </ol>
</nav>

<div class="scholaris-actividad-form">
    
    
    <div class="alert alert-info">
        <strong><?= $model->actividad->title ?></strong>
    </div>
    
    

    <div class="container">

        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <?php $form = ActiveForm::begin(); ?>


                <!--ocultos--> 

                <?= $form->field($model, 'idalumno')->hiddenInput()->label(FALSE) ?>
                <?= $form->field($model, 'idactividad')->hiddenInput()->label(FALSE) ?>
                <?= $form->field($model, 'idtipoactividad')->hiddenInput()->label(FALSE) ?>
                <?= $form->field($model, 'idperiodo')->hiddenInput()->label(FALSE) ?>
                <?= $form->field($model, 'calificacion')->hiddenInput()->label(FALSE) ?>
                <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
                <?= $form->field($model, 'criterio_id')->hiddenInput()->label(FALSE) ?>
                <?= $form->field($model, 'estado_proceso')->hiddenInput()->label(FALSE) ?>
                <?= $form->field($model, 'grupo_numero')->hiddenInput()->label(FALSE) ?>
                <?= $form->field($model, 'estado')->hiddenInput()->label(FALSE) ?>

                <div class="form-group">
                    <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
            <div class="col-2"></div>
        </div>





    </div>
</div>
