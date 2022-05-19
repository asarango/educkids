<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisInstitutoAutoridades */

$this->title = 'Update Scholaris Instituto Autoridades: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Instituto Autoridades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-instituto-autoridades-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
