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
$this->params['breadcrumbs'][] = ['label' => 'Volver', 'url' => ['form', 'id' => $modelParalelo->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<style type="text/css">
    .tableFixHead          { overflow-y: auto; height: 400px; }
    .tableFixHead thead th { position: sticky; top: 0; }

    /* Just common table stuff. Really. */
    /*table  { border-collapse: collapse; width: 100%; }*/
/*    th, td { padding: 0px; }
    */th     { background:#eee; }

    .static {
/*        position: fixed;
        background-color: white;*/
    }


</style>


<div class="alert alert-success">
    <strong>DATOS CORRESPONDIENTES AL PARCIAL: </strong><?= $mensaje[0] ?>  
    <strong>Casos bajos menores a: </strong><?= $bajos ?> / <strong>Casos Altos mayores o igual a </strong><?= $altos ?>
</div>

<a href="<?php echo Url::to(['sabanageneral','id' => $modelParalelo->id]) ?>">| Descargar Sábana |</a>

<div class="tableFixHead">
    <table border="1">
        <thead>
            <tr>
                <th class="static">#</th>
                <th class="">Estudiante</th>
                <?php
                foreach ($modelMaterias as $materia) {
                    if ($materia['promedia'] == true) {



                        echo '<th align="center">';

                        echo Html::a('<font style="font-size:8px; color:#036714">' . $materia['materia'].'('.$materia['id'].')' . '<br>' . $materia['last_name'] . ' ' . $materia['x_first_name'] . '</font>',
                                //['sabana', 'clase' => $materia['id']], ['class' => 'btn btn-link']);
                                ['notasprofesor', 'clase' => $materia['id'],'campo' => $campo, 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']);

                        echo '</th>';
                    } else {
                        echo '<th align="center" class="rotate">';
                        echo '<div><span>';
                        echo Html::a('<font style="font-size:8px; color:#036714">*' . $materia['materia'].'('.$materia['id'].')' . '<br>' . $materia['last_name'] . ' ' . $materia['x_first_name'] . '</font>',
                                //['sabana', 'clase' => $materia['id']], ['class' => 'btn btn-link']);
                                ['notasprofesor', 'clase' => $materia['id'], 'campo' => $campo, 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']);

                        echo '</span></div>';
                        echo '</th>';
                    }
                }
                ?>
                <th><center><strong>PROMEDIO</strong></center></th>
        <th># Casos</th>
        <th>Responsable</th>
        <th>COMP</th>
        </tr>
        </thead>
        <tbody>
            <?php
            
            
            
            $i = 0;
            foreach ($modelAlumnos as $alumno) {
                $i++;
                echo '<tr>';
                echo '<td class="static">' . $i . '</td>';
//                            echo '<td>' . $alumno->last_name . ' ' . $alumno->first_name . '</td>';
                echo '<td style="font-size:8px;" width="200px;">';
//                echo Html::a('<font style="font-size:8px; color:black">' . $alumno->last_name . ' ' . $alumno->first_name . '</font>',                      
//                        ['libreta', 'alumno' => $alumno->id, 'paralelo' => $modelParalelo->id, 'campo' => $campo],
//                        ['class' => 'btn btn-link']);
                echo $alumno->last_name . ' ' . $alumno->first_name;
                echo '</td>';


                foreach ($modelMaterias as $materia) {
//                                echo '<td>'.$materia['id'].'</td>';
                    $notas = $modelNotas->toma_nota_clase($campo, $alumno->id, $materia['id']);
                    
                    isset($notas['nota']) ? $notN = $notas['nota'] : $notN = 0;
                    
                    $not = $notN;
                    
                    if($notN > 0 && $notN < $bajos){
                        $color = '#FBE7E0';
                    }else if($notN >= $altos){
                        $color = '#E2F791';
                    }else{
                        $color = '';
                    }
                    
                    
                    echo '<td bgcolor="'.$color.'" align="center">';
                    echo '<button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal" '
                        . 'onclick="verificaNotas(' . $alumno->id . ',' . $materia['id'] . ',\'' . $campo . '\',' . $not . ')">'
                        . '<font style="font-size:10px;">'
                        . $notN . '</font></button>';
                    echo '</td>';                                        
                }

                if($tipoCalificacion == 0){
                    $sentenciasAl = new backend\models\AlumnoNotasNormales();
                    $promedioAlu = $sentenciasAl->get_promedio_alumno($alumno->id, $modelParalelo->id, $usuario);
                }
                //$promedioAlu = $sentencias->get_notas_finales($alumno->id, $usuario, $malla);
//                echo '<pre>';
//                print_r($proemdioAlu);
//                die();
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
                
                
                
                /**
                 * para comportamiento
                 */
                
                $nota = toma_comportamiento($alumno->id, $modelParalelo->id, $campo);
                echo '<td bgcolor="'.$color.'" align="center">'. $nota . '</td>';  
                
                
//                foreach ($modelMateriasComp as $materia) {
//                    $notas = $modelNotas->toma_nota_clase($campo, $alumno->id, $materia['id']);
//                    $not = $notas['nota'];
//                    
//                    if($notas['nota'] < $bajos){
//                        $color = '#FBE7E0';
//                    }else if($notas['nota'] >= $altos){
//                        $color = '#E2F791';
//                    }else{
//                        $color = '';
//                    }
//                    
//                    
//                    echo '<td bgcolor="'.$color.'" align="center">';
//                    echo Html::a('<font style="font-size:10px;">xx' . $notas['nota'] . '</font>',
//                                ['informecomportamiento', 'alumno' => $alumno->id, 'campo' => $campo, 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']);
//                    echo '</td>';                                        
//                }
                

                echo '</tr>';
            }
            ?>
            <tr>
                <td colspan="2" bgcolor="#ECEAEA"><strong>PROMEDIOS:</strong></td>
                <?php
                foreach ($modelMaterias as $materia) {
//                                echo '<td>'.$materia['id'].'</td>';
                    $notas = $modelNotas->toma_promedio_clase($campo, $materia['id']);
                    echo '<td bgcolor="#ECEAEA" align="center"><strong>' . $notas['nota'] . '</strong></td>';
                }
                ?>
            </tr>

            <tr>
                <td colspan="2" bgcolor="#ECEAEA"><strong>TOTAL ESTUDIANTES:</strong></td>
                <?php
                foreach ($modelMaterias as $materia) {                                     
                    
                    $notas = $modelNotas->toma_total_alumnos_clase($materia['id'], $modelParalelo->id);
                    echo '<td bgcolor="#ECEAEA" align="center"><strong>' . $notas['total'] . '</strong></td>';
                }
                ?>
            </tr>

            <tr>
                <td colspan="2" bgcolor="#FBE7E0"><strong>CASOS BAJOS < (<?= $bajos ?>):</strong></td>
                <?php
                foreach ($modelMaterias as $materia) {
                    $notas = $modelNotas->toma_casos_bajos_clase($materia['id'], $campo, $bajos);
                    echo '<td bgcolor="#FBE7E0" align="center"><strong>' . $notas['total'] . '</strong></td>';
                }
                ?>
            </tr>

            <tr>
                <td colspan="2" bgcolor="#FBE7E0"><strong>CASOS BAJOS (%):</strong></td>
                <?php
                foreach ($modelMaterias as $materia) {
                    $notas = $modelNotas->toma_casos_bajos_porcentaje_clase($materia['id'], $campo, $bajos);
                    echo '<td bgcolor="#FBE7E0" align="center"><strong>' . number_format($notas['total'], 2) . '%' . '</strong></td>';
                }
                ?>
            </tr>
            
            <tr>
                <td colspan="2" bgcolor="#FBE7E0"><strong>ACAMPOM / OK:</strong></td>
                <?php
                foreach ($modelMaterias as $materia) {
                    $notas = $modelNotas->toma_casos_bajos_porcentaje_clase($materia['id'], $campo, $bajos);
                    if($notas['total'] >= 25){
                        echo '<td bgcolor="#FBE7E0" align="center"><strong>ACAPOM</strong></td>';
                    } else {
                        echo '<td bgcolor="#FBE7E0" align="center"><strong></strong></td>';
                    }
                    
                }
                ?>
            </tr>
            

            <tr>

                <td colspan="2" bgcolor="#E2F791"><strong>CASOS ALTOS > = (<?= $altos ?>):</strong></td>
                <?php
                foreach ($modelMaterias as $materia) {
                    $notas = $modelNotas->toma_casos_altos_clase($materia['id'], $campo, $altos);
                    echo '<td bgcolor="#E2F791" align="center"><strong>' . $notas['total'] . '</strong></td>';
                }
                ?>
            </tr>

            <tr>
                <td colspan="2" bgcolor="#E2F791"><strong>CASOS ALTOS (%):</strong></td>
                <?php
                foreach ($modelMaterias as $materia) {
                    $notas = $modelNotas->toma_casos_altos_porcentaje_clase($materia['id'], $campo, $altos);
                    echo '<td bgcolor="#E2F791" align="center"><strong>' . number_format($notas['total'], 2) . '%' . '</strong></td>';
                }
                ?>
            </tr>
        </tbody>
    </table>
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


<?php
    function toma_comportamiento($alumnoId, $paralelo, $campo){
        $proyectos = new \backend\models\ComportamientoProyectos($alumnoId, $paralelo);
        
//        print_r($proyectos->arrayNotasComp);
//        die();
        
        switch ($campo){
            case 'p1':
                $nota = $proyectos->arrayNotasComp[0]['p1'];
                break;
            case 'p2':
                $nota = $proyectos->arrayNotasComp[0]['p2'];
                break;
            case 'p3':
                $nota = $proyectos->arrayNotasComp[0]['p3'];
                break;
            case 'ex1':
                $nota = $proyectos->arrayNotasComp[0]['q1'];
                break;
            case 'q1':
                $nota = $proyectos->arrayNotasComp[0]['q1'];
                break;
            case 'p4':
                $nota = $proyectos->arrayNotasComp[0]['p4'];
                break;
            case 'p5':
                $nota = $proyectos->arrayNotasComp[0]['p5'];
                break;
            case 'p6':
                $nota = $proyectos->arrayNotasComp[0]['p6'];
                break;
            case 'ex2':
                $nota = $proyectos->arrayNotasComp[0]['q2'];
                break;
            case 'q2':
                $nota = $proyectos->arrayNotasComp[0]['q2'];
                break;
            case 'final_ano_normal':
                $nota = $proyectos->arrayNotasComp[0]['q2'];
                break;
        }
        
        return $nota;
        
    }
?>



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


    function verificaNotas(alumno, clase, campo, nota)
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
            data: parametros,
            url: url,
            type: 'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success: function (response) {
                $("#detalleNotas").html(response);

            }
        });
    }

</script>