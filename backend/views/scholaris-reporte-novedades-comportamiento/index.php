<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisReporteNovedadesComportamientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Novedades de Comportamiento';

?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">        
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['/profesor-inicio/index'], ['class' => 'btn btn-link']); ?>
        </li>

        <li class="breadcrumb-item">
            <?php echo Html::a('Registro docente', ['/scholaris-asistencia-profesor/index'], ['class' => 'btn btn-link']); ?>
        </li>

        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>
<div class="scholaris-reporte-novedades-comportamiento-index" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

//            'novedad_id',
            'bloque',
            'semana',
            'fecha',
            'hora',
            'materia',
            'estudiante',
            'curso',
            'paralelo',
            'codigo',
            'falta',
            'observacion:ntext',
            'justificacion:ntext',
            'usuario',

//            ['class' => 'yii\grid\ActionColumn'],
            /** INICIO BOTONES DE ACCION * */
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'width' => '150px',
                    'vAlign' => 'middle',
                    'template' => '{view}',
                    'buttons' => [
//                        'objetivos' => function($url, $model) {
//                            return Html::a('<span class="glyphicon glyphicon-road"></span>', $url, [
//                                        'title' => 'Objetivos', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
//                            ]);
//                        },
//                        'destreza' => function($url, $model) {
//                            return Html::a('<span class="glyphicon glyphicon-tasks"></span>', $url, [
//                                        'title' => 'Destrezas', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
//                            ]);
//                        },'evaluacion' => function($url, $model) {
//                            return Html::a('<span class="glyphicon glyphicon-ok-circle"></span>', $url, [
//                                        'title' => 'Evaluaciones', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
//                            ]);
//                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'view') {
                            return \yii\helpers\Url::to(['view', 'id' => $key]);                        
                        } else if ($action === 'update') {
                            return \yii\helpers\Url::to(['scholaris-clase-aux/update', 'id' => $key]);
                        }  
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
