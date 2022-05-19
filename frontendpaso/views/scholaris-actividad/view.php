<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisActividad */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Actividads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-actividad-view">

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
            'create_date',
            'write_date',
            'create_uid',
            'write_uid',
            'title',
            'descripcion',
            'archivo',
            'descripcion_archivo',
            'color',
            'inicio',
            'fin',
            'tipo_actividad_id',
            'bloque_actividad_id',
            'a_peso',
            'b_peso',
            'c_peso',
            'd_peso',
            'paralelo_id',
            'materia_id',
            'calificado',
            'tipo_calificacion',
            'tareas',
            'hora_id',
            'actividad_original',
            'semana_id',
        ],
    ]) ?>

</div>
