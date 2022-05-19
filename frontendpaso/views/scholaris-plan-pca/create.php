<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPca */

$this->title = 'Creación de un PCA';
$this->params['breadcrumbs'][] = ['label' => 'Planificaciones PCA', 'url' => ['index1']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pca-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'periodoId' => $periodoId
    ]) ?>

</div>
