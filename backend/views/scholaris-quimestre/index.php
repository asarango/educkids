<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisQuimestreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Quimestres';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-quimestre-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Quimestre', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'codigo',
            'nombre',
            'tipo_quimestre',
            'orden',
            'estado',
            'abreviatura',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
