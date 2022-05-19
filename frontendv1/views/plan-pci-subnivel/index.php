<?php

use yii\helpers\Html;
use kartik\grid\GridView;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use backend\models\PlanNivel;
use backend\models\PlanCurriculo;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PlanPciSubnivelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$listaNivel = PlanNivel::find()->all();


$this->title = 'PCI - SUBNIVELES DE EDUCACIÓN';
$pdfTitle = $this->title;
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
<div class="plan-pci-subnivel-index">

    <div class="container">
    <p>
        <?= Html::a('Crer PCI', ['create'], ['class' => 'btn btn-success']) ?>
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
            'panel' => ['type' => 'primary', 'heading' => 'Listado de ' . $pdfTitle],
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
//            'nivel_id',
            
            [
                'attribute' => 'nivel_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->nivel->nombre;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($listaNivel, 'id', 'nombre'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                'format' => 'raw',
            ],
            
            'curriculo_id',
            'estado',
            'creado_por',
            //'creado_fecha',
            //'actualizado_por',
            //'actualizado_fecha',

            /** INICIO BOTONES DE ACCION * */
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                'width' => '150px',
                'vAlign' => 'middle',
                'template' => '{view}{update}{detalle}',
                'buttons' => [
                    'detalle' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-list"></span>', $url, [
                                    'title' => 'Detalle', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                        ]);
                    }
                ],
                'urlCreator' => function($action, $model, $key) {
                    if ($action === 'detalle') {
                        return \yii\helpers\Url::to(['plan-pci-asignaturas/index1', 'id' => $key]);
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
