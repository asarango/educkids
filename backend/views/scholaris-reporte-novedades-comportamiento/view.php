<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisReporteNovedadesComportamiento */

$this->title = $model->novedad_id;
$this->params['breadcrumbs'][] = ['label' => 'Novedades de Comportamientos', 'url' => ['index1']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-reporte-novedades-comportamiento-view" style="padding-left: 40px; padding-right: 40px;">

    <h1><?= Html::encode($model->estudiante) ?></h1>

    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'novedad_id',
            'bloque',
            'semana',
            'fecha',
            'hora',
            'materia',
            'estudiante',
            'curso',
            'paralelo',
            'codigo',
            'falta',
            'observacion:ntext',
            'justificacion:ntext',
            'usuario',
        ],
    ]) ?>

</div>
