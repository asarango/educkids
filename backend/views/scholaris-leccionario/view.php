<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisLeccionario */

$this->title = $model->paralelo_id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Leccionarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-leccionario-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'paralelo_id' => $model->paralelo_id, 'fecha' => $model->fecha], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'paralelo_id' => $model->paralelo_id, 'fecha' => $model->fecha], [
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
            'paralelo_id',
            'fecha',
            'total_clases',
            'total_revisadas',
            'usuario_crea',
            'fecha_crea',
            'usuario_actualiza',
            'fecha_actualiza',
        ],
    ]) ?>

</div>
