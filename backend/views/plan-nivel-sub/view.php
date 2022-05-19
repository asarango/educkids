<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanNivelSub */

$this->title = $model->curso_template_id;
$this->params['breadcrumbs'][] = ['label' => 'Plan Nivel Subs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-nivel-sub-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'curso_template_id' => $model->curso_template_id, 'nivel_id' => $model->nivel_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'curso_template_id' => $model->curso_template_id, 'nivel_id' => $model->nivel_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'curso_template_id',
            'nivel_id',
        ],
    ]) ?>

</div>
