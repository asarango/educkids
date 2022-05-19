<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaMateria */

$this->title = 'Editando Malla Materia: ' . $model->asignatura->nombre
        .' / ' . $model->area->malla->nombre
        ;
$this->params['breadcrumbs'][] = ['label' => 'Malla MEC', 'url' => ['scholaris-mec-v2-malla-area/index1', 'id' => $model->area->malla_id]];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-mec-v2-malla-materia-update">

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
