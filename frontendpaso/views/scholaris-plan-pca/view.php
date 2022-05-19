<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPca */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Plan Pcas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-plan-pca-view">

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
            'malla_materia_curriculo_id',
            'malla_materia_institucion_id',
            'curso_curriculo_id',
            'curso_institucion_id',
            'docentes:ntext',
            'paralelos',
            'nivel_educativo',
            'carga_horaria_semanal',
            'semanas_trabajo',
            'aprendizaje_imprevistos',
            'total_semanas_clase',
            'total_periodos',
            'revisado_por',
            'aprobado_por',
            'creado_por',
            'creado_fecha',
            'actualizado_por',
            'actualizado_fecha',
            'estado',
        ],
    ]) ?>

</div>
