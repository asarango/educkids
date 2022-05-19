<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoDetalle */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Asistencia Comportamiento Detalles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-asistencia-comportamiento-detalle-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'comportamiento_id',
            'codigo',
            'nombre',
            'tipo',
            'cantidad_descuento',
            'punto_descuento',
            'total_x_unidad',
            'code_fj',
            'activo:boolean',
        ],
    ]) ?>

</div>
