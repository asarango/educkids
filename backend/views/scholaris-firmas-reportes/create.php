<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFirmasReportes */

$this->title = 'Creando nuevo nombre de firma';
$this->params['breadcrumbs'][] = ['label' => 'Nombres de firmas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-firmas-reportes-create" style="padding-left: 40px; padding-right: 40px;">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelTemplates' => $modelTemplates,
        'modelInstitutos' => $modelInstitutos
    ]) ?>

</div>
