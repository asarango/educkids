<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionDesagregacionCabecera */

$this->title = 'Update Planificacion Desagregacion Cabecera: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Planificacion Desagregacion Cabeceras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="planificacion-desagregacion-cabecera-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
