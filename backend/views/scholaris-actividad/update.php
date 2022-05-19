<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisActividad */

$this->title = 'Actualizando Actividad: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de Actividad', 'url' => ['actividad', 'actividad' => $model->id]];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualiza Actividad';
?>
<div class="scholaris-actividad-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'modelCalificaciones' => $modelCalificaciones,
        'horas' => $horas
    ]) ?>

</div>
