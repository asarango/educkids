<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoDetalle */

$this->title = 'Actualizando Detalle de Comportamiento : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Códigos de Comportamiento', 
                                    'url' => ['scholaris-asistencia-comportamiento/updatedetalle','id' => $model->comportamiento_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-asistencia-comportamiento-detalle-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
