<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClaseLibreta */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Clase Libretas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-clase-libreta-view">

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
            'grupo_id',
            'p1',
            'p2',
            'p3',
            'pr1',
            'pr180',
            'ex1',
            'ex120',
            'q1',
            'p4',
            'p5',
            'p6',
            'pr2',
            'pr280',
            'ex2',
            'ex220',
            'q2',
            'final_ano_normal',
            'mejora_q1',
            'mejora_q2',
            'final_con_mejora',
            'supletorio',
            'remedial',
            'gracia',
            'final_total',
            'estado',
        ],
    ]) ?>

</div>
