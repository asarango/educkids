<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPudDetalle */

$this->title = 'Create Scholaris Plan Pud Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Plan Pud Detalles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pud-detalle-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
