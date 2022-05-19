<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanAreaSup */

$this->title = 'Create Plan Area Sup';
$this->params['breadcrumbs'][] = ['label' => 'Plan Area Sups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-area-sup-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
