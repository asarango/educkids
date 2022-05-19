<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisAsistenciaComportamientoDetalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Asistencia Comportamiento Detalles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-asistencia-comportamiento-detalle-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Asistencia Comportamiento Detalle', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'comportamiento_id',
            'codigo',
            'nombre',
            'tipo',
            //'cantidad_descuento',
            //'punto_descuento',
            //'total_x_unidad',
            //'code_fj',
            //'activo:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
