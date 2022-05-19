<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanPduCabecera */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Plan Pdu Cabeceras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="plan-pdu-cabecera-view">

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
            'clase_id',
            'asignatura_curriculo_id',
            'bloque_id',
            'periodos',
            'coordinador_id',
            'vicerrector_id',
            'planificacion_titulo',
            'objetivo_por_nivel_id',
            'estado',
            'creado_por',
            'creado_fecha',
            'actualizado_por',
            'actualizado_fecha',
        ],
    ]) ?>

</div>
