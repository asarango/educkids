<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisReporteNovedadesComportamiento */

$this->title = 'Create Scholaris Reporte Novedades Comportamiento';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Reporte Novedades Comportamientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-reporte-novedades-comportamiento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
