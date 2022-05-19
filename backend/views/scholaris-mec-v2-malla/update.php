<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Malla */

$this->title = 'Actualizar Malla MEC: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Actualizando Malla MEC', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-mec-v2-malla-update">

    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
            'modelPeriodo' => $modelPeriodo
        ])
        ?>

    </div>
</div>
