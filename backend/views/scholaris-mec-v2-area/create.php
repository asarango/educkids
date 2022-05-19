<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Area */

$this->title = 'Crear Area MEC: '.$modelMalla->nombre;
$this->params['breadcrumbs'][] = ['label' => 'AREAS MEC', 'url' => ['index1', 'id' => $modelMalla->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-area-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelMalla' => $modelMalla
    ]) ?>

</div>
