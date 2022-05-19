<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDestrezaEvaluar */

$this->title = 'Create Plan Curriculo Destreza Evaluar';
$this->params['breadcrumbs'][] = ['label' => 'Plan Curriculo Destreza Evaluars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-curriculo-destreza-evaluar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
    ]) ?>

</div>
