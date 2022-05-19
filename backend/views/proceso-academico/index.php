<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cursos y Paralelos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="procesos-academicos-index">

    <div class="container">
    

    <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
//            'id',
//            'course_id',
                    [
                        'attribute' => 'course_id',
                        'vAlign' => 'top',
                        'value' => function($model, $key, $index, $widget) {
                            return $model->course->name;
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ArrayHelper::map($modelCursos, 'id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                        'format' => 'raw',
                    ],
                    'name',
                    /** INICIO BOTONES DE ACCION * */
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'dropdown' => false,
                        'width' => '150px',
                        'vAlign' => 'middle',
                        'template' => '{opciones}',
                        'buttons' => [
                            'opciones' => function($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-list"></span>', $url, [
                                            'title' => 'Opciones', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                ]);
                            }
                        ],
                        'urlCreator' => function($action, $model, $key) {
                            if ($action === 'opciones') {
                                return \yii\helpers\Url::to(['opciones', 'id' => $key]);
                            }
                        }
                    ],
                /** FIN BOTONES DE ACCION * */
                ],
            ]);
            ?>
</div>
</div>