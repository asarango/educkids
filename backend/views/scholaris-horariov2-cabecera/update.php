<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisHorariov2Cabecera */

$this->title = 'Update Scholaris Horariov2 Cabecera: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Horariov2 Cabeceras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-horariov2-cabecera-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
