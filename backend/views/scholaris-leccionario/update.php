<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisLeccionario */

$this->title = 'Update Scholaris Leccionario: ' . $model->paralelo_id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Leccionarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->paralelo_id, 'url' => ['view', 'paralelo_id' => $model->paralelo_id, 'fecha' => $model->fecha]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-leccionario-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
