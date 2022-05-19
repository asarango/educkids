<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Secretaria';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="secretaria-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <div class="container">
        <div class="row">
            
            <!--INICIO CARD DE REPORTES-->
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">Procesos de soporte</div>
                    <div class="panel-body">
                        <ul>
                            <li><a href="<?= Url::to(['scholaris-clase-libreta/actualizar']) ?>">&nbsp;Actualizar promedios libreta</a></li>
                            <!--<li><a href="<?= Url::to(['reportes-quimestral/index']) ?>">&nbsp;Reportes Generales</a></li>-->
                        </ul>
                    </div>
                </div>
            </div>
            <!--fin CARD DE REPORTES-->
            
            <!--INICIO CARD DE REPORTES-->
<!--            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">Procesos Especiales</div>
                    <div class="panel-body">
                        <ul>
                            <li><a href="<?= Url::to(['scholaris-calificaciones/index1']) ?>">&nbsp;Cambiar Notas</a></li>
                        </ul>
                    </div>
                </div>
            </div>-->
            <!--fin CARD DE REPORTES-->
            
            
        </div>
    </div>



    
</div>