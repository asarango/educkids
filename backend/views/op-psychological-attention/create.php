<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OpPsychologicalAttention */

$this->title = 'Nueva Atención';
$this->params['breadcrumbs'][] = ['label' => 'Principal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-psychological-attention-create">

    <div class="container">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>
    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
