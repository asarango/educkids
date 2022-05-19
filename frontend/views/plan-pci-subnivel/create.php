<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanPciSubnivel */

$this->title = 'Create Plan Pci Subnivel';
$this->params['breadcrumbs'][] = ['label' => 'Plan Pci Subnivels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-pci-subnivel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
