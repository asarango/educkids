<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OpPsychologicalAttentionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Atención Psicológica';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-psychological-attention-index" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Atención', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//            'id',
//            'attended_faculty_id',
//            'create_date',
//            'detail:ntext',
//            'departament_id',
            //'course_id',
            //'subject',
            //'employee_id',
            //'external_derivation_id',
            //'student_id',
            //'violence_modality_id',
            //'attention_type_id',
            //'agreements:ntext',
            //'violence_type_id',
            //'violence_reason_id',
            //'attended_student_id',
            //'state',
            //'attended_parent_id',
            //'write_date',
            //'date',
            //'write_uid',
            //'special_need_id',
            //'substance_use_id',
            //'parallel_id',
            //'special_attention:boolean',
            //'persona_lidera',
            /** INICIO BOTONES DE ACCION * */
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                'width' => '150px',
                'vAlign' => 'middle',
                'template' => '{view}  {update}  {delete}  {print}',
                'buttons' => [
                    'print' => function($url, $model) {
                        return Html::a('<i class="fa fa-file-pdf-o">', $url, [
                                    'title' => 'Imprimir', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                        ]);
                    },
                ],
                'urlCreator' => function($action, $model, $key) {

                    if ($action === 'view') {
                        return \yii\helpers\Url::to(['view', 'id' => $key]);
                    } else if ($action === 'update' && $model->state != 'open') {
                        return \yii\helpers\Url::to(['update', 'id' => $key]);
                    } else if ($action === 'delete' && $model->state != 'open') {
                        return \yii\helpers\Url::to(['delete', 'id' => $key]);
                    } else if ($action === 'print') {
                        return \yii\helpers\Url::to(['print-one', 'id' => $key]);
                    }
                }
            ],
        /** FIN BOTONES DE ACCION * */
            'id',
            [
                'attribute' => 'date',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->date;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($students, 'date', 'date'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione Fecha...'],
                'format' => 'raw',
            ],
//            'create_uid',
            [
                'attribute' => 'create_uid',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->createU->partner->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($usersAttention, 'id', 'usuario'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione atendido por...'],
                'format' => 'raw',
            ],
                        
            [
                'attribute' => 'employee_id',
                'label' => 'Empleado',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->employee->resource->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($employees, 'employee_id', 'empleado'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione Empleado...'],
                'format' => 'raw',
            ],
            [
                'attribute' => 'student_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->student->last_name . ' ' . $model->student->first_name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($students, 'id', 'student_name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione Estudiante...'],
                'format' => 'raw',
            ],
            'course.name',
            'parallel.name',
            'subject',                        
            'state',
            
        ],
    ]);
    ?>
</div>
