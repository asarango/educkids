<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisImagenes */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Imágenes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="scholaris-imagenes-view" style="padding-left: 40px; padding-right: 40px">

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
            'codigo',
//            'nombre_archivo',
            'alto_pixeles',
            'ancho_pixeles',
            'detalle:ntext',
            'imagen_educandi:boolean',
             [
                'attribute' => 'nombre_archivo',
                'format'    => 'raw',
                'value'     => function($model){
                    if(!is_null($model->nombre_archivo)){
                        return Html::img('imagenesEducandi/'.$model->nombre_archivo, ['style' => 'display:block;margin:auto']);
                    }else{
                        return 'vacio';
                    }
                }
            ],
        ],
    ]) ?>

</div>
