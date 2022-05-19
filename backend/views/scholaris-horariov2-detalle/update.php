<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisHorariov2Detalle */

$this->title = 'Actualizar detalle de horario: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Listado de detalles', 'url' => ['index1', 'id' => $model->cabecera_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editando';
?>
<div class="scholaris-horariov2-detalle-update" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
