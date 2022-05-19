<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisRepLibreta */

$this->title = $model->codigo;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Rep Libretas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-rep-libreta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->codigo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->codigo], [
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
            'codigo',
            'usuario',
            'clase_id',
            'promedia',
            'tipo_uso_bloque',
            'tipo',
            'asignatura_id',
            'asignatura',
            'paralelo_id',
            'alumno_id',
            'area_id',
            'p1',
            'p2',
            'p3',
            'pr1',
            'ex1',
            'pr180',
            'ex120',
            'q1',
            'p4',
            'p5',
            'p6',
            'pr2',
            'ex2',
            'pr280',
            'ex220',
            'q2',
            'nota_final',
        ],
    ]) ?>

</div>
