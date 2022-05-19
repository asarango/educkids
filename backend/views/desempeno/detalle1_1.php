<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$modelNotas = new \backend\models\SentenciasNotas();
$sentencias = new backend\models\Notas();



$this->title = 'Detalle del nivel: ' . $modelParalelo->course->name . ' - ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = ['label' => 'Volver', 'url' => ['detalle', 'id' => $modelParalelo->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
table {
  border:0px solid black;
}
table th {
  border-bottom:0px solid black;
}


th.rotate {
  height:150px;
  white-space: nowrap;
  position:relative;
}

th.rotate > div {
  transform: rotate(-90deg);
  position:absolute;
  left:0;
  right:0;
  top: 80px;
  margin:auto;
  
}


</style>


<div class="desempeno-detalle1">


    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-2"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'p1']) ?>">Parcial 1</a></div>
                    <div class="col-md-2"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'p2']) ?>">Parcial 2</a></div>
                    <div class="col-md-2"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'p3']) ?>">Parcial 3</a></div>
                    <div class="col-md-2"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'ex1']) ?>">Examen 1</a></div>
                    <div class="col-md-4"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'q1']) ?>">QUIMESTRE 1</a></div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-2"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'p4']) ?>">Parcial 4</a></div>
                    <div class="col-md-2"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'p5']) ?>">Parcial 5</a></div>
                    <div class="col-md-2"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'p6']) ?>">Parcial 6</a></div>
                    <div class="col-md-2"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'ex2']) ?>">Examen 2</a></div>
                    <div class="col-md-4"><a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'q2']) ?>">QUIMESTRE 2</a></div>
                </div>
            </div>

            <div class="col-md-2">
                <a href="<?= Url::to(['detalle1', 'id' => $modelParalelo->id, 'parcial' => 'final_ano_normal']) ?>">FIN DE AÑO</a>
            </div>
        </div>
    </div>

    <hr>

    <div class="alert alert-success">
        <strong>DATOS CORRESPONDIENTES AL PARCIAL: </strong><?= $mensaje[0] ?>        
    </div>
    
   
    

    <div class="row">
        <div class="col-md-12">
            <div class="table table-responsive">
                <table class="table table-condensed table-hover table-bordered tamano10 sticky">
                    <thead>
                        <tr>
                            <th class="rotate static" scope="col"><div><span>#</span></th>
                            <th class="first-col" scope="col">Estudiante</th>
                            <?php
                            foreach ($modelMaterias as $materia) {
                                if ($materia['promedia'] == true) {
                                    
                                    
                                    
                                    echo '<th align="center">';
                                    
                                   echo Html::a('<font style="font-size:8px; color:#036714">'.$materia['materia'].'<br>'.$materia['last_name'] . ' ' . $materia['x_first_name'].'</font>', 
                                             ['sabana','clase'=>$materia['id']], ['class' => 'btn btn-link']);
                                   
                                    echo '</th>';
                                } else {
                                    echo '<th align="center" class="rotate">';
                                    echo '<div><span>';
                                     echo Html::a('<font style="font-size:8px; color:#036714">*'.$materia['materia'].'<br>'.$materia['last_name'] . ' ' . $materia['x_first_name'].'</font>', 
                                             ['sabana','clase'=>$materia['id']], ['class' => 'btn btn-link']);
                                    
                                    echo '</span></div>';
                                    echo '</th>';
                                }
                            }
                            ?>
                            <th><center><strong>PROMEDIO</strong></center></th>
                    <th># Casos</th>
                    <th>Responsable</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($modelAlumnos as $alumno) {
                            $i++;
                            echo '<tr>';
                            echo '<td>' . $i . '</td>';
//                            echo '<td>' . $alumno->last_name . ' ' . $alumno->first_name . '</td>';
                            echo '<td>';
                            echo Html::a('<font style="font-size:8px; color:black">'.$alumno->last_name . ' ' . $alumno->first_name.'</font>', 
                                             ['libreta','alumno'=>$alumno->id,'paralelo'=>$modelParalelo->id, 'curso' =>$modelParalelo->course_id], 
                                             ['class' => 'btn btn-link']);
                            echo '</td>';
                            

                            foreach ($modelMaterias as $materia) {
//                                echo '<td>'.$materia['id'].'</td>';
                                $notas = $modelNotas->toma_nota_clase($campo, $alumno->id, $materia['id']);
                                $not = $notas['nota'];
                                if ($notas['nota'] < $modelMinimo->valor) {
                                    echo '<td bgcolor="#FDD3AF" align="center">';
//                                    echo $notas['nota'];
                                    
                                    echo '<button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal" '
                                                .'onclick="verificaNotas('.$alumno->id.','.$materia['id'].',\''.$campo.'\','.$not.')">'
                                            . '<font style="font-size:10px;">'
                                                .$notas['nota'].'</font></button>';
                                    echo '</td>';
                                    echo '</td>';
                                } else {
                                    echo '<td bgcolor="" align="center">';
//                                    echo $notas['nota'];
                                    echo '<button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal" '
                                                .'onclick="verificaNotas('.$alumno->id.','.$materia['id'].',\''.$campo.'\','.$not.')">'
                                            .'<font style="font-size:10px;">'
                                                .$notas['nota'].'</font></button>';
                                    echo '</td>';
                                }
                            }

                            $promedioAlu = $sentencias->get_notas_finales($alumno->id, $usuario, $malla);

                            echo '<td><center><strong>' . $promedioAlu[$campo] . '</strong></center></td>';

                            $casos = $modelNotas->toma_casos($campo, $alumno->id, $periodoCodigo);

                            echo '<td class="centrarTexto">' . $casos['total'] . '</td>';

                            if ($casos['total'] <= 2 && $casos['total'] > 0) {
                                $responsable = 'MAESTRO';
                                $color = "#F0FAB8";
                            } elseif ($casos['total'] > 2 && $casos['total'] <= 4) {
                                $responsable = 'TUTOR';
                                $color = "#FA9E2F";
                            } elseif ($casos['total'] > 4 && $casos['total'] <= 6) {
                                $responsable = 'COORDINADOR';
                                $color = "#FB680F";
                            } elseif ($casos['total'] > 6) {
                                $responsable = 'RECTOR';
                                $color = "#FC4404";
                            } else {
                                $responsable = '';
                                $color = "";
                            }

                            echo '<td bgcolor="' . $color . '">' . $responsable . '</td>';

                            echo '</tr>';
                        }
                        ?>
                        <tr>
                            <td colspan="2" bgcolor="#ECEAEA"><strong>PROMEDIOS:</strong></td>
                            <?php
                            foreach ($modelMaterias as $materia) {
//                                echo '<td>'.$materia['id'].'</td>';
                                $notas = $modelNotas->toma_promedio_clase($campo, $materia['id']);
                                echo '<td bgcolor="#ECEAEA"><strong>' . $notas['nota'] . '</strong></td>';
                            }
                            ?>
                        </tr>

                        <tr>
                            <td colspan="2" bgcolor="#ECEAEA"><strong>TOTAL ESTUDIANTES:</strong></td>
                            <?php
                            foreach ($modelMaterias as $materia) {
                                $notas = $modelNotas->toma_total_alumnos_clase($materia['id'],$modelParalelo->id);
                                echo '<td bgcolor="#ECEAEA"><strong>' . $notas['total'] . '</strong></td>';
                            }
                            ?>
                        </tr>

                        <tr>
                            <td colspan="2" bgcolor="#FBE7E0"><strong>CASOS BAJOS < (<?= $modelBajos->valor ?>):</strong></td>
                            <?php
                            foreach ($modelMaterias as $materia) {
                                $notas = $modelNotas->toma_casos_bajos_clase($materia['id'], $campo);
                                echo '<td bgcolor="#FBE7E0"><strong>' . $notas['total'] . '</strong></td>';
                            }
                            ?>
                        </tr>

                        <tr>
                            <td colspan="2" bgcolor="#FBE7E0"><strong>CASOS BAJOS (%):</strong></td>
                            <?php
                            foreach ($modelMaterias as $materia) {
                                $notas = $modelNotas->toma_casos_bajos_porcentaje_clase($materia['id'], $campo);
                                echo '<td bgcolor="#FBE7E0"><strong>' . number_format($notas['total'], 2) . '%' . '</strong></td>';
                            }
                            ?>
                        </tr>

                        <tr>
                            
                            <td colspan="2" bgcolor="#E2F791"><strong>CASOS ALTOS > = (<?= $modelAltos->valor ?>):</strong></td>
                            <?php
                            foreach ($modelMaterias as $materia) {
                                $notas = $modelNotas->toma_casos_altos_clase($materia['id'], $campo);
                                echo '<td bgcolor="#E2F791"><strong>' . $notas['total'] . '</strong></td>';
                            }
                            ?>
                        </tr>

                        <tr>
                            <td colspan="2" bgcolor="#E2F791"><strong>CASOS ALTOS (%):</strong></td>
                            <?php
                            foreach ($modelMaterias as $materia) {
                                $notas = $modelNotas->toma_casos_altos_porcentaje_clase($materia['id'], $campo);
                                echo '<td bgcolor="#E2F791"><strong>' . number_format($notas['total'], 2) . '%' . '</strong></td>';
                            }
                            ?>
                        </tr>

                    </tbody>
                </table>
            </div> 
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">DETALLE DE ACTIVIDADES OBTENIDAS EN <?= strtoupper($campo) ?></h4>
                </div>
                <div class="modal-body">
                    
                    <div id="detalleNotas"></div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


</div>

<script>
//    function verificaNotas(alumno,clase,campo){
//        
//        //console.log(alumno);
//        console.log(campo);
//        $("#detalleNotas").html('ola k ase');
//        
//        
//        
//        
//    }
    
    
    function verificaNotas(alumno,clase,campo,nota)
    {
        
        //console.log(nota);
        
        var url = "<?= Url::to(['detallenota']) ?>";
        //console.log(url);
        var parametros = {
            "alumno": alumno,
            "clase": clase,
            "campo": campo,
            "nota": nota
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#detalleNotas").html(response);

            }
        });
    }
    
</script>