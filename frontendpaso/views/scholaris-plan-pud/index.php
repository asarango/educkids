<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$usuario = Yii::$app->user->identity->usuario;
$periodoId = Yii::$app->user->identity->periodo_id;
$modelPerido = backend\models\ScholarisPeriodo::findOne($periodoId);

$modelBloque = backend\models\ScholarisBloqueActividad::find()
        ->where(['scholaris_periodo_codigo' => $modelPerido->codigo, 'tipo_uso' => $modelClase->tipo_usu_bloque])
        ->orderBy('orden')
        ->all();

$this->title = 'PUD: ' . $modelClase->curso->name . ' - ' . $modelClase->paralelo->name
        . ' / ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name
        . ' / ' . $modelClase->materia->name
;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pud-index">
    
    <h3>Lista de mis planificaciones</h3>

    <p>
        <?= Html::a('Crear plan de unidad', ['create', 'clase' => $modelClase->id], ['class' => 'btn btn-success']) ?>
        <?php echo Html::a('Copiar PUD', ['copiar', 'clase' => $modelClase->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
//            'clase_id',
//            'bloque_id',
            [
                'attribute' => 'bloque_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->bloque->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($modelBloque, 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                'format' => 'raw',
            ],
            'titulo',
            'fecha_inicio',
            //'fecha_finalizacion',
            //'objetivo_unidad:ntext',
            //'ac_necesidad_atendida:ntext',
            //'ac_adaptacion_aplicada:ntext',
            //'ac_responsable_dece',
            //'bibliografia:ntext',
            //'observaciones:ntext',
//            'quien_revisa_id',
            //'quien_aprueba_id',
            'estado',
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
                    'template' => '{view}{update}{detalle}',
                    'buttons' => [
                        'detalle' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-road"></span>', $url, [
                                        'title' => 'Detalle', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'view') {
                            return \yii\helpers\Url::to(['view', 'id' => $key]);                        
                        } else if ($action === 'update') {
                            return \yii\helpers\Url::to(['update', 'id' => $key]);
                        }else if ($action === 'detalle') {
                            return \yii\helpers\Url::to(['scholaris-plan-pud-detalle/index1', 'id' => $key]);
                        }   
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]);
    ?>
</div>
