<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CurCurriculoKidsCriterioEvaluacion */

$this->title = 'Create Cur Curriculo Kids Criterio Evaluacion';
$this->params['breadcrumbs'][] = ['label' => 'Cur Curriculo Kids Criterio Evaluacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cur-curriculo-kids-criterio-evaluacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
