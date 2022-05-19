<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanNivel */

$this->title = 'Create Plan Nivel';
$this->params['breadcrumbs'][] = ['label' => 'Plan Nivels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-nivel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
