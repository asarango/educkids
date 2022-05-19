<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Distribucion */

$this->title = 'Create Scholaris Mec V2 Distribucion';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Mec V2 Distribucions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-distribucion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
