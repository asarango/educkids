<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanPduEjes */

$this->title = 'Create Plan Pdu Ejes';
$this->params['breadcrumbs'][] = ['label' => 'Plan Pdu Ejes', 'url' => ['index1','id' => $id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-pdu-ejes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id
    ]) ?>

</div>
