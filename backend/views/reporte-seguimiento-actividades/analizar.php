<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\PlanCurriculoDistribucion;
use backend\models\OpCourse;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reporte de Seguimiento de Actividades del Curso: ' . $modelCurso->name;
$this->params['breadcrumbs'][] = ['label' => 'Reporte Seguimiento de Actividades', 'url' => ['index']];
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reporte-seguimiento-actividades-analizar">


    <div class="container">
        <h4><?= Html::encode($this->title) ?></h4>

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3"><?=
                        Html::a('Parcial 1', ['analizar',
                            'curso' => $modelCurso->id,
                            'orden' => 1], ['class' => 'btn btn-primary btn-block']);
                        ?>
                    </div>

                    <div class="col-md-3"><?=
                        Html::a('Parcial 2', ['analizar',
                            'curso' => $modelCurso->id,
                            'orden' => 2], ['class' => 'btn btn-primary btn-block']);
                        ?>
                    </div>

                    <div class="col-md-3"><?=
                        Html::a('Parcial 3', ['analizar',
                            'curso' => $modelCurso->id,
                            'orden' => 3], ['class' => 'btn btn-primary btn-block']);
                        ?>
                    </div>

                    <div class="col-md-3"><?=
                        Html::a('Examen 1', ['analizar',
                            'curso' => $modelCurso->id,
                            'orden' => 4], ['class' => 'btn btn-primary btn-block']);
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3"><?=
                        Html::a('Parcial 4', ['analizar',
                            'curso' => $modelCurso->id,
                            'orden' => 5], ['class' => 'btn btn-warning btn-block']);
                        ?>
                    </div>

                    <div class="col-md-3"><?=
                        Html::a('Parcial 5', ['analizar',
                            'curso' => $modelCurso->id,
                            'orden' => 6], ['class' => 'btn btn-warning btn-block']);
                        ?>
                    </div>

                    <div class="col-md-3"><?=
                        Html::a('Parcial 6', ['analizar',
                            'curso' => $modelCurso->id,
                            'orden' => 7], ['class' => 'btn btn-warning btn-block']);
                        ?>
                    </div>

                    <div class="col-md-3"><?=
                        Html::a('Examen 2', ['analizar',
                            'curso' => $modelCurso->id,
                            'orden' => 8], ['class' => 'btn btn-warning btn-block']);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-3 ">
                <div class="table table-responsive">
                    <table class="table table-hover table-condensed table-bordered table-striped">
                        <tr>
                            <td align="center" rowspan="2"><strong>PARALELO</strong></td>
                            <td align="center" colspan="2"><strong>CANTIDAD DE ACTIVIDADES</strong></td>
                        </tr>

                        <tr>
                            <td align="center" colspan=""><strong>SI</strong></td>
                            <td align="center" colspan=""><strong>NO</strong></td>
                        </tr>

                        <?php
//                 print_r($paralelos);

                        foreach ($paralelos as $paralelo) {
                            echo '<tr>';
                            echo '<td align="center">' . $paralelo['paralelo'] . '</td>';
                            echo '<td align="center">';
                            echo Html::a($paralelo['si'], ['analizar',
                                                           'paralelo' => $paralelo['id'],
                                                           'curso' => $modelCurso->id,
                                                           'orden' => $orden], ['class' => 'btn btn-link btn-block']);
                            echo '</td>';
                            echo '<td align="center">' . $paralelo['no'] . '</td>';
                            echo '</tr>';
                        }
                        ?>


                    </table>
                </div>
            </div>

            <div class="col-md-9 well">
                
                <?php  if($para != 0){ ?>
                        
                <h4>Detalle de Asignaturas del paralelo: <?= $modelParalelo->name ?></h4>
                
                <div class="table table-responsive">
                    <table class="table table-hover table-condensed table-hover table-striped table-bordered">
                        <tr>
                            <td align="center"><strong>ASIGNATURA</strong></td>
                            <td align="center"><strong>PROFESOR</strong></td>
                            <td align="center"><strong>TOT ESTUDIANTES</strong></td>
                            <td align="center"><strong>ACT. CALIFICADAS</strong></td>
                            <td align="center"><strong>CALIFICACIONES</strong></td>
                        </tr>
                        
                        <?php
                              $clases = toma_asignaturas($modelParalelo->id);
                            
                              foreach ($clases as $clase){
                                  echo '<tr>';
                                  echo '<td>'.$clase['materia'].'</td>';
                                  echo '<td>'.$clase['last_name'].' '.$clase['x_first_name'].'</td>';
                                  echo '<td align="center">'.$clase['total'].'</td>';
                                  
                                  $totalSi = toma_total_materia($orden, $clase['id']);                          
                                  echo '<td align="center">'.$totalSi.'</td>';
                                  
                                  $calif = notas_calificadas($clase['id'], $orden);
                                  
                                  //echo '<td align="center"><strong>'.$calif.'</strong> / '.$clase['total']*$totalSi.'</td>';
                                  
                                  echo '<td>';
                                  echo Html::a('<strong>'.$calif.'</strong> / '.$clase['total']*$totalSi, ['notasprofesor',
                                                           'clase' => $clase['id'],                                                           
                                                           'orden' => $orden], ['class' => 'btn btn-link btn-block']);
                                  echo '</td>';
                                  
                                  
                                  echo '</tr>';
                              }
                        ?>
                        
                        
                    </table>
                </div>
                
                <?php } ?>
                
            </div>
        </div>




    </div>
</div>


<?php
    function toma_asignaturas($paralelo){
        $con = Yii::$app->db;
        $query = "select 	c.id
                                    ,m.name as materia
                                    ,f.last_name
                                    ,f.x_first_name
                                    ,(
                                            select count(id) from scholaris_grupo_alumno_clase where clase_id = c.id
                                    ) as total
                    from	scholaris_clase c
                                    inner join scholaris_materia m on m.id = c.idmateria
                                    inner join op_faculty f on f.id = c.idprofesor
                    where	c.paralelo_id = $paralelo
                    order by m.name;";
        
               
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    function toma_total_materia($orden, $clase){
        $con = Yii::$app->db;
//        $query = "select 	count(a.id) as total
//                    from	scholaris_actividad a
//                                    inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id
//                                    --left join scholaris_actividad_descriptor d on d.actividad_id = a.id
//                    where	a.calificado = 'SI'
//                                    and paralelo_id = $clase
//                                    and b.orden = $orden;";
//        
        
        $query = "select sum(total) as total
                    from
                    (
                    select (select count(distinct criterio_id) from scholaris_actividad_descriptor where actividad_id = a.id) as total
                    from scholaris_actividad a 
                    inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id 
                    --left join scholaris_actividad_descriptor d on d.actividad_id = a.id 
                    where 	a.calificado = 'SI' 
                                    and a.tipo_calificacion = 'P'
                                    and paralelo_id = $clase 
                                    and b.orden = $orden
                    union all
                    select count(a.id)
                    from scholaris_actividad a 
                    inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id  
                    where 	a.calificado = 'SI' 
                                    and a.tipo_calificacion = 'N'
                                    and paralelo_id = $clase 
                                    and b.orden = $orden
                    )as total;	";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        return $res['total'];
    }
    
    function notas_calificadas($clase, $orden){
        $con = Yii::$app->db;
        $query = "select 	count(c.id) as total
                    from	scholaris_calificaciones c
                                    inner join scholaris_actividad a on a.id = c.idactividad
                                    inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id
                    where	a.paralelo_id = $clase
                                    and c.calificacion > 0
                                    and b.orden = $orden;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        return $res['total'];
    }

?>