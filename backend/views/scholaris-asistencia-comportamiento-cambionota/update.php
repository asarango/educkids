<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoCambionota */

$this->title = 'Actualizando motivo de cambio de notas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Motivo de cambio de notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-asistencia-comportamiento-cambionota-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>

    </div>
</div>
