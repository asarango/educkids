<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$listaMateria = backend\models\ScholarisMateria::find()
        ->select(["m.id", "concat(mal.nombre_malla,' - ',scholaris_materia.name) as name"])
        ->innerJoin("scholaris_malla_materia m", "scholaris_materia.id = m.materia_id")
        ->innerJoin("scholaris_malla_area a", "a.id = m.malla_area_id")
        ->innerJoin("scholaris_malla_curso c", "c.malla_id = a.malla_id")
        ->innerJoin("scholaris_malla mal", "mal.id = a.malla_id")
        ->innerJoin("op_course cur", "cur.id = c.curso_id")
        ->all();


/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPcaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación PCA';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pca-index">


    <p>
        <?= Html::a('Crear un PCA   ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
//            'malla_materia_curriculo_id',
//            'malla_materia_institucion_id',
//            'curso_curriculo_id',
//            'curso_institucion_id',
            [
                'attribute' => 'curso_institucion_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->cursoInstitucion->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($listCursos, 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                'format' => 'raw',
            ],
            [
                'attribute' => 'malla_materia_institucion_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->mallaMateriaInstitucion->materia->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($listaMateria, 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                'format' => 'raw',
            ],
            'paralelos',
            'docentes:ntext',
            //'nivel_educativo',
            //'carga_horaria_semanal',
            //'semanas_trabajo',
            //'aprendizaje_imprevistos',
            //'total_semanas_clase',
            //'total_periodos',
            //'revisado_por',
            //'aprobado_por',
            //'creado_por',
            //'creado_fecha',
            //'actualizado_por',
            //'actualizado_fecha',
            'estado',
            /** INICIO BOTONES DE ACCION * */
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'width' => '150px',
                    'vAlign' => 'middle',
                    'template' => '{view}{update}{detalle}',
                    'buttons' => [
                        'detalle' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-road"></span>', $url, [
                                        'title' => 'DETALLE', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
//                        'destreza' => function($url, $model) {
//                            return Html::a('<span class="glyphicon glyphicon-tasks"></span>', $url, [
//                                        'title' => 'Destrezas', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
//                            ]);
//                        },'evaluacion' => function($url, $model) {
//                            return Html::a('<span class="glyphicon glyphicon-ok-circle"></span>', $url, [
//                                        'title' => 'Evaluaciones', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
//                            ]);
//                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'view') {
                            return \yii\helpers\Url::to(['plan-curriculo-objetivos/index1', 'id' => $key]);                        
                        } else if ($action === 'update') {
                            return \yii\helpers\Url::to(['scholaris-clase-aux/update', 'id' => $key]);
                        } else if ($action === 'detalle') {
                            return \yii\helpers\Url::to(['detalle', 'id' => $key]);
                        }  
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]);
    ?>
</div>
