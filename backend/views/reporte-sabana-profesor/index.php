<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$rinde = $modelRindeSupletorio->rinde_supletorio ? 'SI RINDE SUPLETORIOS' : 'NO RINDE SUPLETORIOS';
$sentencias = new \backend\models\Notas();

$this->title = 'Sábana de notas anuales ';
$this->params['breadcrumbs'][] = ['label' => 'Detalles de cursos', 'url' => ['profesor-inicio/index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

$hoy = date("Y-m-d H:i:s");
?>

<div class="reporte-sabana-profesor-index" style="padding-left: 15px; padding-right: 20px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow">
            <div class=" row align-items-center">
                <div class="col-lg-2"><h4><img src="ISM/main/images/submenu/diagrama.png" width="64px" style=""></h4></div>
                <div class="col-lg-10"><h4>
                        <?= Html::encode($this->title) ?>
                    </h4>

                    <p class="">
                        <?php
                        echo 'Clase: ' . $model->id . ' / ' . $model->materia->name
                        . ' / ' . $model->profesor->last_name . ' ' . $model->profesor->x_first_name
                        . ' / ' . $model->curso->name . ' - ' . $model->paralelo->name
                        . ' / ' . $rinde
                        ?>
                    </p>
                </div>
            </div>
            <hr>

            <p>
                |                                
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>',
                        ['site/index'], ['class' => 'link']);
                ?>                
                |                                                
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</span>',
                        ['pdf', 'clase' => $model->id], ['class' => 'link']);
                ?>                
                |
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Actividades</span>',
                        ['profesor-inicio/actividades', 'id' => $model->id], ['class' => 'link']);
                ?>                
                |
                <?php
                if ($model->curso->section0->code == 'PAI') {
                    echo Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"> Best Fit !!</span>',
                            ['scholaris-notas-pai/index1', "id" => $model->id], ['class' => 'link']);
                }
                ?>
                |
                <?php
                if ($model->curso->section0->code == 'PAI') {
                    echo Html::a('<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-bell" aria-hidden="true"></i> Comportamiento</span>',
                            ['scholaris-notas-pai/index1', "id" => $model->id], ['class' => 'link']);
                }
                ?>
                |
            </p>

            <!--comienza tabla de calificaciones-->
            <div class="tableFixHead table table-responsive" style="height: 500px">
                <table class="table table-condensed table-bordered table-hover my-text-extra-small">
                    <thead>
                        <tr style="position: sticky; top: 0; z-index: 10; background-color: #ff9e18">
                            <td><b>#</b></td>
                            <td><b>Estudiante</b></td>

                            <?php
                            foreach ($modelBloqueQ1 as $bloq1) {
                                ?>
                                <td class="Q1 text-center"><b><?= Html::a(strtoupper($bloq1->abreviatura), 
                                        ['scholaris-actividad/parcial', 'clase' => $model->id, 'orden' => $bloq1->orden], 
                                        ['class' => 'link']) ?></b></td>
                                <?php
                            }
                            ?>                   
                                <td class="Q1 text-center"><b>Pr</b></td>
                                <td class="Q1 text-center"><b>80%</b></td>
                                <td class="Q1 text-center"><b><?= Html::a('Ex1', ['scholaris-actividad/parcial', 'clase' => $model->id, 'orden' => 4], 
                                        ['class' => 'link']) ?></b></td>
                            <td class="Q1 text-center">20%</td>
                            <td class="text-center">Q1<br><a href="#" onclick="oculta('Q1');">Detalle</a></td>

                            <?php
                            foreach ($modelBloqueQ2 as $bloq2) {
                                ?>
                                <td class="Q2 text-center"><b><?= Html::a(strtoupper($bloq2->abreviatura), 
                                        ['scholaris-actividad/parcial', 'clase' => $model->id, 'orden' => $bloq2->orden], 
                                        ['class' => 'link']) ?></b></td>
                                <?php
                            }
                            ?>    
                                <td class="Q2 text-center"><b>Pr</b></td>
                                <td class="Q2 text-center"><b>80%</b></td>
                                <td class="Q2 text-center"><b><?= Html::a('Ex2', ['scholaris-actividad/parcial', 'clase' => $model->id, 'orden' => 8], 
                                    ['class' => 'link']) ?></b></td>
                                <td class="Q2 text-center"><b>20%</b></td>
                                <td class="text-center">Q2<br><a href="#" onclick="oculta('Q2');">Detalle</a></td>                    

                                <td><b>PROM</b></td>

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
                        if ($tipoCalificacion == 0) {
                            new \backend\models\ProcesaNotasNormales($model->paralelo_id, ''); //invoca clase de procesamiento de notas por paralelo despues de danio
                            $sentenciasNotasAlumnos = new backend\models\AlumnoNotasNormales();
                        } elseif ($tipoCalificacion == 2) {
                            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasDisciplinar();
                        } elseif ($tipoCalificacion == 3) {
                            $sentenciasNotasAlumnos = new backend\models\AlumnoNotasInterdisciplinar();
                        } else {
                            echo 'No tiene creado un tipo de calificación para esta institutción!!!';
                            die();
                        }

                        $i = 0;
                        foreach ($modelLibreta as $data) {
                            $notasM = $sentenciasNotasAlumnos->get_nota_materia($data->grupo_id);

                            $i++;
                            echo '<tr>';
                            echo '<td>' . $i . '</td>';
                            echo '<td>' . $data->grupo->alumno->last_name . ' ' . $data->grupo->alumno->first_name . ' ' . $data->grupo->alumno->middle_name . '</td>';

                            echo $notasM['p1'] < $minima ? '<td bgcolor="" class="Q1" align="center" style="color: red; font-size: 14px"><strong>' . $notasM['p1'] . '</strong></td>' :
                                    '<td align="center" class="Q1">' . $notasM['p1'] . '</td>';

                            echo $notasM['p2'] < $minima ? '<td bgcolor="" class="Q1" align="center" style="color: red; font-size: 14px"<strong>' . $notasM['p2'] . '</strong></td>' :
                                    '<td align="center" class="Q1">' . $notasM['p2'] . '</td>';

                            if (count($modelBloqueQ1) > 2) {
                                echo $notasM['p3'] < $minima ? '<td bgcolor="" class="Q1" align="center" style="color: red; font-size: 14px"><strong>' . $notasM['p3'] . '</strong></td>' :
                                        '<td align="center" class="Q1">' . $notasM['p3'] . '</td>';
                            }

                            echo $notasM['pr1'] < $minima ? '<td bgcolor="" class="Q1 my-text-medium" align="center" style="color: red; font-size: 12px"><strong>' . $notasM['pr1'] . '</strong></td>' :
                                    '<td align="center" class="Q1"><strong>' . $notasM['pr1'] . '</strong></td>';

                            echo '<td align="center" class="Q1 my-text-medium" style="color: #00401c;"><strong>' . $notasM['pr180'] . '</strong></td>';

                            echo $notasM['ex1'] < $minima ? '<td bgcolor="" class="Q1" align="center" style="color: red; font-size: 14px"><strong>' . $notasM['ex1'] . '</strong></td>' :
                                    '<td align="center" class="Q1">' . $notasM['ex1'] . '</td>';

                            echo '<td align="center" class="Q1 my-text-medium" style="color: #00401c;"><strong>' . $notasM['ex120'] . '</strong></td>';
                            
                            echo $notasM['q1'] < $minima ? '<td bgcolor="" class="my-text-medium" align="center" style="color: red;"><strong>' . $notasM['q1'] . '</strong></td>' :
                                    '<td align="center" class="my-text-medium" style="color: #000; "><strong>' . $notasM['q1'] . '</strong></td>';

                            /// quimestre 2
                            echo $notasM['p4'] < $minima ? '<td bgcolor="" class="Q2" align="center" style="color: red; font-size: 14px"><strong>' . $notasM['p4'] . '</strong></td>' :
                                    '<td align="center" class="Q2">' . $notasM['p4'] . '</td>';

                            echo $notasM['p5'] < $minima ? '<td bgcolor="" class="Q2" align="center" style="color: red; font-size: 14px"><strong>' . $notasM['p5'] . '</strong></td>' :
                                    '<td align="center" class="Q2">' . $notasM['p5'] . '</td>';
                            if (count($modelBloqueQ1) > 2) {
                                echo $notasM['p6'] < $minima ? '<td bgcolor="" class="Q2" align="center" style="color: red; font-size: 14px"><strong>' . $notasM['p6'] . '</strong></td>' :
                                        '<td align="center" class="Q2">' . $notasM['p6'] . '</td>';
                            }
                            echo $notasM['pr2'] < $minima ? '<td bgcolor="" class="Q2" align="center" style="color: red; font-size: 14px"><strong>' . $notasM['pr2'] . '</strong></td>' :
                                    '<td align="center" class="Q2"><strong>' . $notasM['pr2'] . '</strong></td>';

                            echo '<td align="center" class="Q2 my-text-medium" style="color: #00401c;"><strong>' . $notasM['pr280'] . '</strong></td>';

                            echo $notasM['ex2'] < $minima ? '<td bgcolor="" class="Q2" align="center">' . $notasM['ex2'] . '</td>' : '<td align="center" class="Q2">' . $notasM['ex2'] . '</td>';
                            echo '<td align="center" class="Q2 my-text-medium" style="color: #00401c;"><strong>' . $notasM['ex220'] . '</strong></td>';

                            echo $notasM['q2'] < $minima ? '<td bgcolor="" class="my-text-medium" align="center" style="color: red;"><strong>' . $notasM['q2'] . '</strong></td>' :
                                    '<td align="center" class="my-text-medium" style="color: #000;"><strong>' . $notasM['q2'] . '</strong></td>';

                            echo $notasM['final_ano_normal'] < $minima ? '<td bgcolor="" class="my-text-medium" align="center" style="color: red;"><strong>' . $notasM['final_ano_normal'] . '</strong></td>' :
                                    '<td bgcolor=" #e7fbd3 " align="center" class="my-text-medium" style="color: #000;"><strong>' . $notasM['final_ano_normal'] . '</strong></td>';

                            $observacion1 = procesa_observacion($notasM['final_ano_normal'], $minima, $remedial);

                            echo '<td bgcolor="' . $observacion1['color'] . '" class="" align="center">';
                            echo Html::a($observacion1['consecuencia'], ['scholaris-actividad/extraordinarios', 'grupo' => $data->grupo_id], 
                                    ['class' => 'link', ]);
                            echo '</td>';

                            echo ($notasM['mejora_q1'] > $notasM['q1']) ? '<td bgcolor="#CEF97B" align="center">' . $notasM['mejora_q1'] . '</td>' : '<td bgcolor="" align="center">' . $notasM['mejora_q1'] . '</td>';
                            echo ($notasM['mejora_q2'] > $notasM['q2']) ? '<td bgcolor="#CEF97B" align="center">' . $notasM['mejora_q2'] . '</td>' : '<td bgcolor="" align="center">' . $notasM['mejora_q2'] . '</td>';
                            echo '<td class="" align="center">' . $notasM['supletorio'] . '</td>';
                            echo '<td class="" align="center">' . $notasM['remedial'] . '</td>';
                            echo '<td class="" align="center">' . $notasM['gracia'] . '</td>';
                            echo $notasM['final_total'] < $minima ? '<td bgcolor="#FF0000" class="" align="center">' . $notasM['final_total'] . '</td>' : '<td bgcolor=" #e7fbd3 " align="center" class="">' . $notasM['final_total'] . '</td>';

                            $observacion2 = procesa_observacion($notasM['final_total'], $minima, $remedial);
                            echo '<td bgcolor="' . $observacion2['color'] . '" class="" align="center">' . $observacion2['consecuencia'] . '</td>';

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!--finaliza tabla de calificaciones-->


        </div>

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

function busca_paralelo($periodoId, $alumnoId) {
    $con = Yii::$app->db;
    $query = "select 	i.parallel_id 
                    from	scholaris_op_period_periodo_scholaris sop
                                    inner join op_student_inscription i on i.period_id = sop.op_id 
                    where 	sop.scholaris_id = $periodoId
                                    and i.student_id = $alumnoId;";
    $res = $con->createCommand($query)->queryOne();
    return $res['parallel_id'];
}

function procesa_observacion($nota, $notaMinima, $notaRemedial) {
    $observacion = array(
        'consecuencia' => 'Sin rango',
        'color' => '#00000'
    );

    if ($nota >= $notaMinima && $nota > $notaRemedial) {
        $observacion = array(
            'consecuencia' => 'APROBADO',
            'color' => '#CEF97B'
        );
    } elseif ($nota < $notaMinima && $nota >= $notaRemedial) {
        $observacion = array(
            'consecuencia' => 'SUPLETORIO',
            'color' => '#F9C27B'
        );
    } else {
        $observacion = array(
            'consecuencia' => 'REMEDIAL',
            'color' => '#FE9696'
        );
    }

    return $observacion;
}
?>