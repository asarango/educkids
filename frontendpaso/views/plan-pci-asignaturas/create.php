<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanPciAsignaturas */

$this->title = 'Create Plan Pci Asignaturas: '.$modelPci->curriculo->ano_incia.' - '.$modelPci->curriculo->ano_finaliza.
               ' '.$modelPci->nivel->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Plan Pci Asignaturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-pci-asignaturas-create">

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
        'modelPci' => $modelPci
    ]) ?>

</div>
