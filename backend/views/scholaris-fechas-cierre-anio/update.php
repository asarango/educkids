<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFechasCierreAnio */

$this->title = 'Actualizando Fechas Cierre Año: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Fechas Cierre Anios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizando';
?>
<div class="scholaris-fechas-cierre-anio-update">


    <?= $this->render('_form', [
        'model' => $model,
        'modelPeriodo' => $modelPeriodo
    ]) ?>

</div>
