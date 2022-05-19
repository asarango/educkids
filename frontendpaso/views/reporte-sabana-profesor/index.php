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

?>
<div class="reporte-sabana-profesor-index">
    
    <?= Html::a('Imprimir Sábana', ['pdf', 'clase' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Terminar año lectivo', ['scholaris-actividad/terminar', 'clase' => $model->id], ['class' => 'btn btn-warning']) ?>
    
    <br>
    <br>

    <div class="table table-responsive">
        <table class="table table-condensed table-bordered table-hover tamano10">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Estudiante</td>
                    <td class="Q1"><?= Html::a('P1', ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => 1], ['class' => 'btn btn-link']) ?></td>
                    <td class="Q1"><?= Html::a('P2', ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => 2], ['class' => 'btn btn-link']) ?></td>
                    <td class="Q1"><?= Html::a('P3', ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => 3], ['class' => 'btn btn-link']) ?></td>
                    <td class="Q1">Pr</td>
                    <td class="Q1">80%</td>
                    <td class="Q1"><?= Html::a('Ex1', ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => 4], ['class' => 'btn btn-link']) ?></td>
                    <td class="Q1">20%</td>
                    <td>Q1<br><a href="#" onclick="oculta('Q1');">Detalle</a></td>
                    
                    <td class="Q2"><?= Html::a('P4', ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => 5], ['class' => 'btn btn-link']) ?></td>
                    <td class="Q2"><?= Html::a('P5', ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => 6], ['class' => 'btn btn-link']) ?></td>
                    <td class="Q2"><?= Html::a('P6', ['scholaris-actividad/parcial', 'clase' => $model->id,'orden' => 7], ['class' => 'btn btn-link']) ?></td>
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
                $i=0;
                foreach ($modelLibreta as $data){
                    $i++;
                    echo '<tr>';
                    echo '<td>'.$i.'</td>';
                    echo '<td>'.$data->grupo->id.$data->grupo->alumno->last_name.' '.$data->grupo->alumno->first_name.' '.$data->grupo->alumno->middle_name.'</td>';
                    
                    echo $data->p1 < $minima ? '<td bgcolor="#FF0000" class="Q1">'.$data->p1.'</td>' : '<td  class="Q1">'.$data->p1.'</td>';
                    echo $data->p2 < $minima ? '<td bgcolor="#FF0000" class="Q1">'.$data->p2.'</td>' : '<td  class="Q1">'.$data->p2.'</td>';
                    echo $data->p3 < $minima ? '<td bgcolor="#FF0000" class="Q1">'.$data->p3.'</td>' : '<td  class="Q1">'.$data->p3.'</td>';                    
                    echo '<td  class="Q1">'.$data->pr1.'</td>';
                    echo '<td  class="Q1">'.$data->pr180.'</td>';                    
                    echo $data->ex1 < $minima ? '<td bgcolor="#FF0000"  class="Q1">'.$data->ex1.'</td>' : '<td  class="Q1">'.$data->ex1.'</td>';
                    echo '<td class="Q1">'.$data->ex120.'</td>';
                    echo $data->q1 < $minima ? '<td bgcolor="#FF0000">'.$data->q1.'</td>' : '<td class="">'.$data->q1.'</td>';
                    
                    echo $data->p4 < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$data->p4.'</td>' : '<td  class="Q2">'.$data->p4.'</td>';
                    echo $data->p5 < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$data->p5.'</td>' : '<td  class="Q2">'.$data->p5.'</td>';
                    echo $data->p6 < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$data->p6.'</td>' : '<td  class="Q2">'.$data->p6.'</td>';                    
                    echo '<td class="Q2">'.$data->pr2.'</td>';
                    echo '<td class="Q2">'.$data->pr280.'</td>';
                    echo $data->ex2 < $minima ? '<td bgcolor="#FF0000" class="Q2">'.$data->ex2.'</td>' : '<td  class="Q2">'.$data->ex2.'</td>';
                    echo '<td class="Q2">'.$data->ex220.'</td>';
                    echo $data->q2 < $minima ? '<td bgcolor="#FF0000">'.$data->q2.'</td>' : '<td class="">'.$data->q2.'</td>';                    
                                        
                    echo $data->final_ano_normal < $minima ? '<td bgcolor="#FF0000">'.$data->final_ano_normal.'</td>' : '<td class="">'.$data->final_ano_normal.'</td>';     
                    
                    
                    
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
                        
                        echo '<td class="Q2">'.$data->mejora_q1.'</td>';
                        echo '<td class="Q2">'.$data->mejora_q2.'</td>';
                        echo '<td class="Q2">'.$data->supletorio.'</td>';
                        echo '<td class="Q2">'.$data->remedial.'</td>';
                        echo '<td class="Q2">'.$data->gracia.'</td>';
                        echo '<td class="Q2">'.$data->final_total.'</td>';
                        
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