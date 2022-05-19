<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaArea */

$this->title = 'Create Scholaris Malla Area';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Malla Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-malla-area-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelAreas' => $modelAreas,
        'id' => $id
    ]) ?>

</div>
