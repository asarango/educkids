<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisNotasPai */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Notas Pais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-notas-pai-view">

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
            'clase_id',
            'alumno_id',
            'alumno',
            'quimestre',
            'scholaris_periodo_codigo',
            'sumativa1_a',
            'sumativa2_a',
            'sumativa3_a',
            'nota_a',
            'sumativa1_b',
            'sumativa2_b',
            'sumativa3_b',
            'nota_b',
            'sumativa1_c',
            'sumativa2_c',
            'sumativa3_c',
            'nota_c',
            'sumativa1_d',
            'sumativa2_d',
            'sumativa3_d',
            'nota_d',
            'suma_total',
            'final_homologado',
            'creado',
            'usuario_crea',
            'actualizado',
            'usuario_modifica',
        ],
    ]) ?>

</div>
