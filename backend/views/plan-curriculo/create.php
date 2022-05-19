<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculo */

$this->title = 'Create Plan Curriculo';
$this->params['breadcrumbs'][] = ['label' => 'Plan Curriculos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-curriculo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
