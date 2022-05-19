<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\PlanCurriculoDistribucion;
use backend\models\OpCourse;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificaciones';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
$pdfHMTLHeader = 'EMPRESA';
$pdfHeader = [
    'L' => [
      'content' => '',
      'font-size' => 10,
      'font-style' => 'B',
      'font-family' => 'arial',
      'color'=>'#000000'
    ],
    'C' => [
      'content' => $pdfTitle,
      'font-size' => 12,
      //'font-style' => 'B',
      'font-family' => 'arial',
      'color'=>'#000000'
    ],
    'R' => [
      'content' => $pdfHMTLHeader,
      'font-size' => 10,
      'font-style' => 'B',
      'font-family' => 'arial',
      'color'=>'#000000'
    ],
    'line' => 1,
];
$pdfFooter = [
    'L' => [
      'content' => '',
      'font-size' => 8,
      'font-style' => '',
      'font-family' => 'arial',
      'color'=>'#929292'
    ],
    'C' => [
      'content' => '',
      'font-size' => 10,
      'font-style' => 'B',
      'font-family' => 'arial',
      'color'=>'#000000'
    ],
    'R' => [
      'content' => '{PAGENO}',
      'font-size' => 10,
      'font-style' => 'B',
      'font-family' => 'arial',
      'color'=>'#000000'
    ],
    'line' => 1,
];


?>
<div class="plan-planificacion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="container">
    
    <p>
        <?= Html::a('Crear Planificacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        
        'bootstrap' => true,
        'bordered' => true,
        'showPageSummary' => true,
        'pageSummaryRowOptions' => ['class' => 'kv-page-summary info'],
        'floatHeader'=>true,
        'floatHeaderOptions'=>['scrollingTop'=>'50'],
        'pjax'=>false,
        'striped'=>true,
        'hover'=>true,
        'responsive'=>true,
        'panel'=>['type'=>'primary', 'heading'=>'Listado de '.$pdfTitle],
        'rowOptions' => ['style' => 'font-size:12px'],
        'footerRowOptions' => ['style' => 'font-size:12px'],
        'captionOptions' => ['style' => 'font-size:18px'],
        'headerRowOptions' => ['style' => 'font-size:12px'],
        'export'=>[
            'fontAwesome'=>true,
            'showConfirmAlert'=>true,
            'target'=>GridView::TARGET_BLANK
        ],
        'exportConfig'=>[
            
            GridView::HTML => [
                'label' => 'HTML',
                'filename' => $pdfTitle,
            ],
            GridView::CSV => [
                'label' => 'CSV',
                'filename' => $pdfTitle,
            ],
            GridView::TEXT => [
                'label' => 'Text',
                'filename' => $pdfTitle,
            ],
            GridView::EXCEL => [
                'label' => 'Excel',
                'filename' => $pdfTitle,
               
            ],
            GridView::PDF => [
                'filename' => $pdfTitle,
                'config' => [
                    //'mode' => 'c',
                    'mode' => 'utf-8',
                    'format' => 'A4-L',
                    'destination' => 'I',
                    'cssInline' => '.kv-wrap{padding:20px;}' .
                        '.kv-align-center{text-align:center;}' .
                        '.kv-align-left{text-align:left;}' .
                        '.kv-align-right{text-align:right;}' .
                        '.kv-align-top{vertical-align:top!important;}' .
                        '.kv-align-bottom{vertical-align:bottom!important;}' .
                        '.kv-align-middle{vertical-align:middle!important;}' .
                        '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
                    'methods' => [
                        'SetHeader' => [
                            ['odd' => $pdfHeader, 'even' => $pdfHeader]
                        ],
                        'SetFooter' => [
                            ['odd' => $pdfFooter, 'even' => $pdfFooter]
                        ],
                    ],
                    'options' => [
                        'title' => $pdfTitle,
                    ],
                ]
            ],
            GridView::JSON => [
                'label' => 'JSON',
                'filename' => $pdfTitle,
            ],
        ],
        
        
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

//            'id',
//            'distribucion_id',
            'distribucion.nivel.nombre',
            'distribucion.area.nombre',
            'curso_id',
            'curso.name',
//            'periodo_id',
            'estado',
            //'creado_por',
            //'creado_fecha',
            //'actualizado_por',
            //'actualizado_fecha',

            /** INICIO BOTONES DE ACCION * */
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                'width' => '150px',
                'vAlign' => 'middle',
                'template' => '{view}{update}{planificar}',
                'buttons' => [
                    'planificar' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-folder-open"></span>', $url, [
                                    'title' => 'PLANIFICAR', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                        ]);
                    }
                ],
                'urlCreator' => function($action, $model, $key) {
                    if ($action === 'planificar') {
                        return \yii\helpers\Url::to(['scholaris-acceso-usuario-clase/index', 'id' => $key]);
                    } else if ($action === 'view') {
                        return \yii\helpers\Url::to(['view', 'id' => $key]);
                    } else if ($action === 'update') {
                        return \yii\helpers\Url::to(['update', 'id' => $key]);
                    }
                }
            ],
        /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
</div>