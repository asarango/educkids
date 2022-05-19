<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisFirmasReportesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nombres de firmas para Reportes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-firmas-reportes-index" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Nuevo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'template_id',
            'template.name',
            'codigo_reporte',
            'principal_cargo',
            'principal_nombre',
            'secretaria_cargo',
            'secretaria_nombre',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
