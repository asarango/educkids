<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDesempeno */

$this->title = 'Create Plan Curriculo Desempeno';
$this->params['breadcrumbs'][] = ['label' => 'Plan Curriculo Desempenos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-curriculo-desempeno-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
    ]) ?>

</div>
