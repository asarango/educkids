<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaMateria */

$this->title = 'Creando Malla Materia del Área: ' . $modelArea->asignatura->nombre
        . ' de ' . $modelArea->malla->nombre
;
$this->params['breadcrumbs'][] = ['label' => 'Malla Area', 'url' => ['scholaris-mec-v2-malla-area/index1', 'id' => $modelArea->malla_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-malla-materia-create">

    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
            'modelArea' => $modelArea
        ])
        ?>

    </div>
</div>
