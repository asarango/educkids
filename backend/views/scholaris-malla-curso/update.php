<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaCurso */

$this->title = 'Update Scholaris Malla Curso: ' . $model->malla_id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Malla Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->malla_id, 'url' => ['view', 'malla_id' => $model->malla_id, 'curso_id' => $model->curso_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-malla-curso-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelMalla' => $modelMalla
    ]) ?>

</div>
