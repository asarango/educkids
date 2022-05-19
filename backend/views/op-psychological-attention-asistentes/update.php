<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OpPsychologicalAttentionAsistentes */

$this->title = 'Actualizando a asistente: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Atención', 'url' => ['/op-psychological-attention/update', 'id' => $model->psychological_attention_id]];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="op-psychological-attention-asistentes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'userId' => $userId
    ]) ?>

</div>
