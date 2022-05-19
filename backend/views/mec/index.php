<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Informes de Aprendizaje y Comportamiento';
$this->params['breadcrumbs'][] = $this->title;


$listaCursos = backend\models\OpCourse::find()
                ->innerJoin("scholaris_clase", "op_course.id = scholaris_clase.idcurso")
                ->where([
                    "scholaris_clase.periodo_scholaris" => $modelPeriodo->codigo,
                    "op_course.x_institute" => $institutoId
                ])->all();


?>
<div class="informes-aprendizaje-index" style="padding-left: 40px; padding-right: 40px">

    
    <strong><h3>Reportes Mec</h3></strong>
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
//            'course_id',
            [
                    'attribute' => 'course_id',
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
            
            'name',
//            'fecha',
//            'bloque_id',
//            'hubo_clases:boolean',
            //'observacion:ntext',
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
//                    'template' => '{informes}{reporte}{comportamiento} {informes2}',
                    'template' => '{libretas}',
                    'buttons' => [
                        'informes' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-folder-open"></span>2019-2020', $url, [
                                        'title' => 'INFORMES 2019-2020', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
                                'libretas' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-book"></span>', $url, [
                                        'title' => 'INFORMES 2020-2021', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'libretas') {
                            return \yii\helpers\Url::to(['informes2', 'id' => $key]);                        
                        }
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
