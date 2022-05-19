<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTableroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Tableros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-tablero-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Tablero', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'clase_id',
            'curso',
            'paralelo',
            'apellido_profesor',
            'nombre_profesor',
            //'p1',
            //'p2',
            //'p3',
            //'ex1',
            //'p4',
            //'p5',
            //'p6',
            //'ex2',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
