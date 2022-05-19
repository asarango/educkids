<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClase */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Clases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-clase-view">

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
            'idmateria',
            'idprofesor',
            'idcurso',
            'paralelo_id',
            'peso',
            'periodo_scholaris',
            'promedia',
            'asignado_horario',
            'tipo_usu_bloque',
            'todos_alumnos',
            'malla_materia',
        ],
    ]) ?>

</div>
