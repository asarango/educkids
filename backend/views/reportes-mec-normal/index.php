<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'REPORTES MEC ' . $model->course->name . ' ' . $model->name;
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="rportes-parcial-index">-->


<div class="container">

    <div class="row">
        <div class="col-md-6">Reporte por quimestre</div>
        <div class="col-md-6"><?= Html::a('Generar Reporte', ['reportes-mec-normal-quimestre/index', 'paralelo' => $model->id], ['class' => 'btn btn-link']) ?></div>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-6">Reporte final y supletorios</div>
        <div class="col-md-6"><?= Html::a('Generar Reporte', ['reportes-mec-normal-final/index', 'paralelo' => $model->id], ['class' => 'btn btn-link']) ?></div>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-6">Reporte Aptitud</div>
        <div class="col-md-6"><?= Html::a('Generar Reporte', ['reportes-mec-normal-promociones/index', 'paralelo' => $model->id, 'reporte' => 'aptitud'], ['class' => 'btn btn-link']) ?>
        </div>
    </div>
    <hr>


    <div class="row">
        <div class="col-md-6">Nómina de matriculados</div>
        <div class="col-md-6"><?=
            Html::a('Generar Reporte', ['reportes-mec-normal-nomina/index',
                'paralelo' => $model->id], ['class' => 'btn btn-link'])
            ?>
        </div>

    </div>
    <hr>


</div>