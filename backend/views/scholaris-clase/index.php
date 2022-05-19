<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisMateria;
use backend\models\OpCourse;
use backend\models\OpCourseParalelo;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisClaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$listaMateria = ScholarisMateria::find()
                ->innerJoin("scholaris_clase", "scholaris_clase.idmateria = scholaris_materia.id")
                ->innerJoin("op_course", "op_course.id = scholaris_clase.idcurso")
                ->where([
                    "scholaris_clase.periodo_scholaris" => $modelPeriodo->codigo,
                    "op_course.x_institute" => $institutoId
                ])->all();

$listaCursos = OpCourse::find()
                ->innerJoin("scholaris_clase", "op_course.id = scholaris_clase.idcurso")
                ->where([
                    "scholaris_clase.periodo_scholaris" => $modelPeriodo->codigo,
                    "op_course.x_institute" => $institutoId
                ])->all();

$listaParalelos = OpCourseParalelo::find()
                ->innerJoin("scholaris_clase", "op_course_paralelo.id = scholaris_clase.paralelo_id")
                ->where([
                    "scholaris_clase.periodo_scholaris" => $modelPeriodo->codigo,
                    "op_course_paralelo.institute_id" => $institutoId
                ])->all();

$listaProfesores = backend\models\OpFaculty::find()
        ->select(["id","concat(last_name,' ',x_first_name) as last_name"])
        ->all();



$this->title = 'Clase perido: ' . $modelPeriodo->nombre;
$this->params['breadcrumbs'][] = $this->title;

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
<div class="scholaris-clase-index" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>
<?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
    <?= Html::a('Crear Clase', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'bootstrap' => true,
            'bordered' => true,
            'showPageSummary' => true,
            'pageSummaryRowOptions' => ['class' => 'kv-page-summary info'],
            'floatHeader' => false,
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
                
                /** INICIO BOTONES DE ACCION * */
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'width' => '150px',
                    'vAlign' => 'middle',
                    'template' => '{view}{update}',
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
                            return \yii\helpers\Url::to(['plan-curriculo-objetivos/index1', 'id' => $key]);                        
                        } else if ($action === 'update') {
                            return \yii\helpers\Url::to(['scholaris-clase-aux/update', 'id' => $key]);
                        }  
                    }
                ],
            /** FIN BOTONES DE ACCION * */
                
                
                'id',
                'idmateria',
                [
                    'attribute' => 'idmateria',
                    'vAlign' => 'top',
                    'value' => function($model, $key, $index, $widget) {
                        return $model->materia->name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map($listaMateria, 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                    'format' => 'raw',
                ],
//                'idprofesor',
                [
                    'attribute' => 'idprofesor',
                    'vAlign' => 'top',
                    'value' => function($model, $key, $index, $widget) {
                        return $model->profesor->last_name.' '.$model->profesor->x_first_name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map($listaProfesores, 'id', 'last_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                    'format' => 'raw',
                ],
                'idcurso',
                [
                    'attribute' => 'idcurso',
                    'vAlign' => 'top',
                    'value' => function($model, $key, $index, $widget) {
                        return $model->course->name;
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map($listaCursos, 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                    'format' => 'raw',
                ],
                'paralelo_id',
                'paralelo.name',
                'materia_curriculo_codigo',
//                        [
//                'attribute' => 'paralelo_id',
//                'vAlign' => 'top',
//                'value' => function($model, $key, $index, $widget) {
//                    return $model->paralelo->name;
//                },
//                'filterType' => GridView::FILTER_SELECT2,
//                'filter' => ArrayHelper::map($listaParalelos, 'id', 'name'),
//                'filterWidgetOptions' => [
//                    'pluginOptions' => ['allowClear' => true],
//                ],
//                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
//                'format' => 'raw',
//            ],
                'peso',
                'periodo_scholaris',
                'promedia',
                'asignado_horario',
                'tipo_usu_bloque',
                'todos_alumnos',
                //'malla_materia',
                'mallaMateria.materia_id',
                'mallaMateria.materia.name',
                
            ],
        ]);
        ?>
</div>
