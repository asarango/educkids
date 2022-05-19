<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionDesagregacionCriteriosEvaluacion */

$this->title = 'Update Planificacion Desagregacion Criterios Evaluacion: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Planificacion Desagregacion Criterios Evaluacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="planificacion-desagregacion-criterios-evaluacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
