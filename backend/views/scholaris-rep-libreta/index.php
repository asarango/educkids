<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\OpStudent;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisRepLibretaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Rep Libretas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-rep-libreta-index">

    <h4><?= Html::encode($this->title).' - '.$modelParalelo->course->name.' - '.$modelParalelo->name ?></h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Exportar PDF', ['pdf','paralelo' => $modelParalelo->id, 'alumno' => $alumno], ['class' => 'btn btn-danger']) ?>
        
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

//            'codigo',
//            'usuario',
//            'clase_id',
            'promedia',
            'peso',
//            'tipo_uso_bloque',
            'tipo',
            'tipo_calificacion',
            'asignatura_id',
            'asignatura',
            //'paralelo_id',
//            'alumno_id',
            [
                'attribute' => 'alumno_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->alumno->last_name.' '.$model->alumno->first_name.' '.$model->alumno->middle_name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(OpStudent::find()
                                ->select(['op_student.id',"concat(op_student.last_name,' ',op_student.first_name,' ',op_student.middle_name) as last_name"])
                                ->joinWith('op_student_inscription','op_student.id = op_student_inscription.student_id')
                                ->where(['op_student_inscription.parallel_id' => $modelParalelo->id])
                                ->orderBy('op_student.last_name')->all(), 'id', 'last_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                'format' => 'raw',
            ],
            'area_id',
            //'p1',
            //'p2',
            //'p3',
            //'pr1',
            //'ex1',
            //'pr180',
            //'ex120',
            'q1',
            //'p4',
            //'p5',
            //'p6',
            //'pr2',
            //'ex2',
            //'pr280',
            //'ex220',
            'q2',
            'nota_final',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
