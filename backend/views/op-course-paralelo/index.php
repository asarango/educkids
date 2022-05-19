<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OpCourseParaleloSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Op Course Paralelos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-course-paralelo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Op Course Paralelo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'create_uid',
            'last_date_invoice',
            'create_date',
            'name',
            //'x_capacidad',
            //'write_uid',
            //'write_date',
            //'course_id',
            //'period_id',
            //'institute_id',
            //'capacidad',
            //'aula',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
