<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoFecuencia */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Asistencia Comportamiento Fecuencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-asistencia-comportamiento-fecuencia-view">

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
            'detalle_id',
            'fecuencia',
            'puntos',
            'accion',
            'observacion:ntext',
            'alerta:ntext',
        ],
    ]) ?>

</div>
