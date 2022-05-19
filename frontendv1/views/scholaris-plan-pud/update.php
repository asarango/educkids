<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPud */

$this->title = 'Update Scholaris Plan Pud: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Listados de Planificaciones Puds', 'url' => ['index1','id'=>$model->clase_id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-plan-pud-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelClase' => $modelClase
    ]) ?>

</div>
