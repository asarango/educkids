<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaCurso */

$this->title = 'Update Scholaris Mec V2 Malla Curso: ' . $model->malla_id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Mec V2 Malla Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->malla_id, 'url' => ['view', 'malla_id' => $model->malla_id, 'curso_id' => $model->curso_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-mec-v2-malla-curso-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
