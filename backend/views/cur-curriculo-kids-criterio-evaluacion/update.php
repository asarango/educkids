<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CurCurriculoKidsCriterioEvaluacion */

$this->title = 'Update Cur Curriculo Kids Criterio Evaluacion: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cur Curriculo Kids Criterio Evaluacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cur-curriculo-kids-criterio-evaluacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
