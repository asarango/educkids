<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanPlanificacion */

$this->title = 'Create Plan Planificacion';
$this->params['breadcrumbs'][] = ['label' => 'Plan Planificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-planificacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model        
    ]) ?>

</div>
