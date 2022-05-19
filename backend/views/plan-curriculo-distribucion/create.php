<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDistribucion */

$this->title = 'Create Plan Curriculo Distribucion';
$this->params['breadcrumbs'][] = ['label' => 'Plan Curriculo Distribucions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-curriculo-distribucion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
