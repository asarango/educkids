<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaMateria */

$this->title;
$this->params['breadcrumbs'][] = ['label' => 'Malla MEC', 'url' => ['scholaris-mec-v2-malla-area/index1', 'id' => $model->area->malla_id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-mec-v2-malla-materia-view">

    <div class="container">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?=
            Html::a('Eliminar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Está seguro de elimnar esta materia?... Esto eliminrá la configuración que corresponde a esta asignatura!!!',
                    'method' => 'post',
                ],
            ])
            ?>
        </p>

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'codigo',
                //'asignatura.nombre',
                [
                    'attribute' => 'asignatura.nombre',
                    'label' => 'Nombre de la asignatura',
                ],
                //'area.asignatura.nombre',
                [
                    'attribute' => 'area.asignatura.nombre',
                    'label' => 'Nombre del área',
                ],
                'imprime:boolean',
                'es_cuantitativa:boolean',
                'promedia:boolean',
                'tipo',
            ],
        ])
        ?>

    </div>
</div>
