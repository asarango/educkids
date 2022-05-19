<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaDisribucion */

$this->title = 'Update Scholaris Mec V2 Malla Disribucion: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Mec V2 Malla Disribucions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-mec-v2-malla-disribucion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
