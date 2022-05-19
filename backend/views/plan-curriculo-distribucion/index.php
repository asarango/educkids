<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use \yii\helpers\ArrayHelper;
use backend\models\PlanNivel;
use backend\models\PlanCurriculo;
use backend\models\PlanArea;
use backend\models\OpFaculty;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanCurriculoDistribucionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Distribución del Currículo';
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
<div class="plan-curriculo-distribucion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <div class="container">
        <p>
            <?= Html::a('Create Plan Curriculo Distribucion', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?=
        GridView::widget([
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
//            'nivel_id',
                [
                    'attribute' => 'nivel_id',
                    'vAlign' => 'top',
                    'value' => function($model, $key, $index, $widget) {
                        return $model->nivel->nombre;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(PlanNivel::find()
                                    //->joinWith('scholaris_area')
                                    //->where(['scholaris_area.period_id' => $periodo])
                                    ->orderBy('nombre')->all(), 'id', 'nombre'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                    'format' => 'raw',
                ],
//            'curriculo_id',
                [
                    'attribute' => 'curriculo_id',
                    'vAlign' => 'top',
                    'value' => function($model, $key, $index, $widget) {
                        return $model->curriculo->ano_incia . ' - ' . $model->curriculo->ano_finaliza;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(PlanCurriculo::find()
                                    //->joinWith('scholaris_area')
                                    //->where(['scholaris_area.period_id' => $periodo])
                                    ->orderBy('ano_incia')->all(), 'id', 'ano_incia'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                    'format' => 'raw',
                ],
                //'area_id',
                [
                    'attribute' => 'area_id',
                    'vAlign' => 'top',
                    'value' => function($model, $key, $index, $widget) {
                        return $model->area->nombre;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(PlanArea::find()
                                    //->joinWith('scholaris_area')
                                    //->where(['scholaris_area.period_id' => $periodo])
                                    ->orderBy('nombre')->all(), 'id', 'nombre'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                    'format' => 'raw',
                ],
//        'jefe_area_id',
                [
                    'attribute' => 'jefe_area_id',
                    'vAlign' => 'top',
                    'value' => function($model, $key, $index, $widget) {
                        return $model->jefeArea->last_name . ' ' . $model->jefeArea->x_first_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(OpFaculty::find()
                                    ->select(["id", "concat(last_name,' ', x_first_name) as last_name"])
                                    //->joinWith('scholaris_area')
                                    //->where(['scholaris_area.period_id' => $periodo])
                                    ->orderBy('last_name')->all(), 'id', 'last_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                    'format' => 'raw',
                ],
                /** INICIO BOTONES DE ACCION * */
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'width' => '150px',
                    'vAlign' => 'middle',
                    'template' => '{view}{update}{objetivos}{destreza}{evaluacion}',
                    'buttons' => [
                        'objetivos' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-road"></span>', $url, [
                                        'title' => 'Objetivos', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
                        'destreza' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-tasks"></span>', $url, [
                                        'title' => 'Destrezas', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },'evaluacion' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-ok-circle"></span>', $url, [
                                        'title' => 'Evaluaciones', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'objetivos') {
                            return \yii\helpers\Url::to(['plan-curriculo-objetivos/index1', 'id' => $key]);
                        } else if ($action === 'view') {
                            return \yii\helpers\Url::to(['view', 'id' => $key]);
                        } else if ($action === 'update') {
                            return \yii\helpers\Url::to(['update', 'id' => $key]);
                        } else if ($action === 'destreza') {
                            return \yii\helpers\Url::to(['plan-curriculo-destreza/index1', 'id' => $key]);
                        }else if ($action === 'evaluacion') {
                            return \yii\helpers\Url::to(['plan-curriculo-evaluacion/index1', 'id' => $key]);
                        } 
                    }
                ],
            /** FIN BOTONES DE ACCION * */
            ],
        ]);
        ?>
    </div>
</div>