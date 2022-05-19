<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanificacionDesagregacionCabecera */

$this->title = 'Create Planificacion Desagregacion Cabecera';
$this->params['breadcrumbs'][] = ['label' => 'Planificacion Desagregacion Cabeceras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planificacion-desagregacion-cabecera-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
