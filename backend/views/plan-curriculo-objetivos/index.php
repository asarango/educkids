<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanCurriculoObjetivosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Objetivos por Nivel';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Distribución', 'url' => ['plan-curriculo-distribucion/index']];
$this->params['breadcrumbs'][] = $this->title;

$pdfHMTLHeader = 'EMPRESA';
$pdfHeader = [
    'L' => [
        'content' => '',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'C' => [
        'content' => $pdfTitle,
        'font-size' => 12,
        //'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'R' => [
        'content' => $pdfHMTLHeader,
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'line' => 1,
];
$pdfFooter = [
    'L' => [
        'content' => '',
        'font-size' => 8,
        'font-style' => '',
        'font-family' => 'arial',
        'color' => '#929292'
    ],
    'C' => [
        'content' => '',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'R' => [
        'content' => '{PAGENO}',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'line' => 1,
];

?>
<div class="plan-curriculo-objetivos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <h4>
        <?= 
            $modelDistribucion->curriculo->ano_incia.' - '.$modelDistribucion->curriculo->ano_finaliza
            .' / '
            .$modelDistribucion->nivel->nombre
            .' / '
            .$modelDistribucion->area->nombre
            .' / '
            .$modelDistribucion->jefeArea->last_name.' '.$modelDistribucion->jefeArea->x_first_name
        ?>
    </h4>

    <!--<div class="container">-->
    <p>
        <?= Html::a('Crear Objetivo', ['create','id' => $id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'bootstrap' => true,
            'bordered' => true,
            'showPageSummary' => true,
            'pageSummaryRowOptions' => ['class' => 'kv-page-summary info'],
            'floatHeader' => true,
            'floatHeaderOptions' => ['scrollingTop' => '50'],
            'pjax' => false,
            'striped' => true,
            'hover' => true,
            'responsive' => true,
            'panel' => ['type' => 'primary', 'heading' => 'Listado '.$pdfTitle],
            'rowOptions' => ['style' => 'font-size:12px'],
            'footerRowOptions' => ['style' => 'font-size:12px'],
            'captionOptions' => ['style' => 'font-size:18px'],
            'headerRowOptions' => ['style' => 'font-size:12px'],
            'export' => [
                'fontAwesome' => true,
                'showConfirmAlert' => true,
                'target' => GridView::TARGET_BLANK
            ],
            'exportConfig' => [
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

            'id',
//            'distribucion_id',
            'codigo_ministerio',
            'descripcion:ntext',
            'tipo_objetivo',

            /** INICIO BOTONES DE ACCION * */
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'width' => '150px',
                    'vAlign' => 'middle',
                    'template' => '{view}{update}{delete}',
                    'buttons' => [
                        'objetivos' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-road"></span>', $url, [
                                        'title' => 'Objetivos', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
                        'alumnos' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-list"></span>', $url, [
                                        'title' => 'Alumnos', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
                        'horario' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-dashboard"></span>', $url, [
                                        'title' => 'Horario', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'view') {
                            return \yii\helpers\Url::to(['plan-curriculo-objetivos/index1', 'id' => $key]);
                        }else if ($action === 'update') {
                            return \yii\helpers\Url::to(['update', 'id' => $key]);
                        } else if ($action === 'delete') {
                            return \yii\helpers\Url::to(['scholaris-grupo-alumno-clase/index1', 'id' => $key]);
                        }
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
<!--</div>-->