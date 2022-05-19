<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueActividad */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Parciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-bloque-actividad-view">

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
            'name',
            'create_uid',
            'create_date',
            'write_uid',
            'write_date',
            'quimestre',
            'tipo',
            'desde',
            'hasta',
            'orden',
            'scholaris_periodo_codigo',
            'tipo_bloque',
            'dias_laborados',
            'estado',
            'abreviatura',
            'tipo_uso',
            'bloque_inicia',
            'bloque_finaliza',
        ],
    ]) ?>

</div>
