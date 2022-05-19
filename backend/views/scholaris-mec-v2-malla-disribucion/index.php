<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMecV2MallaDisribucionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Mec V2 Malla Disribucions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-malla-disribucion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Mec V2 Malla Disribucion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'materia_id',
            'tipo_homologacion',
            'codigo_materia_source',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
