<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisRefuerzoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Refuerzos Académicos: '.$modelGrupo->alumno->last_name.' '.$modelGrupo->alumno->first_name.' '.$modelGrupo->alumno->middle_name
        .' / '.$modelGrupo->clase->materia->name
        .' / '.$modelGrupo->clase->profesor->last_name.' '.$modelGrupo->clase->profesor->x_first_name
        .' / '.$modelBloque->name
        ;

?>
<div class="scholaris-refuerzo-index">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item">
                <?php echo Html::a('Actividades del Parcial', ['scholaris-actividad/parcial', "clase" => $modelGrupo->clase_id, 'orden' => $modelBloque->orden]); ?>
            </li>

            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
        </ol>
    </nav>
    <p>
        
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'grupo_id',
//            'bloque_id',
//            'orden_calificacion',
            [
                'attribute' => 'orden_calificacion',
                'value' => function($model, $key, $index, $widget) {
                        $prueba = \backend\models\ScholarisGrupoOrdenCalificacion::find()->where(['grupo_numero' => $model->orden_calificacion])->one();
                        return $prueba->nombre_grupo;
                    },
            ],
            'promedio_normal',
            'nota_refuerzo',
            'nota_final',
            'observacion:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
