<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisBloqueSemanasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Semanas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-bloque-semanas-index" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Semana', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            'bloque_id',
           
            [
                'attribute' => 'bloque.tipo_uso',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    $mod = backend\models\ScholarisBloqueComparte::find()->where(['valor' => $model->bloque->tipo_uso])->one();
                    return $mod->nombre;
                },
            ],
            [
                'attribute' => 'bloque_id',
                'vAlign' => 'top',
                'value' => function($model, $key, $index, $widget) {
                    return $model->bloque->name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map($modelBloques, 'id', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                'format' => 'raw',
            ],
            'semana_numero',
            'nombre_semana',
            'fecha_inicio',
            'fecha_finaliza',
            //'estado',
            'fecha_limite_inicia',
            'fecha_limite_tope',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
