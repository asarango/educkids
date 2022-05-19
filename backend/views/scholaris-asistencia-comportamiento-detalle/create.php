<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoDetalle */

$this->title = 'Nuevo Detalle Comportamiento ';
$this->params['breadcrumbs'][] = ['label' => 'Códigos de Comportamiento', 'url' => ['scholaris-asistencia-comportamiento/updatedetalle','id' => $id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-asistencia-comportamiento-detalle-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
    ]) ?>

</div>
