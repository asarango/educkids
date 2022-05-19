<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMensaje1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Mensaje1s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mensaje1-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Mensaje1', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'mensaje:ntext',
            'estado:boolean',
            'autor_usuario',
            'para_usuario',
            //'fecha',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
