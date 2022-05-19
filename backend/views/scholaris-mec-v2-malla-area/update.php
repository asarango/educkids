<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaArea */

$this->title = 'Editando Malla Area MEC: ' . $model->asignatura->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Malla Areas', 'url' => ['index1', 'id'=>$modelMalla->id]];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-mec-v2-malla-area-update">

    <div class="container">

        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
            'modelMalla' => $modelMalla
        ])
        ?>

    </div>
</div>
