<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanCurriculoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Reporte de comportamiento: ' . $modelParalelo->course->name . ' - ' . $modelParalelo->name
        . ' / ' . $modelBloque->name;
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Listado de cursos y paralelos', 'url' => ['scholaris-toma-asistecia/index']];
$this->params['breadcrumbs'][] = $this->title;


//print_r($modelActividad);
//die();

?>
<div class="reporte-comportamiento-index1">

    <div class="container">       

        <?php
        foreach ($modelBloques as $blo) {
            echo Html::a($blo->name, ['index1','id' => $modelParalelo->id,'parcial' => $blo->orden], ['class' => 'btn btn-link']);
        }
        ?>
        
        <div><h1><?= $modelBloque->name ?></h1></div>
        
    </div>
    
    <div class="table table-responsive">
        <table class="table table-hover table-bordered table-condensed">
            <tr>
                <td align="center"><strong>ESTUDIANTES</strong></td>
                <td align="center"><strong>SUGERIDO-SISTEMA</strong></td>
                <td align="center"><strong>CALIFICACIÓN</strong></td>
            </tr>
            
            <?php
                    foreach ($modelAlumnos as $alumno){
                        echo '<tr>';
                        echo '<td>'.Html::a($alumno['id'].'.- '.$alumno['last_name'].' '.$alumno['first_name'].' '.$alumno['middle_name'], 
                                            ['reporteiso','alumno' => $alumno['id'],'bloque' => $modelBloque->id,'tipo' => 'solo', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']).'</td>';
                        $datos = toma_calificacion_sugerida($alumno['id'], $modelBloque->id, $modelParalelo->id, $modelActividad);
                        echo '<td align="center">'.Html::a($datos, 
                                                            ['reportesugerido',
                                                             'alumno' => $alumno['id'],
                                                             'bloque' => $modelBloque->id,
                                                             'paralelo' => $modelParalelo->id
                                                            ], ['class' => 'btn btn-link']);
                        echo '</td>';
                        
                        $notaActi = toma_calificacion_actividad($alumno['id'], $modelActividad);
                        //echo '<td align="center">'.$notaActi.'</td>';
                        echo '<td align="center">'.Html::a($notaActi->calificacion, 
                                            ['cambianota','notaId' => $notaActi->id], ['class' => 'btn btn-link']).'</td>';
                        echo '</tr>';
                    }
            ?>
            
        </table>
    </div>
    
    

</div>

<?php
    function toma_calificacion_sugerida($alumno, $parcial, $paralelo, $modelActividad){
        
        $sentencias = new \backend\models\ComportamientoSugerido();
        $valor = $sentencias->devuelve_nota_sugerida($alumno, $parcial, $modelActividad);                
        return $valor;
        
    }
    
    function toma_calificacion_actividad($alumno,$modelActividad){
                  
        $sentencias = new \backend\models\ComportamientoSugerido();
        $valor = $sentencias->toma_nota_actividad($alumno, $modelActividad);                  
        return $valor;
    }

?>