<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanNivelSub */

$this->title = 'Create Plan Nivel Sub';
$this->params['breadcrumbs'][] = ['label' => 'Plan Nivel Subs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-nivel-sub-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id
    ]) ?>

</div>
