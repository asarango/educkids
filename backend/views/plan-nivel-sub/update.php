<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanNivelSub */

$this->title = 'Update Plan Nivel Sub: ' . $model->curso_template_id;
$this->params['breadcrumbs'][] = ['label' => 'Plan Nivel Subs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->curso_template_id, 'url' => ['view', 'curso_template_id' => $model->curso_template_id, 'nivel_id' => $model->nivel_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="plan-nivel-sub-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
