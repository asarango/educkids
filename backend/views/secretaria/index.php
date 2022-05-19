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
    

        <div class="row" style="width: 100%; height: 100%">
            
            <!--INICIO CARD DE REPORTES-->
            <div class="col-lg-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">Reportes Institucionales</div>
                    <div class="panel-body">
                        <ul>
                            <li><a href="<?= Url::to(['reportes-parcial/index']) ?>">&nbsp;Reportes por parcial</a></li>
                            <li><a href="<?= Url::to(['reportes-quimestral/index']) ?>">&nbsp;Reportes Generales</a></li>
                            <li><a href="<?= Url::to(['fin-ano/index1']) ?>">&nbsp;Proceso de cierre de año por paralelo</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--fin CARD DE REPORTES-->
            
            <!--INICIO CARD DE REPORTES-->
            <div class="col-lg-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">Procesos Especiales</div>
                    <div class="panel-body">
                        <ul>
                            <li><a href="<?= Url::to(['scholaris-calificaciones/index1']) ?>">&nbsp;Cambiar Notas</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--fin CARD DE REPORTES-->
            
            
        </div>
    
    
    
</div>


