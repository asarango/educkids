<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaArea */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Malla Áreas', 'url' => ['index1', 'id' => $modelMalla->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-mec-v2-malla-area-view">

    <div class="container">
        <p>
            <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?=
            Html::a('Borrar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Esta seguro de eliminar el Área?... ¡Esto eliminará toda la configuración!',
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
                'asignatura.nombre',
                'malla.nombre',
                'imprime:boolean',
                'es_cuantitativa:boolean',
                'tipo',
                'promedia',
            ],
        ])
        ?>

    </div>
</div>
