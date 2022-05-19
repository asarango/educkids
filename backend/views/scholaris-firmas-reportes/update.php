<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFirmasReportes */

$this->title = 'Editando Firmas de Reportes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-firmas-reportes-update" style="padding-right: 40px; padding-left: 40px">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelTemplates' => $modelTemplates,
        'modelInstitutos' => $modelInstitutos
    ]) ?>

</div>
