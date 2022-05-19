<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionDesagregacionCriteriosEvaluacion */

$this->title = 'Create Planificacion Desagregacion Criterios Evaluacion';
$this->params['breadcrumbs'][] = ['label' => 'Planificacion Desagregacion Criterios Evaluacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planificacion-desagregacion-criterios-evaluacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
