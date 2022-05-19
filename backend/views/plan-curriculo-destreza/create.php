<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoDestreza */

$this->title = 'Create Plan Curriculo Destreza';
$this->params['breadcrumbs'][] = ['label' => 'Plan Curriculo Destrezas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-curriculo-destreza-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
    ]) ?>

</div>
