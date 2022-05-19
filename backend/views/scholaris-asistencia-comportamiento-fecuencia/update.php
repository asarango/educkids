<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoFecuencia */

$this->title = 'Update Scholaris Asistencia Comportamiento Fecuencia: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fecuencias de Comportamiento ', 'url' => ['index1', 'id' => $model->detalle_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-asistencia-comportamiento-fecuencia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
