<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OpCourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Op Courses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-course-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Op Course', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'create_uid',
            'code',
            'create_date',
            'name',
            //'evaluation_type',
            //'write_uid',
            //'parent_id',
            //'write_date',
            //'section_moved0',
            //'x_template_id',
            //'x_capacidad',
            //'x_institute',
            //'section_moved1',
            //'orden',
            //'abreviatura',
            //'level_id',
            //'section_moved2',
            //'section_moved3',
            //'section_moved4',
            //'section_moved5',
            //'section_moved6',
            //'section_moved7',
            //'section_moved8',
            //'section',
            //'period_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
