<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoObjetivos */

$this->title = 'Create Plan Curriculo Objetivos';
$this->params['breadcrumbs'][] = ['label' => 'Plan Curriculo Objetivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-curriculo-objetivos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
    ]) ?>

</div>
