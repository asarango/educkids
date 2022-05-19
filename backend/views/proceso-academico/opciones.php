<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Procesos Académicos de nivel: ' . $modelParalelo->course->name . ' ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = ['label' => 'Cursos y Paralelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proceso-academico-index">
    <div class="container">
        <div class="row">

            <!--INICIO CARD DE REPORTES-->
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">Faltas y Atrasos</div>
                    <div class="panel-body">
                        <ul>
                            <li><a href="<?= Url::to(['scholaris-faltas/index1', 'paralelo' => $modelParalelo->id]) ?>">&nbsp;Ingreso de faltas y atrasos</a></li>
                            <!--<li><a href="<?= Url::to(['tomar-lista/index1', 'paralelo' => $modelParalelo->id]) ?>">&nbsp;Tomar Lista</a></li>-->                            
                        </ul>
                    </div>
                </div>
            </div>
            <!--fin CARD DE REPORTES-->

            <!--INICIO CARD DE REPORTES-->
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">Leccionario</div>
                    <div class="panel-body">
                        <ul>
                            <li><a href="<?= Url::to(['scholaris-leccionario/index1', 'paralelo' => $modelParalelo->id]) ?>">&nbsp;Revisión de Novedades</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--fin CARD DE REPORTES-->
        </div>
    </div>
</div>