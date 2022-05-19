<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisHorariov2DetalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'DETALLE DE HORARIO: ' . $modelHorario->descripcion;
$this->params['breadcrumbs'][] = $this->title;

$listaDias = \backend\models\ScholarisHorariov2Dia::find()->orderBy('numero')->all();

$listaHoras = backend\models\ScholarisHorariov2Hora::find()
        ->select(['id', "concat(sigla,' ',desde,' ',hasta) as sigla"])
        ->orderBy('numero')
        ->all();
?>
<div class="scholaris-horariov2-detalle-index" style="padding-left: 40px; padding-right: 40px">

    <p>
        <?= Html::a('Crear detalle de Horario: ', ['create', 'cabecera' => $modelHorario->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//            'id',
            //'cabecera_id',
            [
                'attribute' => 'dia_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->dia->nombre;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($listaDias, 'id', 'nombre'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                'format' => 'raw',
            ],
//                        [
//                'attribute' => 'hora_id',
//                'vAlign' => 'top',
//                'value' => function($model, $key, $index, $widget) {
//                    return $model->hora->sigla;
//                },
//                'filterType' => GridView::FILTER_SELECT2,
//                'filter' => ArrayHelper::map($listaHoras, 'id', 'sigla  '),
//                'filterWidgetOptions' => [
//                    'pluginOptions' => ['allowClear' => true],
//                ],
//                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
//                'format' => 'raw',
//            ],
                        
                        [
                            'attribute' => 'hora_id',
                            'value' => function($model, $key, $index, $widget){
                                return $model->hora->sigla.' // '.$model->hora->desde.' - '.$model->hora->hasta;
                            }
                        ],
//            'hora_id',
                        
//            'dia_id',
            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
