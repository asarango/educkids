<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Materia */

$this->title = 'Creación de materis del Área: '. $modelArea->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Malla', 'url' => ['scholaris-mec-v2-area/index1','id' => $modelArea->malla_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-materia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelArea' => $modelArea
    ]) ?>

</div>
