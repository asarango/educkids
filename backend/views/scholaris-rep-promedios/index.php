<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\OpStudent;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FacturaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promedios de mayor a menor';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="scholaris-rep-promedios-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php // echo $this->render('_search', ['model' => $searchModel]);   ?>

    <div class="container">
    <p>
    <?= Html::a('Exportar PDF', ['pdf','paralelo' => $paralelo, 'usuario' => $usuario, 'bloque' => $bloque], ['class' => 'btn btn-danger']) ?>
    <?= Html::a('Exportar Excel', ['excel','paralelo' => $paralelo, 'usuario' => $usuario, 'bloque' => $bloque], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'bootstrap' => true,
        'bordered' => true,
        'showPageSummary' => FALSE,
        'pageSummaryRowOptions' => ['class' => 'kv-page-summary info'],
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => '50'],
        'pjax' => false,
        'striped' => true,
        'hover' => true,
        'responsive' => true,
        'panel' => ['type' => 'primary', 'heading' => 'Listado de: '.$this->title],
        'rowOptions' => ['style' => 'font-size:12px'],
        'footerRowOptions' => ['style' => 'font-size:12px'],
        'captionOptions' => ['style' => 'font-size:10px'],
        'headerRowOptions' => ['style' => 'font-size:12px'],
        'export' => FALSE,
       
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//            'codigo',
//            'paralelo_id',
//            'alumno_id',
            [
                'attribute' => 'alumno_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->alumno->last_name . ' ' . $model->alumno->first_name . ' ' . $model->alumno->middle_name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(OpStudent::find()
                                ->select([
                                    "op_student.id",
                                    "concat(op_student.last_name, op_student.first_name, op_student.middle_name) as last_name"
                                ])
                                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                                ->where(["op_student_inscription.parallel_id" => $paralelo])
                                ->all(), 'id', 'last_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                'format' => 'raw',
            ],
            'nota_promedio',
//            'nota_comportamiento',
            //'usuario',
//            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
</div>