<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisArchivosPudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Archivos PUD: '.$modelClase->curso->name
        .' | '.$modelClase->paralelo->name
        .' | '.$modelClase->materia->name
        .' | '.$modelClase->profesor->last_name.' '.$modelClase->profesor->x_first_name
        .' | '.$modelBloque->name
        ;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de Actividades', 'url' => ['profesor-inicio/actividades','id' => $modelClase->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-archivos-pud-index" style="padding-left: 40px; padding-right: 40px">

    

    
    <p>
        <?= Html::a(' Subir_Archivo', ['create','clase'=>$modelClase->id, 'bloque'=>$modelBloque->id], 
                ['class' => 'btn btn-success glyphicon glyphicon-plus']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'codigo',
            'bloque_id',
            'clase_id',
            'nombre',
            //'tipo_documento',
            //'estado',
            //'creado_fecha',
            //'creado_por',
            //'actualizado_fecha',
            //'actualizado_por',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
