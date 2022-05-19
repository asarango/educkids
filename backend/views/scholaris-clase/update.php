<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClase */

$this->title = 'Update Scholaris Clase: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Clases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-clase-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelMallaMateria' => $modelMallaMateria
    ]) ?>

</div>
