<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaCurso */

$this->title = $model->malla_id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Mec V2 Malla Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-mec-v2-malla-curso-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'malla_id' => $model->malla_id, 'curso_id' => $model->curso_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'malla_id' => $model->malla_id, 'curso_id' => $model->curso_id], [
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
            'malla_id',
            'curso_id',
        ],
    ]) ?>

</div>
