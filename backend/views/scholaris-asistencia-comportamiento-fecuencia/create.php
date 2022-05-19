<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoFecuencia */

$this->title = 'Create Scholaris Asistencia Comportamiento Fecuencia';
$this->params['breadcrumbs'][] = ['label' => 'Fecuencias Comportamiento', 'url' => ['index1', 'id' => $detalleId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-asistencia-comportamiento-fecuencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'detalleId' => $detalleId
    ]) ?>

</div>
