<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'id',
    ],
//    [
//        'class'=>'\kartik\grid\DataColumn',
//        'attribute'=>'clase_id',
//    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_material',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'quimestre_codigo',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'titulo',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_inicio',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_entrega',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre_archivo',
    ],
    
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'creado_por',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'creado_fecha',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'actualizado_por',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'actualizado_fecha',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
//        'template' => '{actualizavideo}{view}{update}{eliminar}{descargar}{recibir}',
        'template' => '{actualizavideo}{actualizar}{eliminar}{descargar}{recibir}',
        'buttons' => [
            'descargar' => function($url, $model, $key) {
//                return Html::a('Descargar', ['descargar', 'id' => $model->id], ['target' => '_blank']);
                return Html::a('| Descargar |', ['descargar','id' => $key], ['title' => 'Descargar', 'target' => '_blank', 'data' => ['pjax' => 0]] );
            },
            'recibir' => function($url, $model, $key) {
                return Html::a(' Recibir |', ['recibir','id' => $key, 'quimestre' => $model->quimestre_codigo]
                                           , ['title' => 'Recibir', 'target' => '_blank', 'data' => ['pjax' => 0]] );
            },
            'actualizavideo' => function($url, $model, $key) {
                return Html::a(' Videoconf |', ['videoconferencia','id' => $key]
                                           , ['title' => 'Editar_VIdeoconferencia', 'target' => '', 'data' => ['pjax' => 0]] );
            },
            'eliminar' => function($url, $model, $key) {
                return Html::a(' Eliminar ', ['eliminar','id' => $key]
                                           , ['title' => 'Eliminar', 'target' => '', 'data' => ['pjax' => 0]] );
            },
            'actualizar' => function($url, $model, $key) {
                return Html::a(' Actualizar | ', ['update','id' => $key]
                                           , ['title' => 'Actualizar', 'target' => '', 'data' => ['pjax' => 0]] );
            },
        ],
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
//        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
//        'deleteOptions' => ['role' => '', 'title' => 'Delete',
//            'data-confirm' => false, 'data-method' => false, // for overide yii data api
//            'data-request-method' => 'post',
//            'data-toggle' => 'tooltip',
//            'data-confirm-title' => 'Alerta!!!',
//            'data-confirm-message' => '¿Está seguro de eliminar este archivo?'],
    ],
];
