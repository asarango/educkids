<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolOperacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rol Operacions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rol-operacion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Rol Operacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rol_id',
            'operacion_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
