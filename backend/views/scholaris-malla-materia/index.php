<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMallaMateriaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Malla Materias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-malla-materia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Malla Materia', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'malla_area_id',
            'materia_id',
            'se_imprime:boolean',
            'promedia:boolean',
            //'total_porcentaje',
            //'es_cuantitativa:boolean',
            //'tipo',
            //'orden',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
