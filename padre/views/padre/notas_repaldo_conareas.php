<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;

$this->title = 'Educandi-Portal';
?>


<div class="padre-notas">

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">Volver</a></li>                
                <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
                <li class="breadcrumb-item active" aria-current="page">DESEMPEÑO ACADÉMICO</li>
                <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
            </ol>
        </nav> 






        <div class="">

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Actividades en el parcial</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="detalle"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                        </div>
                    </div>
                </div>
            </div>
            <!--fin modal-->




            <div class="alert alert-warning" role="alert">
                Para mayor detalle de los promedios, click sobre Q1 o Q2.
            </div>

            <!--Inicia notas-->
            <div class="tamano10P">
                <div class="table">
                    <table width="" class="table-hover table-condensed table-striped table-bordered" bgcolor="#FFFFFF">
                        <thead>
                            <tr class="">
                                <th class=""><div><span>Asignaturas</span></div></th>
                                <th class="">Peso%</th>
                                <th>Promedia</th>
                                <th class="q1 bordeQuimestre" style="display: none">P1</th>
                                <th class="q1 bordeQuimestre" style="display: none">P2</th>
                                <th class="q1 bordeQuimestre" style="display: none">P3</th>
                                <th class="q1 bordeQuimestre" style="display: none">PR1</th>
                                <th class="q1 bordeQuimestre" style="display: none">PR180</th>
                                <th class="q1 bordeQuimestre" style="display: none">E1</th>
                                <th class="q1 bordeQuimestre" style="display: none">P120</th>
                                <th onclick="mostrarQuimestre('q1')" class="fondoQuimestre">Q1</th>

                                <th class="q2 bordeQuimestre" style="display: none">P4</th>
                                <th class="q2 bordeQuimestre" style="display: none">P5</th>
                                <th class="q2 bordeQuimestre" style="display: none">P6</th>
                                <th class="q2 bordeQuimestre" style="display: none">PR2</th>
                                <th class="q2 bordeQuimestre" style="display: none">PR280</th>
                                <th class="q2 bordeQuimestre" style="display: none">E2</th>
                                <th class="q2 bordeQuimestre" style="display: none">P220</th>
                                <th onclick="mostrarQuimestre('q2')" class="fondoQuimestre">Q2</th>

                                <th>FINAL</th>

                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            foreach ($modelAreas as $area) {
                                $nota = $sentencia1->get_nota_por_area($modelAlumno->id, $usuario, $area->id);
                                echo '<tr>';
                                if($area->tipo != 'COMPORTAMIENTO'){
                                    
                                
                                echo '<td bgcolor="#E9E9E9" class="alinearIzquierda"><strong>' . $area->area->name . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9"><strong>' . number_format($area->total_porcentaje,2) . '%</strong></td>';

                                echo ($area->promedia == 1) ? '<td bgcolor="#E9E9E9">SI</td>' : '<td bgcolor="#E9E9E9">NO</td>';

                                
                                
                                    echo '<td bgcolor="#E9E9E9" class="q1 bordeQuimestre" style="display: none"><strong>' . $nota['p1'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q1 bordeQuimestre" style="display: none"><strong>' . $nota['p2'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q1 bordeQuimestre" style="display: none"><strong>' . $nota['p3'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q1 bordeQuimestre" style="display: none"><strong>' . $nota['pr1'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q1 bordeQuimestre" style="display: none"><strong>' . $nota['pr180'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q1 bordeQuimestre" style="display: none"><strong>' . $nota['ex1'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q1 bordeQuimestre" style="display: none"><strong>' . $nota['ex120'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="fondoQuimestre"><strong>' . $nota['q1'] . '</strong></td>';

                                echo '<td bgcolor="#E9E9E9" class="q2 bordeQuimestre" style="display: none"><strong>' . $nota['p4'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q2 bordeQuimestre" style="display: none"><strong>' . $nota['p5'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q2 bordeQuimestre" style="display: none"><strong>' . $nota['p6'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q2 bordeQuimestre" style="display: none"><strong>' . $nota['pr2'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q2 bordeQuimestre" style="display: none"><strong>' . $nota['pr280'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q2 bordeQuimestre" style="display: none"><strong>' . $nota['ex2'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="q2 bordeQuimestre" style="display: none"><strong>' . $nota['ex220'] . '</strong></td>';
                                echo '<td bgcolor="#E9E9E9" class="fondoQuimestre"><strong>' . $nota['q2'] . '</strong></td>';

                                echo '<td bgcolor="#E9E9E9"><strong>' . $nota['final_ano_normal'] . '</strong></td>';

                                echo '</tr>';


                                $modelMateria = $sentencia1->get_clases_por_area($modelCurso->id, $area->id);
                                foreach ($modelMateria as $dataM) {


                                    $nota = $sentencia1->get_notas_por_materia($dataM['clase_id'], $modelAlumno->id);

//                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
//            Launch demo modal
//        </button>

                                    echo '<tr>';
                                    echo '<td class="alinearIzquierda">' . $dataM['materia'] . '</td>';
                                    echo '<td>' . $dataM['total_porcentaje'] . '%</td>';
                                    echo '<td>' . $dataM['promedia'] . '</td>';

                                    echo '<td class="q1" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $nota['p1'] . ',' . $modelAlumno->id . ',' . $dataM['clase_id'] . ',1)">' . $nota['p1'] . '</a></td>';
                                    echo '<td class="q1" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $nota['p2'] . ',' . $modelAlumno->id . ',' . $dataM['clase_id'] . ',2)">' . $nota['p2'] . '</a></td>';
                                    echo '<td class="q1" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $nota['p3'] . ',' . $modelAlumno->id . ',' . $dataM['clase_id'] . ',3)">' . $nota['p3'] . '</a></td>';
                                    echo '<td class="q1" style="display: none">' . $nota['pr1'] . '</td>';
                                    echo '<td class="q1" style="display: none">' . $nota['pr180'] . '</td>';
                                    echo '<td class="q1" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $nota['ex1'] . ',' . $modelAlumno->id . ',' . $dataM['clase_id'] . ',4)">' . $nota['ex1'] . '</a></td>';
                                    echo '<td class="q1" style="display: none">' . $nota['ex120'] . '</td>';
                                    echo '<td class="fondoQuimestre">' . $nota['q1'] . '</td>';

                                    echo '<td class="q2" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $nota['p4'] . ',' . $modelAlumno->id . ',' . $dataM['clase_id'] . ',5)">' . $nota['p4'] . '</a></td>';
                                    echo '<td class="q2" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $nota['p5'] . ',' . $modelAlumno->id . ',' . $dataM['clase_id'] . ',6)">' . $nota['p5'] . '</a></td>';
                                    echo '<td class="q2" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $nota['p6'] . ',' . $modelAlumno->id . ',' . $dataM['clase_id'] . ',7)">' . $nota['p6'] . '</a></td>';

                                    echo '<td class="q2" style="display: none">' . $nota['pr2'] . '</td>';
                                    echo '<td class="q2" style="display: none">' . $nota['pr280'] . '</td>';
                                    echo '<td class="q2" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $nota['ex2'] . ',' . $modelAlumno->id . ',' . $dataM['clase_id'] . ',8)">' . $nota['ex2'] . '</a></td>';
                                    echo '<td class="q2" style="display: none">' . $nota['ex220'] . '</td>';
                                    echo '<td class="fondoQuimestre">' . $nota['q2'] . '</td>';

                                    echo '<td>' . $nota['final_ano_normal'] . '</td>';
                                    echo '</tr>';
                                }
                                }else{/////homologacion de comportamiento
                                    echo '<tr>';
//                                    echo '<td bgcolor="#E9E9E9" class="alinearIzquierda"><strong>COMPORTAMIENTO</strong></td>';
                                    echo '<td bgcolor="#E9E9E9" class="alinearIzquierda"><strong>'.$area->area->name.'</strong></td>';
                                    echo '<td>' . $dataM['total_porcentaje'] . '%</td>';
                                    echo '<td>' . $dataM['promedia'] . '</td>';

                                    echo '<td class="q1" style="display: none">-</td>';                                    
                                    echo '<td class="q1" style="display: none">-</td>';                                    
                                    echo '<td class="q1" style="display: none">-</td>';                                    
                                    echo '<td class="q1" style="display: none">-</td>';                                    
                                    echo '<td class="q1" style="display: none">-</td>';                                    
                                    echo '<td class="q1" style="display: none">-</td>';                                    
                                    echo '<td class="q1" style="display: none">-</td>';                                    
                                    
                                    $notaComp = comportamientoEmula($nota['p3'], $modelCurso->course->section0->code);
                                    
                                    echo '<td class="fondoQuimestre">' . $notaComp . '</td>';
//                                    echo '<td class="fondoQuimestre">' . $nota['q1'] . '</td>';

                                    echo '<td class="q2" style="display: none">-</td>';
                                    echo '<td class="q2" style="display: none">-</td>';
                                    echo '<td class="q2" style="display: none">-</td>';
                                    echo '<td class="q2" style="display: none">-</td>';
                                    echo '<td class="q2" style="display: none">-</td>';
                                    echo '<td class="q2" style="display: none">-</td>';
                                    echo '<td class="q2" style="display: none">-</td>';
                                    
                                    $notaComp2 = comportamientoEmula($nota['p6'], $modelCurso->course->section0->code);
                                    
                                    echo '<td class="fondoQuimestre">' . $notaComp2 . '</td>';
//                                    echo '<td class="fondoQuimestre">' . $nota['q2'] . '</td>';

                                    echo '<td>' . $nota['final_ano_normal'] . '</td>';
                                    echo '</tr>';
                                }
                                
                                

                                
                            }
                            ?>

                        </tbody>



                    </table>
                </div>



            </div>

            <!--fin de notas-->

        </div>


        <br>
        <br>
        <br>
        <br>



    </div>
</div>

<script>
    function muestraNotas(nota, alumno, clase, ordenbloque) {
        var url = "<?= Url::to(['actividades']) ?>";
        var parametros = {
            "nota": nota,
            "alumno": alumno,
            "clase": clase,
            "orden": ordenbloque
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                $("#detalle").html(response);
            }
        });

    }



    function mostrarQuimestre(q) {
        var qui = "." + q;
        var visible = $(qui + ":visible").length;// > 0;

        if (visible == 0) {
            //$(qui).show("slide",{direction: "left"}, 1000);
            $(qui).fadeIn(1000);
//            $(qui).css('border-bottom-color', '#FF0000');
            $(qui).css('border-style', 'solid');
            $(qui).css('border-color', '#007951');
            $(qui).css('border-width', '0.2px');

        } else {
            $(qui).fadeOut(1000);
        }

    }
    

</script>

<?php
    function comportamientoEmula($nota, $seccion){
        $sentencias = new \backend\models\Notas();
        
        if(isset($nota)){
            $nota = $nota;
        }else{
            $nota = 0;
        }
        
        $res = $sentencias->homologa_comportamiento($nota, $seccion);
        return $res;
    }
?>