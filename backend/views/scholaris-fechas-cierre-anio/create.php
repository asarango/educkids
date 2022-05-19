<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFechasCierreAnio */

$this->title = 'Crear fecha de cierre de Año';
$this->params['breadcrumbs'][] = ['label' => 'Fechas Cierre Año', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-fechas-cierre-anio-create">

   

    <?= $this->render('_form', [
        'model' => $model,
        'modelPeriodo' => $modelPeriodo
    ]) ?>

</div>
