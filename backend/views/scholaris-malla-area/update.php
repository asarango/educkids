<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaArea */

$this->title = 'Update Scholaris Malla Area: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Malla Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-malla-area-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
        'modelAreas' => $modelAreas,
    ]) ?>

</div>
