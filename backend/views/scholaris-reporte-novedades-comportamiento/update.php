<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisReporteNovedadesComportamiento */

$this->title = 'Update Scholaris Reporte Novedades Comportamiento: ' . $model->novedad_id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Reporte Novedades Comportamientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->novedad_id, 'url' => ['view', 'id' => $model->novedad_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-reporte-novedades-comportamiento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
