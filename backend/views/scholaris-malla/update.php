<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMalla */

$this->title = 'Update Scholaris Malla: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Mallas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-malla-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelPeriodo' => $modelPeriodo,
        'modelSection' => $modelSection
    ]) ?>

</div>
