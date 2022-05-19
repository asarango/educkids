<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoEvaluacion */

$this->title = 'Create Plan Curriculo Evaluacion';
$this->params['breadcrumbs'][] = ['label' => 'Plan Curriculo Evaluacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-curriculo-evaluacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
    ]) ?>

</div>
