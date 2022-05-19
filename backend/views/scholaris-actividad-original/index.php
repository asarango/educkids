<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Actividads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-actividad-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Actividad', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'create_date',
            'write_date',
            'create_uid',
            'write_uid',
            //'title',
            //'descripcion',
            //'archivo',
            //'descripcion_archivo',
            //'color',
            //'inicio',
            //'fin',
            //'tipo_actividad_id',
            //'bloque_actividad_id',
            //'a_peso',
            //'b_peso',
            //'c_peso',
            //'d_peso',
            //'paralelo_id',
            //'materia_id',
            //'calificado',
            //'tipo_calificacion',
            //'tareas',
            //'hora_id',
            //'actividad_original',
            //'semana_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
