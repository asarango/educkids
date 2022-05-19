<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMecV2HomologacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Homologación de la materia: '.$modelDisti->materia->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Distributivo de malla: ', 'url' => ['scholaris-mec-v2-distribucion/index1', 'malla' => $modelDisti->materia->mallaArea->malla_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-homologacion-index">

    <p>
        <?= Html::a('Ingresar nueva materia', ['create','distribucionId' => $modelDisti->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'distribucion_id',
            'tipo',
            'codigo_tipo',
            'nombre_tipo',
            //'profesor_nombre',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
