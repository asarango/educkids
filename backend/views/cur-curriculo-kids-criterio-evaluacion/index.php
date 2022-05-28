<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CurCurriculoKidsCriterioEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cur Curriculo Kids Criterio Evaluacions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cur-curriculo-kids-criterio-evaluacion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cur Curriculo Kids Criterio Evaluacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'codigo',
            'nombre:ntext',
            'estado:boolean',

            //['class' => 'yii\grid\ActionColumn'],
            /** INICIO BOTONES DE ACCION * */
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url, $model, $key){
                        return Html::a('View',['view', 'id' => $model->id]);
                    }
                ],
            ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
