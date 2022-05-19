<?php

use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$institutoId = Yii::$app->user->identity->instituto_defecto;
$periodoId = Yii::$app->user->identity->periodo_id;
$modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);


$listaCursos = \backend\models\OpCourse::find()
                ->innerJoin("scholaris_clase", "op_course.id = scholaris_clase.idcurso")
                ->where([
                    "scholaris_clase.periodo_scholaris" => $modelPeriodo->codigo,
                    "op_course.x_institute" => $institutoId
                ])->all();



return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    [
        'attribute' => 'curso_id',
        'vAlign' => 'top',
        'value' => function($model, $key, $index, $widget) {
            return $model->curso->name;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($listaCursos, 'id', 'name'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'curso_id',
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'imprime',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'rinde_supletorio',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_proyectos',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'esta_bloqueado',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => '', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'],
    ],
];
