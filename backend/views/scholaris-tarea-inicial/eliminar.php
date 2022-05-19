<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTareaInicialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => 'Lista de Tareas', 'url' => ['index1', 'clase' => $model->clase->id, 'quimestre' => $model->quimestre_codigo]];
$this->title = 'Tarea Inicial - Preparatoria / ' . $model->titulo
        . ' / ' . $model->clase->curso->name
        . ' / ' . $model->clase->paralelo->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-tarea-inicial-eliminar" style="padding-left: 50px; padding-right: 50px">

    <div class="" style="padding: 50px; background-color: white">
        <div class="row">
            <div class="alert alert-danger">
                <h1><strong>ALERTA!!!</strong></h1>
                Usted recibió algunos trabajos de estudiantes, si elimina la tarea, se borrarán todos los trabajos entregados son la opción de recuperar.
                <p><strong>Si esta seguro de eliminar, presione el boton ELIMINAR</strong></p>
            </div>


            <p>
                <?= Html::a('Eliminar Tarea', ['ejecuta-eliminar', 'tarea_id' => $model->id], ['class' => 'btn btn-danger']) ?>
            </p>
            
            
        </div>
    </div>


</div>