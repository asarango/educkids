<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaMateria */

$this->title = 'Update Scholaris Malla Materia: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Malla Materias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-malla-materia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelArea' => $modelArea
    ]) ?>

</div>
