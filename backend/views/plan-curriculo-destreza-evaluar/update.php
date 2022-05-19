<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDestrezaEvaluar */

$this->title = 'Update Plan Curriculo Destreza Evaluar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Plan Curriculo Destreza Evaluars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="plan-curriculo-destreza-evaluar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
