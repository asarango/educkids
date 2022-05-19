<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanSemanalDestrezas */

$this->title = $model->curso_id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Plan Semanal Destrezas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-plan-semanal-destrezas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'curso_id' => $model->curso_id, 'faculty_id' => $model->faculty_id, 'semana_id' => $model->semana_id, 'comparte_valor' => $model->comparte_valor], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'curso_id' => $model->curso_id, 'faculty_id' => $model->faculty_id, 'semana_id' => $model->semana_id, 'comparte_valor' => $model->comparte_valor], [
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
            'curso_id',
            'faculty_id',
            'semana_id',
            'comparte_valor',
            'concepto:ntext',
            'contexto:ntext',
            'pregunta_indagacion:ntext',
            'enfoque:ntext',
            'creado_por',
            'creado_fecha',
            'actualizado_por',
            'actualizado_fecha',
        ],
    ]) ?>

</div>
