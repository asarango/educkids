<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisHorariov2Detalle */

$this->title = 'Crear Detalle de Horario: '.$modelCabecera->descripcion;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de Horario', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-horariov2-detalle-create">

    

    <?= $this->render('_form', [
        'model' => $model,
        'modelCabecera' => $modelCabecera
    ]) ?>

</div>
