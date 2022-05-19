<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanPduValores */

$this->title = 'Create Plan Pdu Valores';
$this->params['breadcrumbs'][] = ['label' => 'Plan Pdu Valores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-pdu-valores-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id
    ]) ?>

</div>
