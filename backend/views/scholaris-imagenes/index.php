<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisImagenesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Imágenes de Educandi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-imagenes-index" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Imagen', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'codigo',
            //'nombre_archivo',
            [
                'attribute' => 'nombre_archivo',
                'format'    => 'raw',
                'value'     => function($model){
                    if(!is_null($model->nombre_archivo)){
                        return Html::img('imagenesEducandi/'.$model->nombre_archivo, ['style' => 'width:80px;heigth:40; display:block;margin:auto']);
                    }else{
                        return 'vacio';
                    }
                }
            ],
            'alto_pixeles',
            'ancho_pixeles',
            //'detalle:ntext',
            //'imagen_educandi:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
