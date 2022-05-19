<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPud */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Plan Puds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-plan-pud-view">

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
            'bloque_id',
            'titulo',
            'fecha_inicio',
            'fecha_finalizacion',
            'objetivo_unidad:ntext',
            'ac_necesidad_atendida:ntext',
            'ac_adaptacion_aplicada:ntext',
            'ac_responsable_dece',
            'bibliografia:ntext',
            'observaciones:ntext',
            'quien_revisa_id',
            'quien_aprueba_id',
            'estado',
            'creado_por',
            'creado_fecha',
            'actualizado_por',
            'actualizado_fecha',
        ],
    ]) ?>

</div>
