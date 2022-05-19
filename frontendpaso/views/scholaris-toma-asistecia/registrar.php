<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$listaBloque = \backend\models\ScholarisBloqueActividad::find()->where(['tipo_uso' => $uso])->all();


$this->title = 'Registro de Asistencia de Estudiantes: ' . $modelParalelo->course->name . ' - ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-toma-asistecia-registrar">


    <p>
        <?= Html::a('Crear asistencia', ['create', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
//            'paralelo_id',
            'fecha',
            [
                'attribute' => 'bloque_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->bloque->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($listaBloque, 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                'format' => 'raw',
            ],
//            'bloque_id',
            'hubo_clases:boolean',
            'observacion:ntext',
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
                    'template' => '{view}{update}{detalle}{comportamiento}{imprimir}',
                    'buttons' => [
                        'detalle' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-hdd"></span>', $url, [
                                        'title' => 'Faltas_Atrasos', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
                        'comportamiento' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-bullhorn"></span>', $url, [
                                        'title' => 'Comportamiento', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
                        'imprimir' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-print"></span>', $url, [
                                        'title' => 'Imprimir_Leccionario', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'view') {
                            return \yii\helpers\Url::to(['plan-curriculo-objetivos/index1', 'id' => $key]);                        
                        } else if ($action === 'update') {
                            return \yii\helpers\Url::to(['scholaris-clase-aux/update', 'id' => $key]);
                        } else if ($action === 'detalle') {
                            return \yii\helpers\Url::to(['detalle', 'id' => $key]);
                        }else if ($action === 'comportamiento') {
                            return \yii\helpers\Url::to(['comportamiento', 'id' => $key]);
                        }else if ($action === 'imprimir') {
                            return \yii\helpers\Url::to(['reporte-leccionario/index1', 'id' => $key]);
                        }
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]);
    ?>
</div>
