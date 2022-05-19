<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisClaseLibretaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Clase Libretas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-clase-libreta-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Create Scholaris Clase Libreta', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'grupo_id',
            'p1',
            'p2',
            'p3',
            'pr1',
            'pr180',
            'ex1',
            'ex120',
            'q1',
            'p4',
            'p5',
            'p6',
            'pr2',
            'pr280',
            'ex2',
            'ex220',
            'q2',
            'final_ano_normal',
            'mejora_q1',
            'mejora_q2',
            'final_con_mejora',
            'supletorio',
            'remedial',
            'gracia',
            'final_total',
            'estado',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
