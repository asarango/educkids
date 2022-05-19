<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaDisribucion */

$this->title = 'Creando una distribución de Malla: ' . $modelMateria->asignatura->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de Malla MEC',
    'url' => ['scholaris-mec-v2-malla-area/index1', 'id' => $modelMateria->area->malla_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-malla-disribucion-create">

    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
            'modelMateria' => $modelMateria
        ])
        ?>

    </div>
</div>
