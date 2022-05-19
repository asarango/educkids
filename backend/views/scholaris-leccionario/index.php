<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisLeccionarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Leccionario: '.$modelParalelo->course->name.' '.$modelParalelo->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-leccionario-index">

    

    <p>
        <?= Html::a('Nuevo Leccionario', ['create', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

//            'paralelo_id',
            'fecha',
            'total_clases',
            'total_revisadas',
            'usuario_crea',
            'fecha_crea',
            'usuario_actualiza',
            'fecha_actualiza',

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
                        if ($action === 'view') {
                            return \yii\helpers\Url::to(['view', 'paralelo_id' => $key['paralelo_id'],'fecha' => $key['fecha']]);
                        }else if ($action === 'update') {
                            return \yii\helpers\Url::to(['update', 'paralelo_id' => $key['paralelo_id'],'fecha' => $key['fecha']]);
                        } else if ($action === 'detalle') {
                            return \yii\helpers\Url::to(['scholaris-leccionario-detalle/index1', 'paralelo_id' => $key['paralelo_id'],'fecha' => $key['fecha']]);
                        }
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
