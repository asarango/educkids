<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPud */

$this->title = 'Create Scholaris Plan Pud';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Plan Puds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pud-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelClase' => $modelClase
    ]) ?>

</div>
