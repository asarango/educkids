<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisAsistenciaComportamientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Parámetros de Comportamiento';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-asistencia-comportamiento-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <p>
        CRITERIOS PARA LA EVALUACIÓN DE LA CONDUCTA
    </p>

    <?php echo Html::a('Crear Criterio Comportamiento', ['create'], ['class' => 'btn btn-success']) ?>

    <?php
    foreach ($model as $data) {
        echo '<div class="table table-responsive">';
        echo '<table class="table table-condensed table-hover table-bordered" width="100%">';


        echo '<tr>';
        echo '<td colspan="7" align="center"><strong>';
        echo Html::a($data->nombre,['updatedetalle','id' => $data->id],['class' => 'btn btn-link']);
        echo '</strong></td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td width="30%"><strong>CÓDIGO LECCIONARIO</strong></td>';
        echo '<td><strong>FRECUENCIA</strong></td>';
        echo '<td><strong>PUNTOS</strong></td>';
        echo '<td><strong>ACCIÓN</strong></td>';
        echo '<td><strong>OBSERVACIÓN</strong></td>';
        echo '<td><strong>ALERTA</strong></td>';
//        echo '<td><strong>LÍMITE</strong></td>';
        echo '</tr>';
        
        echo detalle($data->id);       


        echo '</table>';
        echo '</div>';
    }
    ?>

</div>

<?php

function detalle($id) {
    $html = '';

    $model = toma_codigos_detalle($id);
    
    foreach ($model as $data){
        $html.= '<tr>';
        $html.= '<td>';
        $html.= Html::a($data['codigo'].' '.$data['nombre'],
                ['scholaris-asistencia-comportamiento-fecuencia/index1','id' => $data['id']],
                ['class' => 'btn btn-link']);
        $html.='</td>';
        $html.= '<td>'.$data['fecuencia'].'</td>';
        $html.= '<td>'.$data['puntos'].'</td>';
        $html.= '<td>'.$data['accion'].'</td>';
        $html.= '<td>'.$data['observacion'].'</td>';
        $html.= '<td>'.$data['alerta'].'</td>';
        $html.= '</tr>';
    }


    return $html;
}

function toma_codigos_detalle($compId) {
    $con = Yii::$app->db;
    $query = "select 	d.id
		,d.codigo
		,d.nombre
		,f.fecuencia
		,f.puntos
		,f.accion
		,f.observacion
		,f.alerta
from 	scholaris_asistencia_comportamiento_fecuencia f
		right join scholaris_asistencia_comportamiento_detalle d on d.id = f.detalle_id
where	d.comportamiento_id = $compId
order by d.codigo asc, f.fecuencia;";
    $res = $con->createCommand($query)->queryAll();
    return $res;
}
?>