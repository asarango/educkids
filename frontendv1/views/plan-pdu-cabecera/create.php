<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanPduCabecera */

$this->title = 'Create Plan Pdu Cabecera';
$this->params['breadcrumbs'][] = ['label' => 'Plan Pdu Cabeceras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-pdu-cabecera-create">

    <h1><?=Html::encode($this->title)?></h1>

    <?=$this->render('_form', [
    'model' => $model,
    'clase_id' => $clase_id,
    'bloque_id' => $bloque_id,
])?>

</div>
