<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ScholarisArchivosprofesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Archivosprofesors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-archivosprofesor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Archivosprofesor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idactividad',
            'archivo',
            'fechasubido',
            'nombre_archivo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
