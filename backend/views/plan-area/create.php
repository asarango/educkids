<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanArea */

$this->title = 'Create Plan Area';
$this->params['breadcrumbs'][] = ['label' => 'Plan Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-area-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
