<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisAsistenciaComportamientoFecuenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Frecuencias de Comportamiento: '.$modelDetalle->codigo.' '.$modelDetalle->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Comportamientos', 'url' => ['scholaris-asistencia-comportamiento/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-asistencia-comportamiento-fecuencia-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nueva Frecuencia de Comportamiento ', ['create','id' => $modelDetalle->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'detalle.codigo',
            'detalle.nombre',
            'fecuencia',
            'puntos',
            'accion',
            //'observacion:ntext',
            //'alerta:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
