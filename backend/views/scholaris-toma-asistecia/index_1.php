<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Toma Asistecias';
$this->params['breadcrumbs'][] = $this->title;


$listaCursos = backend\models\OpCourse::find()
                ->innerJoin("scholaris_clase", "op_course.id = scholaris_clase.idcurso")
                ->where([
                    "scholaris_clase.periodo_scholaris" => $modelPeriodo->codigo,
                    "op_course.x_institute" => $institutoId
                ])->all();


?>
<div class="scholaris-toma-asistecia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Toma Asistecia', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                    'template' => '{registrar}{reporte}',
                    'buttons' => [
                        'registrar' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-road"></span>', $url, [
                                        'title' => 'REGISTRAR', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
                        'reporte' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-book"></span>', $url, [
                                        'title' => 'REPORTE', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        }      
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'registrar') {
                            return \yii\helpers\Url::to(['registrar', 'id' => $key]);                        
                        }else{
                            return \yii\helpers\Url::to(['reporte-faltas-anual/index1', 'id' => $key]);
                        }
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
