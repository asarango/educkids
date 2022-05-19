<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamientoCambionota */

$this->title = 'Creando motivos de cambio de notas';
$this->params['breadcrumbs'][] = ['label' => 'Motivos de cambio de notas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-asistencia-comportamiento-cambionota-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>

    </div>
</div>
