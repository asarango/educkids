<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OpPsychologicalAttentionAsistentes */

$this->title = 'Agregando Asistente';
$this->params['breadcrumbs'][] = ['label' => 'Volver a atención', 'url' => ['op-psychological-attention/update', 'id' => $attentionId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-psychological-attention-asistentes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'attentionId' => $attentionId,
        'userId' => $userId
    ]) ?>

</div>
