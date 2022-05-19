<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanCurriculoBloqueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bloques';
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
<div class="plan-curriculo-bloque-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="container">
    
    <p>
        <?= Html::a('Create Plan Curriculo Bloque', ['create'], ['class' => 'btn btn-success']) ?>
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
            'panel' => ['type' => 'primary', 'heading' => 'Listado de '.$pdfTitle],
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
            'codigo',
            'nombre',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>
</div>
</div>
