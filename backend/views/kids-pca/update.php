<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsPca */

$this->title = 'Update Kids Pca: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kids Pcas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kids-pca-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
