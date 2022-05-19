<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMalla */

$this->title = 'Create Scholaris Malla';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Mallas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-malla-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelPeriodo' => $modelPeriodo,
        'modelSection' => $modelSection
    ]) ?>

</div>
