<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\PlanCurriculoDistribucion;
use backend\models\OpCourse;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reporte de Seguimiento de Actividades';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reporte-seguimiento-actividades-index">

    

    <div class="container">

        <h4><?= Html::encode($this->title) ?></h4>
        
        <p>
            <?php //echo Html::a('Crear Planificacion', ['create'], ['class' => 'btn btn-success'])  ?>
        </p>

        <div class="table table-responsive">
            <table class="table table-hover table-striped table-bordered table-condensed">
                <tr>
                    <td align="center" rowspan="2"><strong>CURSOS</strong></td>
                    <?php echo presenta_bloques($modelBloques); ?>
                    <td align="center" rowspan="2"><strong>ACCIONES</strong></td>
                </tr>
                
                <tr>
                    <?php
                    foreach ($modelBloques as $bloque){
                        echo '<td align="center"><strong>SI</strong></td>';
                        echo '<td align="center"><strong>NO</strong></td>';
                    }
                    ?>
                    
                </tr>

                <?php echo presenta_cursos($cursos, $modelBloques); ?>

            </table>
        </div>


    </div>
</div>

<?php

function presenta_cursos($cursos, $modelBloques) {
    $html = '';

    foreach ($cursos as $curso) {
        $html .= '<tr>';
        $html .= '<td>' . $curso['name'] . '</td>';
        foreach ($modelBloques as $bloque) {
            //$html .= '<td>' . $bloque['abreviatura'] . '</td>';
            $totales = devuelve_totales($curso['id'], $bloque['orden']);
            
            if(isset($totales[0]['total'])){
                $html .= '<td align="center">'.$totales[0]['total'].'</td>';
            }else{
                $html .= '<td align="center">-</td>';
            }
            
            if(isset($totales[1]['total'])){
                $html .= '<td align="center">'.$totales[1]['total'].'</td>';
            }else{
                $html .= '<td align="center">-</td>';
            }
            
        }
        
        $html .= '<td align="center">';
        $html .= Html::a('', ['analizar','curso' => $curso['id']], ['class' => 'btn btn-success glyphicon glyphicon-eye-open']);
        $html .= '</td>';
        
        $html .= '</tr>';
    }
    return $html;
}

function presenta_bloques($modelBloques) {
    $html = '';
    foreach ($modelBloques as $bloque) {
        $html .= '<td align="center" colspan="2"><strong>' . $bloque['abreviatura'] . '</strong></td>';
    }

    return $html;
}

function devuelve_totales($curso, $orden){
    $con = Yii::$app->db;
    $query = "select 	count(*) as total
                                ,act.calificado
                from	scholaris_clase cla
                                inner join scholaris_actividad act on act.paralelo_id = cla.id
                                inner join scholaris_bloque_actividad blo on blo.id = act.bloque_actividad_id
                where	cla.idcurso = $curso
                                and blo.orden = $orden
                group by act.calificado
                order by act.calificado desc;";
    
//    echo $query;
//    die();
    
    $res = $con->createCommand($query)->queryAll();
    return $res;
}

?>