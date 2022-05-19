<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$rinde = $modelRindeSupletorio->rinde_supletorio ? 'SI RINDE SUPLETORIOS' : 'NO RINDE SUPLETORIOS';
$sentencias = new \backend\models\Notas();

$this->title = 'Sabana Profesor: ' . $model->id . ' / ' . $model->materia->name
        . ' / ' . $model->profesor->last_name . ' ' . $model->profesor->x_first_name
        . ' / ' . $model->curso->name . ' - ' . $model->paralelo->name
        . ' / ' . $rinde
;
$this->params['breadcrumbs'][] = ['label' => 'Clases de Docente', 'url' => ['profesor-inicio/clases']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

$hoy = date("Y-m-d H:i:s");
?>
<div class="reporte-sabana-profesor-index" style="padding-left: 15px; padding-right: 20px">

    <?= Html::a('Imprimir Sábana', ['pdf', 'clase' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?php
    if ($hoy >= $model->fecha_activacion && $hoy <= $model->fecha_cierre && $model->estado_cierre == false) {
        echo Html::a('Terminar año lectivo',
                ['scholaris-actividad/terminar', 'clase' => $model->id],
                ['class' => 'btn btn-warning']);
    }
    ?>

    <br>
    <br>

    <div class="tableFixHead table table-responsive">
        <table class="table table-condensed table-bordered table-hover tamano10">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Estudiante</td>

                    <?php
                    foreach ($modelBloqueQ1 as $bloq1) {
                        ?>
                            <!--<td class="Q1"><?= Html::a($bloq1->abreviatura, ['scholaris-actividad/parcial', 'clase' => $model->id, 'orden' => $bloq1->orden], ['class' => 'btn btn-link']) ?></td>-->
                        <td class="Q1"><?= $bloq1->abreviatura ?></td>
                        <?php
                    }
                    ?>                   
                    <td class="Q1">Pr</td>
                    <td class="Q1">80%</td>
                    <!--<td class="Q1"><?= Html::a('Ex1', ['scholaris-actividad/parcial', 'clase' => $model->id, 'orden' => 4], ['class' => 'btn btn-link']) ?></td>-->
                    <td class="Q1">Ex1</td>
                    <td class="Q1">20%</td>
                    <!--<td>Q1<br><a href="#" onclick="oculta('Q1');">Detalle</a></td>-->
                    <td>Q1</td>

                    <?php
                    foreach ($modelBloqueQ2 as $bloq2) {
                        ?>
                        <td class="Q2"><?= $bloq2->abreviatura ?></td>
                        <?php
                    }
                    ?>    
                    <td class="Q2">Pr</td>
                    <td class="Q2">80%</td>
                    <td class="Q2">Ex2</td>
                    <td class="Q2">20%</td>
                    <td>Q2  </td>                    

                    <td>PROM</td>

                    <?php
                    if ($modelRindeSupletorio->rinde_supletorio == 1) {
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
                $i = 0;
                foreach ($modelLibreta as $data) {

                    $i++;
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . $data->grupo_id . '-' . $data->grupo->alumno->last_name . ' ' . $data->grupo->alumno->first_name . ' ' . $data->grupo->alumno->middle_name . '</td>';
                    $notas = get_nota_insterdisciplinar($data->grupo_id);

                    $p1 = resultado_nota($notas, 1);
                    $p2 = resultado_nota($notas, 2);
                    $pr1 = $sentencias->truncarNota(($p1 + $p2) / 2, 2);
                    $pr180 = $sentencias->truncarNota(($pr1 * 80) / 100, 2);
                    $ex1 = $data->ex1;
                    $ex120 = $sentencias->truncarNota(($ex1 * 20) / 100, 2);
                    $q1 = $pr180 + $ex120;

                    $p4 = resultado_nota($notas, 5);
                    $p5 = resultado_nota($notas, 6);
                    $pr2 = $sentencias->truncarNota(($p4 + $p5) / 2, 2);
                    $pr280 = $sentencias->truncarNota(($pr2 * 80) / 100, 2);
                    $ex2 = $data->ex2;
                    $ex220 = $sentencias->truncarNota(($ex2 * 20) / 100, 2);
                    $q2 = $pr280 + $ex220;

                    $finalAnioNormal = $sentencias->truncarNota(($q1 + $q2) / 2, 2);


                    echo $p1 < $minima ? '<td bgcolor="#FF0000" class="Q1" align="center">' . $p1 . '</td>' : '<td  class="Q1" class="Q1" align="center">' . $p1 . '</td>';
                    echo $p2 < $minima ? '<td bgcolor="#FF0000" class="Q1" align="center">' . $p2 . '</td>' : '<td  class="Q1" class="Q1" align="center">' . $p2 . '</td>';
                    echo $pr1 < $minima ? '<td bgcolor="#FF0000" class="Q1" align="center">' . $pr1 . '</td>' : '<td  class="Q1" class="Q1" align="center">' . $pr1 . '</td>';
                    echo $pr180 < $minima ? '<td bgcolor="#FF0000" class="Q1" align="center">' . $pr180 . '</td>' : '<td  class="Q1" class="Q1" align="center">' . $pr180 . '</td>';
                    echo $ex1 < $minima ? '<td bgcolor="#FF0000" class="Q1" align="center">' . $ex1 . '</td>' : '<td  class="Q1" class="Q1" align="center">' . $ex1 . '</td>';
                    echo $ex120 < $minima ? '<td bgcolor="" class="Q1" align="center">' . $ex120 . '</td>' : '<td  class="Q1" class="Q1" align="center">' . $ex120 . '</td>';
                    echo $q1 < $minima ? '<td bgcolor="#FF0000" class="Q1" align="center">' . $q1 . '</td>' : '<td  class="Q1" class="Q1" align="center">' . $q1 . '</td>';

                    echo $p4 < $minima ? '<td bgcolor="#FF0000" class="Q2" align="center">' . $p4 . '</td>' : '<td  class="Q2" class="Q2" align="center">' . $p4 . '</td>';
                    echo $p5 < $minima ? '<td bgcolor="#FF0000" class="Q2" align="center">' . $p5 . '</td>' : '<td  class="Q2" class="Q2" align="center">' . $p5 . '</td>';
                    echo $pr2 < $minima ? '<td bgcolor="#FF0000" class="Q2" align="center">' . $pr2 . '</td>' : '<td  class="Q2" class="Q2" align="center">' . $pr2 . '</td>';
                    echo $pr280 < $minima ? '<td bgcolor="#FF0000" class="Q2" align="center">' . $pr280 . '</td>' : '<td  class="Q2" class="Q2" align="center">' . $pr280 . '</td>';
                    echo $ex2 < $minima ? '<td bgcolor="#FF0000" class="Q2" align="center">' . $ex2 . '</td>' : '<td  class="Q2" class="Q2" align="center">' . $ex2 . '</td>';
                    echo $ex220 < $minima ? '<td bgcolor="" class="Q2" align="center">' . $ex220 . '</td>' : '<td  class="Q2" class="Q2" align="center">' . $ex220 . '</td>';
                    echo $q2 < $minima ? '<td bgcolor="#FF0000" class="Q2" align="center">' . $q2 . '</td>' : '<td  class="Q2" class="Q2" align="center">' . $q2 . '</td>';
                    echo $finalAnioNormal < $minima ? '<td bgcolor="#FF0000" class="Q2" align="center">' . $finalAnioNormal . '</td>' : '<td  class="Q2" class="Q2" align="center">' . $finalAnioNormal . '</td>';                    

                    if($modelRindeSupletorio->rinde_supletorio == 1){
                        
                                                
                        
                        $observacionP = observacion($finalAnioNormal, $data->grupo->id);
                        echo '<td bgcolor="" class="Q2" align="center">'.$observacionP.'</td>';
                       
                        /***** para mejora *****/
                        echo '<td class="Q2">'.$data->mejora_q1.'</td>';
                        echo '<td class="Q2">'.$data->mejora_q2.'</td>';
                        
                        $totalMejorado = total_mejorado($q1, $q2, $data->mejora_q1, $data->mejora_q2, $finalAnioNormal,$minima);
                        
                        if($totalMejorado > $finalAnioNormal){
                            $final = $totalMejorado;
                            echo '<td class="Q2"></td>';
                            echo '<td class="Q2"></td>';
                            echo '<td class="Q2"></td>';
                            echo '<td class="Q2">'.$final.'</td>';
                        }else{
                            
                            echo '<td class="Q2">'.$data->supletorio.'</td>';
                            echo '<td class="Q2">'.$data->remedial.'</td>';
                            echo '<td class="Q2">'.$data->gracia.'</td>';
                            
                            $final = nota_final($finalAnioNormal, $data->supletorio, $data->remedial, $data->gracia, $minima);
                                                       
                            echo $final < $minima ? '<td bgcolor="#FF0000" class="Q2" align="center">' . $final . '</td>' : '<td  class="Q2" class="Q2" align="center">' . $final . '</td>';
                        }
                        
                        
                        ////// fin de mejora //////////
                        
                        
                        /*********  observacion FINAL *********/
                        $observacionFinal = observacion_final($final, $minima);
                        echo $observacionFinal == 'PIERDE' ? '<td bgcolor="#FF0000" class="Q2" align="center">' . $observacionFinal . '</td>' : 
                                                             '<td  class="Q2" class="Q2" align="center" style="color: green">' . $observacionFinal . '</td>';
                        //////////  fin de observacion FINAL //////////////
                        
                    }
                      
//                    if($modelRindeSupletorio->rinde_supletorio == 1){
//                        $conclucion1 = $sentencias->get_conclusion_antes_supletorios($data->grupo->id, $model->id, $data->final_ano_normal);
//                        if($conclucion1 == 'APROBADO'){
//                            $conclu = 'APROBADO';
//                            $color = "#CEF97B";
//                        } elseif ($conclucion1 == 'SUPLETORIO') {                            
//                            $conclu = 'SUPLETORIO';
//                            $color = "#F9C27B";
//                        } else {
//                            $conclu = 'REMEDIAL';
//                            $color = "#FE9696";
//                        }
//                        
//                        echo '<td bgcolor="'.$color.'">';
//                        echo Html::a('<p class="tamano10">'.$conclu.'</p>', ['scholaris-actividad/extraordinarios', 'grupo' => $data->grupo->id], ['class' => 'btn btn-link']);
//                        echo '</td>';
//                        
//                        echo '<td class="Q2">'.$data->mejora_q1.'</td>';
//                        echo '<td class="Q2">'.$data->mejora_q2.'</td>';
//                        echo '<td class="Q2">'.$data->supletorio.'</td>';
//                        echo '<td class="Q2">'.$data->remedial.'</td>';
//                        echo '<td class="Q2">'.$data->gracia.'</td>';
//                        echo '<td class="Q2">'.$data->final_total.'</td>';
//                        
//                        if($data->estado == 'APROBADO'){
//                            echo '<td class="Q2" bgcolor="#CEF97B">'.$data->estado.'</td>';
//                        }elseif($data->estado == 'PIERDE EL AÑO'){
//                            echo '<td class="Q2" bgcolor="#FF0000">'.$data->estado.'</td>';
//                        }else{
//                            echo '<td class="Q2">'.$data->estado.'</td>';
//                        }
//                        
//                        
//                        
//                    }
//                    


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


<?php

function get_nota_insterdisciplinar($grupoId) {
    $con = Yii::$app->db;
    $query = "select 	c.bloque_id 
                                ,sum(c.nota)
                                ,b.orden 
                                ,b.name
                from 	scholaris_calificaciones_parcial c
                                inner join scholaris_bloque_actividad b on b.id = c.bloque_id 
                where 	c.grupo_id = $grupoId
                group  by c.bloque_id, b.orden, b.name  ;";
    $res = $con->createCommand($query)->queryAll();
    return $res;
}

function resultado_nota($arrayNotas, $orden) {

    for ($i = 0; $i < count($arrayNotas); $i++) {
        if ($arrayNotas[$i]['orden'] == $orden) {
            return $parcial = $arrayNotas[$i]['sum'];
        }
    }
}


function observacion($notaFinalAnoNormal, $grupoId){
    $modelNotaMinima = backend\models\ScholarisParametrosOpciones::find()->where([
        'codigo' => 'nominpasar'
    ])->one();
    
    $modelNotaMinimaRemedial = backend\models\ScholarisParametrosOpciones::find()->where([
        'codigo' => 'notaRemed'
    ])->one();
    
    $notaMinima = $modelNotaMinima->valor;
    $notaMinimaRemedial = $modelNotaMinimaRemedial->valor;
    
    if($notaFinalAnoNormal >= $notaMinima){
        
        
        return Html::a('<p class="tamano10" style="color:green">APROBADO</p>', ['scholaris-actividad/extraordinarios', 'grupo' => $grupoId], ['class' => 'btn btn-link']);
        //return '<p style="color:green">APROBADO</p>';
        exit;
    }elseif($notaFinalAnoNormal < $notaMinima && $notaFinalAnoNormal >= $notaMinimaRemedial){
        return Html::a('<p class="tamano10" style="color:orange">SUPLETORIO</p>', ['scholaris-actividad/extraordinarios', 'grupo' => $grupoId], ['class' => 'btn btn-link']);
        //return '<p style="color:orange">SUPLETORIO</p>';
        exit;
    }else{
        return Html::a('<p class="tamano10" style="color:red">REMEDIAL</p>', ['scholaris-actividad/extraordinarios', 'grupo' => $grupoId], ['class' => 'btn btn-link']);
        //return '<p style="color:red">REMEDIAL</p>';
        exit;
    }
    
    
}

function total_mejorado($q1, $q2, $mejora1, $mejora2, $finalAnioNormal, $minimo){
    $sentencias = new \backend\models\Notas();
    
    $promedio = $finalAnioNormal;
    
    if($finalAnioNormal >= $minimo){
        
        if($mejora1 > $q1){
            $promedio = $sentencias->truncarNota(($mejora1 + $q2)/2,2);
        }elseif($mejora2 > $q2){
            $promedio = $sentencias->truncarNota(($mejora2 + $q1)/2,2);
        }
        
        
    }
    
    return $promedio;
    
}


function nota_final($notaAnoNormal, $supletorio, $remedial, $gracia, $minima){
    
    if($supletorio >= $minima || $remedial >= $minima || $gracia >= $minima){
        $promedio = $minima;
    }else{
        $promedio = $notaAnoNormal;
    }
    
    return $promedio;
}

function observacion_final($final, $minima){
    if($final>= $minima){
        return 'APROBADO';
    }else{
        return 'PIERDE';
    }
}
?>