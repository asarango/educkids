<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Libretas y Sábanas';
$this->params['breadcrumbs'][] = $this->title;


$listaCursos = backend\models\OpCourse::find()
                ->innerJoin("scholaris_clase", "op_course.id = scholaris_clase.idcurso")
                ->where([
                    "scholaris_clase.periodo_scholaris" => $modelPeriodo->codigo,
                    "op_course.x_institute" => $institutoId
                ])->all();


?>
<div class="informes-aprendizaje-index">

    <h3><?= Html::encode($this->title) ?></h3>
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
                    'template' => '{informes}',
                    'buttons' => [
                        'informes' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-folder-open"></span>', $url, [
                                        'title' => 'INFORMES', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'informes') {
                            return \yii\helpers\Url::to(['informes', 'id' => $key]);                        
                        }
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
