<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\KidsPca */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kids Pcas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="kids-pca-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'ism_area_materia_id',
            'carga_horaria_semanal',
            'numero_semanas_trabajo',
            'imprevistos',
            'objetivos:ntext',
            'observaciones:ntext',
            'bibliografia:ntext',
            'estado',
            'created_at',
            'created',
            'updated_at',
            'updated',
        ],
    ]) ?>

</div>
