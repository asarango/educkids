<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;


$rinde = $modelRindeSupletorio->rinde_supletorio ? 'SI RINDE SUPLETORIOS' : 'NO RINDE SUPLETORIOS';
$sentencias = new \backend\models\Notas();

$this->title = 'Update Scholaris Clase: ' . $model->id.' / '.$model->materia->name
        .' / '.$model->profesor->last_name.' '.$model->profesor->x_first_name
        .' / '.$model->curso->name. ' - '.$model->paralelo->name
        .' / '.$rinde
        ;
$this->params['breadcrumbs'][] = ['label' => 'Clases de Docente', 'url' => ['profesor-inicio/clases']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

$hoy = date("Y-m-d H:i:s");

?>
<div class="reporte-sabana-profesor-index" style="padding-left: 15px; padding-right: 20px">
    
    <?= Html::a('Imprimir Sábana', ['pdf', 'clase' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?php 
        if($hoy >= $model->fecha_activacion && $hoy <= $model->fecha_cierre && $model->estado_cierre == false){
        echo Html::a('Terminar año lectivo', 
                ['scholaris-actividad/terminar', 'clase' => $model->id], 
                ['class' => 'btn btn-warning']);
        }
    ?>
    
    <br>
    <br>

    <div class="tableFixHead table table-responsive" style="height: 500px">
        <table class="table table-condensed table-bordered table-hover tamano10">
            <thead>
                <tr style="position: sticky; top: 0; z-index: 10; background-color: #CCC">
                    <td>#</td>
                    <td>Estudiante</td>
                    
                    <?php
                        foreach ($modelBloqueQ1 as $bloq1){
                            ?>
                    <td class="Q1"><?= Html::a($bloq1->abreviatura, ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => $bloq1->orden], ['class' => 'btn btn-link']) ?></td>
                    <?php
                        }
                    ?>                   
                    <td class="Q1">Pr</td>
                    <td class="Q1">80%</td>
                    <td class="Q1"><?= Html::a('Ex1', ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => 4], ['class' => 'btn btn-link']) ?></td>
                    <td class="Q1">20%</td>
                    <td>Q1<br><a href="#" onclick="oculta('Q1');">Detalle</a></td>
                    
                    <?php
                        foreach ($modelBloqueQ2 as $bloq2){
                            ?>
                    <td class="Q2"><?= Html::a($bloq2->abreviatura, ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => $bloq2->orden], ['class' => 'btn btn-link']) ?></td>
                    <?php
                        }
                    ?>    
                    <td class="Q2">Pr</td>
                    <td class="Q2">80%</td>
                    <td class="Q2"><?= Html::a('Ex2', ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => 8], ['class' => 'btn btn-link']) ?></td>
                    <td class="Q2">20%</td>
                    <td>Q2<br><a href="#" onclick="oculta('Q2');">Detalle</a></td>                    
                    
                    <td>PROM</td>
                    
                    <?php
                        if($modelRindeSupletorio->rinde_supletorio == 1){
                            echo '<td>OBSERVACIONES</td>';
                            echo '<td>MEJORA Q1</td>';
                            echo '<td>MEJORA Q2</td>';
                            echo '<td>SUPLETORIO</td>';
                            echo '<td>REMEDIAL</td>';
                            echo '<td>GRACIA</td>';
                            echo '<td>NOTA FINAL</td>';
                            echo '<td>OBSERVACION FINAL</td>';
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                
                $tipoCalif = $modelCalificacion;
                
                $i=0;
                foreach ($modelLibreta as $data){
                                       
                    $paraleloId = busca_paralelo($periodoId, $data->grupo->alumno->id);
                    
                    if($tipoCalif == 'normal'){
                    
                    $claseNotasDefinitivas = new backend\models\SentenciasNotasDefinitivasAlumno($data->grupo->alumno->id, $periodoId, $paraleloId);                  
                    $notas = $claseNotasDefinitivas->get_nota_materia($model->idmateria, $data->grupo_id);         
                    
                    $i++;
                    echo '<tr>';
                    echo '<td>'.$i.'</td>';
                    echo '<td>'.$data->grupo->alumno->last_name.' '.$data->grupo->alumno->first_name.' '.$data->grupo->alumno->middle_name.'</td>';
                    
                    echo $notas['p1'] < $minima ? '<td bgcolor="#FF0000" class="Q1">'.$notas['p1'].'</td>' : '<td  class="Q1">'.$notas['p1'].'</td>';
                    echo $notas['p2'] < $minima ? '<td bgcolor="#FF0000" class="Q1">'.$notas['p2'].'</td>' : '<td  class="Q1">'.$notas['p2'].'</td>';
                    if(count($modelBloqueQ1) > 2){
                        echo $notas['p3'] < $minima ? '<td bgcolor="#FF0000" class="Q1">'.$notas['p3'].'</td>' : '<td  class="Q1">'.$notas['p3'].'</td>';                    
                    }
                    echo '<td  class="Q1">'.$notas['pr1'].'</td>';
                    echo '<td  class="Q1">'.$notas['pr180'].'</td>';                    
                    echo $notas['ex1'] < $minima ? '<td bgcolor="#FF0000"  class="Q1">'.$notas['ex1'].'</td>' : '<td  class="Q1">'.$notas['ex1'].'</td>';
                    echo '<td class="Q1">'.$notas['ex120'].'</td>';
                    echo $notas['q1'] < $minima ? '<td bgcolor="#FF0000">'.$notas['q1'].'</td>' : '<td class="">'.$notas['q1'].'</td>';
                    
                    echo $notas['p4'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas['p4'].'</td>' : '<td  class="Q2">'.$notas['p4'].'</td>';
                    echo $notas['p5'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas['p5'].'</td>' : '<td  class="Q2">'.$notas['p5'].'</td>';
                    echo $notas['p6'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas['p6'].'</td>' : '<td  class="Q2">'.$notas['p6'].'</td>';                    
                    echo '<td class="Q2">'.$notas['pr2'].'</td>';
                    echo '<td class="Q2">'.$notas['pr280'].'</td>';
                    echo $notas['ex2'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas['ex2'].'</td>' : '<td  class="Q2">'.$notas['ex2'].'</td>';
                    echo '<td class="Q2">'.$notas['ex220'].'</td>';
                    echo $notas['q2'] < $minima ? '<td bgcolor="#FF0000">'.$notas['q2'].'</td>' : '<td class="">'.$notas['q2'].'</td>';                    
                                        
                    echo $notas['final_ano_normal'] < $minima ? '<td bgcolor="#FF0000">'.$notas['final_ano_normal'].'</td>' : '<td class="">'.$notas['final_ano_normal'].'</td>';     
                    
                    
                    
                    if($modelRindeSupletorio->rinde_supletorio == 1){
                        $conclucion1 = $sentencias->get_conclusion_antes_supletorios($data->grupo->id, $model->id, $data->final_ano_normal);
                        if($conclucion1 == 'APROBADO'){
                            $conclu = 'APROBADO';
                            $color = "#CEF97B";
                        } elseif ($conclucion1 == 'SUPLETORIO') {                            
                            $conclu = 'SUPLETORIO';
                            $color = "#F9C27B";
                        } else {
                            $conclu = 'REMEDIAL';
                            $color = "#FE9696";
                        }
                        
                        echo '<td bgcolor="'.$color.'">';
                        echo Html::a('<p class="tamano10">'.$conclu.'</p>', ['scholaris-actividad/extraordinarios', 'grupo' => $data->grupo->id], ['class' => 'btn btn-link']);
                        echo '</td>';
                        
                        echo '<td class="Q2">'.$notas['mejora_q1'].'</td>';
                        echo '<td class="Q2">'.$notas['mejora_q2'].'</td>';
                        echo '<td class="Q2">'.$notas['supletorio'].'</td>';
                        echo '<td class="Q2">'.$notas['remedial'].'</td>';
                        echo '<td class="Q2">'.$notas['gracia'].'</td>';
                        echo '<td class="Q2">'.$notas['final_total'].'</td>';
                        
                        if($data->estado == 'APROBADO'){
                            echo '<td class="Q2" bgcolor="#CEF97B">'.$data->estado.'</td>';
                        }elseif($data->estado == 'PIERDE EL AÑO'){
                            echo '<td class="Q2" bgcolor="#FF0000">'.$data->estado.'</td>';
                        }else{
                            echo '<td class="Q2">'.$data->estado.'</td>';
                        }
                        
                        
                        
                    }
                    
                    
                    
                    echo '</tr>';
//                    }elseif($tipoCalif == 2){
                    }else{ ////// PARA TIPO DE CALIFICACION DISCIPLINAR
                        
                        //PARA TOMAR CALIFICACIONES DE COVID
                        $calificacionDisciplinar = new \backend\models\NotasAlumnosCovid($data->grupo_id);                        
                        $notas = $calificacionDisciplinar->arrayNotasQ1;
                        $notas2 = $calificacionDisciplinar->arrayNotasQ2;
                                                
                        //PARA TOMAR CALIFICACIONES DE EXAMENES EXTRAS
//                        $claseNotasDefinitivas = new backend\models\SentenciasNotasDefinitivasAlumno($data->grupo->alumno->id, $periodoId, $paraleloId);                  
                        $notasExtras = backend\models\ScholarisClaseLibreta::find()->where([
                            'grupo_id' => $data->grupo_id
                        ])->one();
                        
                        $i++;
                    echo '<tr>';
                    echo '<td>'.$i.'</td>';
                    echo '<td>'.$data->grupo->alumno->last_name.' '.$data->grupo->alumno->first_name.' '.$data->grupo->alumno->middle_name.'</td>';
                    
                    echo $notas[0]['p1'] < $minima ? '<td bgcolor="#FF0000" class="Q1">'.$notas[0]['p1'].'</td>' : '<td  class="Q1">'.$notas[0]['p1'].'</td>';
                    echo $notas[0]['p2'] < $minima ? '<td bgcolor="#FF0000" class="Q1">'.$notas[0]['p2'].'</td>' : '<td  class="Q1">'.$notas[0]['p2'].'</td>';
                    if(count($modelBloqueQ1) > 2){
                        echo $notas[0]['p3'] < $minima ? '<td bgcolor="#FF0000" class="Q1">'.$notas[0]['p3'].'</td>' : '<td  class="Q1">'.$notas[0]['p3'].'</td>';                    
                    }
                    echo '<td  class="Q1">'.$notas[0]['pr1'].'</td>';
                    echo '<td  class="Q1">'.$notas[0]['pr180'].'</td>';                    
                    echo $notas[0]['ex1'] < $minima ? '<td bgcolor="#FF0000"  class="Q1">'.$notas[0]['ex1'].'</td>' : '<td  class="Q1">'.$notas[0]['ex1'].'</td>';
                    echo '<td class="Q1">'.$notas[0]['ex120'].'</td>';
                    echo $notas[0]['q1'] < $minima ? '<td bgcolor="#FF0000">'.$notas[0]['q1'].'</td>' : '<td class="">'.$notas[0]['q1'].'</td>';
                    
                    
                    echo $notas2[0]['p4'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas2[0]['p4'].'</td>' : '<td  class="Q2">'.$notas2[0]['p4'].'</td>';
                    echo $notas2[0]['p5'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas2[0]['p5'].'</td>' : '<td  class="Q2">'.$notas2[0]['p5'].'</td>';
                    if(count($modelBloqueQ2) > 2){
                        echo $notas2[0]['p6'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas2[0]['p6'].'</td>' : '<td  class="Q2">'.$notas2[0]['p6'].'</td>';                    
                    }
                    echo '<td  class="Q2">'.$notas2[0]['pr2'].'</td>';
                    echo '<td  class="Q2">'.$notas2[0]['pr280'].'</td>';                    
                    echo $notas2[0]['ex2'] < $minima ? '<td bgcolor="#FF0000"  class="Q2">'.$notas2[0]['ex2'].'</td>' : '<td  class="Q2">'.$notas2[0]['ex2'].'</td>';
                    echo '<td class="Q2">'.$notas2[0]['ex220'].'</td>';
                    echo $notas2[0]['q2'] < $minima ? '<td bgcolor="#FF0000">'.$notas2[0]['q2'].'</td>' : '<td class="">'.$notas2[0]['q2'].'</td>';
                    
                    echo '<td  class="">'.$calificacionDisciplinar->promedio.'</td>';
                    
//                    echo $notas['p4'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas['p4'].'</td>' : '<td  class="Q2">'.$notas['p4'].'</td>';
//                    echo $notas['p5'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas['p5'].'</td>' : '<td  class="Q2">'.$notas['p5'].'</td>';
//                    echo $notas['p6'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas['p6'].'</td>' : '<td  class="Q2">'.$notas['p6'].'</td>';                    
//                    echo '<td class="Q2">'.$notas['pr2'].'</td>';
//                    echo '<td class="Q2">'.$notas['pr280'].'</td>';
//                    echo $notas['ex2'] < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$notas['ex2'].'</td>' : '<td  class="Q2">'.$notas['ex2'].'</td>';
//                    echo '<td class="Q2">'.$notas['ex220'].'</td>';
//                    echo $notas['q2'] < $minima ? '<td bgcolor="#FF0000">'.$notas['q2'].'</td>' : '<td class="">'.$notas['q2'].'</td>';                    
//                                        
//                    echo $notas['final_ano_normal'] < $minima ? '<td bgcolor="#FF0000">'.$notas['final_ano_normal'].'</td>' : '<td class="">'.$notas['final_ano_normal'].'</td>';     
//                    
//                    
//                    
                    if($modelRindeSupletorio->rinde_supletorio == 1){
                        $conclucion1 = $sentencias->get_conclusion_antes_supletorios($data->grupo->id, $model->id, $calificacionDisciplinar->promedio);                        
                        
                        if($conclucion1 == 'APROBADO'){
                            $conclu = 'APROBADO';
                            $color = "#CEF97B";
                        } elseif ($conclucion1 == 'SUPLETORIO') {                            
                            $conclu = 'SUPLETORIO';
                            $color = "#F9C27B";
                        } else {
                            $conclu = 'REMEDIAL';
                            $color = "#FE9696";
                        }
                        
                        echo '<td bgcolor="'.$color.'">';
                        echo Html::a('<p class="tamano10">'.$conclu.'</p>', ['scholaris-actividad/extraordinarios', 'grupo' => $data->grupo->id], ['class' => 'btn btn-link']);
                        echo '</td>';
                        
                        echo '<td class="Q2">'.$notasExtras->mejora_q1.'</td>';
                        echo '<td class="Q2">'.$notasExtras->mejora_q2.'</td>';
                        echo '<td class="Q2">'.$notasExtras->supletorio.'</td>';
                        echo '<td class="Q2">'.$notasExtras->remedial.'</td>';
                        echo '<td class="Q2">'.$notasExtras->gracia.'</td>';
                        echo '<td class="Q2">'.$notasExtras->final_total.'</td>';
                        
                        if($data->estado == 'APROBADO'){
                            echo '<td class="Q2" bgcolor="#CEF97B">'.$data->estado.'</td>';
                        }elseif($data->estado == 'PIERDE EL AÑO'){
                            echo '<td class="Q2" bgcolor="#FF0000">'.$data->estado.'</td>';
                        }else{
                            echo '<td class="Q2">'.$data->estado.'</td>';
                        }                        
                        
                    }
                    
                    
                    
                    echo '</tr>';
                    }
//                    echo $data->grupo->id;
//                    print_r($notas);
//                    die();
                    
                    
                }
                ?>
            </tbody>
        </table>
    </div>
    
    
</div>

<script>
    function oculta(q) {
        var qui = "." + q;
        var visible = $(qui + ":visible").length;// > 0;

        if (visible == 0) {
            //$(qui).show("slide",{direction: "left"}, 1000);
            $(qui).fadeIn(1000);
            $(qui).css('border-bottom-color', '#FF0000');
            $(qui).css('border-style', 'solid');
            $(qui).css('border-color', '#007951');
            $(qui).css('border-width', '0.2px');

        } else {
            $(qui).fadeOut(1000);
        }

    }
</script>


<?php
    function busca_paralelo($periodoId, $alumnoId){
        $con = Yii::$app->db;
        $query = "select 	i.parallel_id 
                    from	scholaris_op_period_periodo_scholaris sop
                                    inner join op_student_inscription i on i.period_id = sop.op_id 
                    where 	sop.scholaris_id = $periodoId
                                    and i.student_id = $alumnoId;";
        $res = $con->createCommand($query)->queryOne();
        return $res['parallel_id'];
    }
?>