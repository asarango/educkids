<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsPca */

$this->title = 'Create Kids Pca';
$this->params['breadcrumbs'][] = ['label' => 'Kids Pcas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kids-pca-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
