<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;
$modelLibreta = backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'publicalib'])->one();

$periodoId = Yii::$app->user->identity->periodo_id;
$sentenciasNxx = new \backend\models\SentenciasNotasDefinitivasAlumno($modelAlumno->id, $periodoId, $modelCurso->id);

$this->title = 'Educandi-Portal';
?>

<style>
    .reportes{
        color: #000000;
        align-items: center;
    }
</style>


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
                <p>
                    <?php
                    if ($modelLibreta->valor == 1) {

//                     $options = ['style' => ['color' => '#000000']];
                        $options = ['class' => ['btn btn-link']];

                        echo Html::a('<u>Informe de aprendizaje - Primer quimestre</u>', ['informeaprendizaje',
                            'alumno' => $modelAlumno->id,
                            'paralelo' => $modelCurso->id,
                            'campo' => 'q1'
                                ],
                                $options);

                        echo '<strong> | </strong>';

                        echo Html::a('<u>Informe de aprendizaje - Segundo quimestre</u>', ['informeaprendizaje',
                            'alumno' => $modelAlumno->id,
                            'paralelo' => $modelCurso->id,
                            'campo' => 'q2'
                                ],
                                $options);
                    }
                    ?>

                </p>
                <a href="#" onclick="detalleCalculo()" id="muestracalculo">Detalles de promedios...</a>
                <a href="#" onclick="ocultarCalculo()" id="ocultacalculo" style="display: none">Ocultar detalles de promedios...</a>

                <div class="row" id="calculo" style="display: none">
                    <div class="col-md-6">
                        <div class="table table-responsive">
                            <table class="table-condensed table-hover table-striped table-bordered" style="font-size: 10px" width="100%">
                                <tr>
                                    <td colspan="2"><strong>QUIMESTRE 1</strong></td>
                                </tr>

                                <tr>
                                    <td><strong>NOMENCLATURA</strong></td>
                                    <td><strong>DETALLE</strong></td>
                                </tr>

                                <tr>

                                    <?php
                                    if (count($modelBloques) == 6) {
                                        echo '<td><strong>P1 / P2 / P3</strong></td>';
                                        echo '<td>Parcial 1 / Parcial 2 / Parcial 3</td>';
                                    } else {
                                        echo '<td><strong>P1 / P2</strong></td>';
                                        echo '<td>Parcial 1 / Parcial 2</td>';
                                    }
                                    ?>

                                </tr>

                                <tr>
                                    <td><strong>PR1</strong></td>
                                    <td>Promedio de (PARCIALES)</td>
                                </tr>

                                <tr>
                                    <td><strong>PR180</strong></td>
                                    <td>el 80% de PR1</td>
                                </tr>

                                <tr>
                                    <td><strong>E1</strong></td>
                                    <td>Examen 1</td>
                                </tr>

                                <tr>
                                    <td><strong>P120</strong></td>
                                    <td>el 20% de E1</td>
                                </tr>

                                <tr>
                                    <td><strong>Q1</strong></td>
                                    <td>suma de PR180 + P120</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table table-responsive">
                            <table class="table-condensed table-hover table-striped table-bordered" style="font-size: 10px" width="100%">
                                <tr>
                                    <td colspan="2"><strong>QUIMESTRE 2</strong></td>
                                </tr>

                                <tr>
                                    <td><strong>NOMENCLATURA</strong></td>
                                    <td><strong>DETALLE</strong></td>
                                </tr>

                                <tr>
                                    <?php
                                    if (count($modelBloques) == 6) {
                                        echo '<td><strong>P4 / P5 / P6</strong></td>';
                                        echo '<td>Parcial 4 / Parcial 5 / Parcial 6</td>';
                                    } else {
                                        echo '<td><strong>P3 / P4</strong></td>';
                                        echo '<td>Parcial 3 / Parcial 4</td>';
                                    }
                                    ?>

                                </tr>

                                <tr>
                                    <td><strong>PR2</strong></td>
                                    <td>Promedio de (PARCIALES)</td>
                                </tr>

                                <tr>
                                    <td><strong>PR280</strong></td>
                                    <td>el 80% de PR2</td>
                                </tr>

                                <tr>
                                    <td><strong>E2</strong></td>
                                    <td>Examen 2</td>
                                </tr>

                                <tr>
                                    <td><strong>P220</strong></td>
                                    <td>el 20% de E2</td>
                                </tr>

                                <tr>
                                    <td><strong>Q2</strong></td>
                                    <td>suma de PR280 + P220</td>
                                </tr>

                            </table>
                        </div>
                    </div>

                    <div class="container"><p><strong>FINAL = Promedio de Q1 y Q2</strong></p></div>

                </div>

                Para visualizar detalle de parciales dar clic en Q1 y/o Q2 respectivamente

            </div>

            <!--Inicia notas-->
            <div class="tamano10P">
                <div class="table">
                    <table width="" class="table-hover table-condensed table-striped table-bordered" bgcolor="#FFFFFF">
                        <thead>
                            <tr class="">
                                <th class=""><div><span>Asignaturas</span></div></th>
                                <th class="">-</th>
                                <th>-</th>

                                <?php
                                foreach ($modelBloques as $bloq) {
                                    if ($bloq->quimestre == 'QUIMESTRE I') {

                                        echo '<th class="q1 bordeQuimestre" style="display: none">' . $bloq->abreviatura . '</th>';
                                    }
                                }
                                ?>


                                <th class="q1 bordeQuimestre" style="display: none">PR1</th>
                                <th class="q1 bordeQuimestre" style="display: none">PR180</th>
                                <th class="q1 bordeQuimestre" style="display: none">E1</th>
                                <th class="q1 bordeQuimestre" style="display: none">P120</th>
                                <th onclick="mostrarQuimestre('q1')" class="fondoQuimestre">Q1<br><p style="color: #0056b3">clic aquí...</p></th>

                                <?php
                                foreach ($modelBloques as $bloq) {
                                    if ($bloq->quimestre == 'QUIMESTRE II') {

                                        echo '<th class="q2 bordeQuimestre" style="display: none">' . $bloq->abreviatura . '</th>';
                                    }
                                }
                                ?>


                                <th class="q2 bordeQuimestre" style="display: none">PR2</th>
                                <th class="q2 bordeQuimestre" style="display: none">PR280</th>
                                <th class="q2 bordeQuimestre" style="display: none">E2</th>
                                <th class="q2 bordeQuimestre" style="display: none">P220</th>
                                <th onclick="mostrarQuimestre('q2')" class="fondoQuimestre">Q2<br><p style="color: #0056b3">clic aquí...</p></th>

                                <th>FINAL</th>






                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            foreach ($modelNotas as $nota) {

                                $notaxx = $sentenciasNxx->get_nota_materia($nota['materia_id'], $nota['grupo_id']);

//    print_r($notaxx);
//    die();

                                if ($nota['tipo'] != 'COMPORTAMIENTO' && $nota['tipo'] != 'PROYECTOS') {
                                    echo '<tr>';
                                    echo '<td class="alinearIzquierda"><strong>' . $nota['materia'] . '</strong></td>';
                                    echo '<td class="alinearIzquierda"></td>';
                                    echo '<td class="alinearIzquierda"></td>';
                                    echo '<td class="q1" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $notaxx['p1'] . ',' . $modelAlumno->id . ',' . $nota['clase_id'] . ',1)">' . $notaxx['p1'] . '</a></td>';
                                    echo '<td class="q1" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $notaxx['p2'] . ',' . $modelAlumno->id . ',' . $nota['clase_id'] . ',2)">' . $notaxx['p2'] . '</a></td>';

                                    if (count($modelBloques) == 6) {
                                        echo '<td class="q1" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $notaxx['p3'] . ',' . $modelAlumno->id . ',' . $nota['clase_id'] . ',3)">' . $notaxx['p3'] . '</a></td>';
                                    }

                                    echo '<td class="q1" style="display: none">' . $nota['pr1'] . '</td>';
                                    echo '<td class="q1" style="display: none">' . $nota['pr180'] . '</td>';
                                    echo '<td class="q1" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $notaxx['ex1'] . ',' . $modelAlumno->id . ',' . $nota['clase_id'] . ',4)">' . $notaxx['ex1'] . '</a></td>';
                                    echo '<td class="q1" style="display: none">' . $nota['ex120'] . '</td>';
                                    echo '<td class="fondoQuimestre">' . $nota['q1'] . '</td>';

                                    echo '<td class="q2" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $notaxx['p4'] . ',' . $modelAlumno->id . ',' . $nota['clase_id'] . ',5)">' . $notaxx['p4'] . '</a></td>';
                                    echo '<td class="q2" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $notaxx['p5'] . ',' . $modelAlumno->id . ',' . $nota['clase_id'] . ',6)">' . $notaxx['p5'] . '</a></td>';



                                    if ($modelCurso->course->section0->code != 'DIPLs') {
                                        if (count($modelBloques) == 6) {
                                            echo '<td class="q2" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $notaxx['p6'] . ',' . $modelAlumno->id . ',' . $nota['clase_id'] . ',7)">' . $notaxx['p6'] . '</a></td>';
                                        }


                                        echo '<td class="q2" style="display: none">' . $notaxx['pr2'] . '</td>';
                                        echo '<td class="q2" style="display: none">' . $notaxx['pr280'] . '</td>';
                                        echo '<td class="q2" style="display: none"><a href="#" data-toggle="modal" class="text-info" data-target="#exampleModal" onclick="muestraNotas(' . $notaxx['ex2'] . ',' . $modelAlumno->id . ',' . $nota['clase_id'] . ',8)">' . $notaxx['ex2'] . '</a></td>';
                                        echo '<td class="q2" style="display: none">' . $notaxx['ex220'] . '</td>';
                                        echo '<td class="fondoQuimestre">' . $notaxx['q2'] . '</td>';

                                        echo '<td>' . $notaxx['final_ano_normal'] . '</td>';
                                    } else {
                                        echo '<td class="q2" style="display: none"></td>';

                                        echo '<td class="q2" style="display: none"></td>';
                                        echo '<td class="q2" style="display: none"></td>';
                                        echo '<td class="q2" style="display: none"></td>';
                                        echo '<td class="q2" style="display: none"></td>';
                                        echo '<td class="fondoQuimestre"></td>';

                                        echo '<td></td>';
                                    }



                                    echo '</tr>';
                                }
                            }


                            /**** INICIA PROYECTOS ESCOLARES *** */
                            $notasCompProy = consulta_comportamientos_y_proyectos($modelAlumno->id, $modelCurso->id, $usuario);
                            if ($tieneProyectos > 0) {

                                $proyectos = new \backend\models\ComportamientoProyectos($modelAlumno->id, $modelCurso->id);



                                if ($tipoCalificacionProyectos == 'PROYECTOSBLOQUE') {

                                    $area = $proyectos->get_area_proyectos_bloque();

                                    echo '<tr>';
                                    echo '<td class="bordesolido tamano8"><strong>PROYECTOS ESCOLARES: </strong></td>';
                                    echo '<td class="" style="display: ">-</td>';
                                    echo '<td class="" style="display: ">-</td>';
                                    echo '<td class="q1" style="display: none"><strong>' . $area[0]['p1'] . '</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>' . $area[0]['p2'] . '</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>' . $area[0]['p3'] . '</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>' . $area[0]['pr1'] . '</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>' . $area[0]['ex1'] . '</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="fondoQuimestre"><strong>' . $area[0]['q1'] . '</strong></td>';
                                    
                                    echo '<td class="q2" style="display: none"><strong>' . $area[0]['p4'] . '</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>' . $area[0]['p5'] . '</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>' . $area[0]['p6'] . '</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>' . $area[0]['pr2'] . '</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>' . $area[0]['ex2'] . '</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="fondoQuimestre"><strong>' . $area[0]['q2'] . '</strong></td>';
                                    
                                    echo '</tr>';

                                    $materiasProy = $proyectos->consulta_materias_proyectos();

                                    foreach ($materiasProy as $proy) {
                                        echo '<tr>';
                                        echo '<td class="bordesolido tamano8">' . $proy['materia'] . '</td>';
                                        echo '<td class="" style="display: ">-</td>';
                                        echo '<td class="" style="display: ">-</td>';
                                        
                                        echo '<td class="q1" style="display: none">' . $proy['p1'] . '</td>';
                                        echo '<td class="q1" style="display: none">' . $proy['p2'] . '</td>';
                                        echo '<td class="q1" style="display: none">' . $proy['p3'] . '</td>';
                                        echo '<td class="q1" style="display: none">' . $proy['pr1'] . '</td>';
                                        echo '<td class="q1" style="display: none">-</td>';
                                        echo '<td class="q1" style="display: none">' . $proy['ex1'] . '</td>';
                                        echo '<td class="q1" style="display: none">-</td>';
                                        echo '<td class="fondoQuimestre">' . $proy['q1'] . '</td>';
                                        
                                        echo '<td class="q2" style="display: none">' . $proy['p4'] . '</td>';
                                        echo '<td class="q2" style="display: none">' . $proy['p5'] . '</td>';
                                        echo '<td class="q2" style="display: none">' . $proy['p6'] . '</td>';
                                        echo '<td class="q2" style="display: none">' . $proy['pr2'] . '</td>';
                                        echo '<td class="q2" style="display: none">-</td>';
                                        echo '<td class="q2" style="display: none">' . $proy['ex2'] . '</td>';
                                        echo '<td class="q2" style="display: none">-</td>';
                                        echo '<td class="fondoQuimestre">' . $proy['q2'] . '</td>';
                                        
                                        echo '</tr>';
                                    }
                                } elseif ($tipoCalificacionProyectos == 'PROYECTOSNORMAL') {
                                    
                                    echo '<tr>';
                                    echo '<td class="bordesolido tamano8"><strong>PROYECTOS ESCOLARES: </strong></td>';
                                    echo '<td class="" style="display: ">-</td>';
                                    echo '<td class="" style="display: ">-</td>';
                                    echo '<td class="q1" style="display: none"><strong>' . $proyectos->arrayNotasProy[0]['p1']['abreviatura'] . '</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>' . $proyectos->arrayNotasProy[0]['p2']['abreviatura'] . '</strong></td>';
                                    if (count($modelBloquesQ1) > 2) {
                                        echo '<td class="q1" style="display: none"><strong>' . $proyectos->arrayNotasProy[0]['p3']['abreviatura'] . '</strong></td>';
                                    }
                                    echo '<td class="q1" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="q1" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="fondoQuimestre"><strong>' . $proyectos->arrayNotasProy[0]['q1']['abreviatura'] . '</strong></td>';
                                    
                                    echo '<td class="q2" style="display: none"><strong>' . $proyectos->arrayNotasProy[0]['p4']['abreviatura'] . '</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>' . $proyectos->arrayNotasProy[0]['p5']['abreviatura'] . '</strong></td>';
                                    if (count($modelBloquesQ1) > 2) {
                                        echo '<td class="q2" style="display: none"><strong>' . $proyectos->arrayNotasProy[0]['p6']['abreviatura'] . '</strong></td>';
                                    }
                                    echo '<td class="q2" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="q2" style="display: none"><strong>-</strong></td>';
                                    echo '<td class="fondoQuimestre"><strong>' . $proyectos->arrayNotasProy[0]['q2']['abreviatura'] . '</strong></td>';
                                    
                                    echo '</tr>';
                                }
                            }

////// TERMINA PROYECTOS ESCOLARES //////////

                            

                            echo '<tr>';
                            echo '<td><strong>COMPORTAMIENTO</strong></td>';
                            echo '<td class="" style="">-</td>';
                            echo '<td class="" style="">-</td>';

                            $notasComportamiento = comportamiento($modelAlumno->id, $modelCurso->id);

                            echo '<td class="q1" style="display: none">' . $notasComportamiento[0]['p1'] . '</td>';
                            echo '<td class="q1" style="display: none">' . $notasComportamiento[0]['p2'] . '</td>';
                            if (count($modelBloquesQ1) > 2) {
                                echo '<td class="q1" style="display: none">' . $notasComportamiento[0]['p3'] . '</td>';
                            } else {
                                echo '<td class="q1" style="display: none">-</td>';
                            }
                            echo '<td class="q1" style="display: none">-</td>';
                            echo '<td class="q1" style="display: none">-</td>';
                            echo '<td class="q1" style="display: none">-</td>';
                            echo '<td class="q1" style="display: none">-</td>';
                            echo '<td class="fondoQuimestre">' . $notasComportamiento[0]['q1'] . '</td>';
                            
                            echo '<td class="q2" style="display: none">' . $notasComportamiento[0]['p4'] . '</td>';
                            echo '<td class="q2" style="display: none">' . $notasComportamiento[0]['p5'] . '</td>';
                            if (count($modelBloquesQ1) > 2) {
                                echo '<td class="q2" style="display: none">' . $notasComportamiento[0]['p6'] . '</td>';
                            } else {
                                echo '<td class="q2" style="display: none">-</td>';
                            }
                            echo '<td class="q2" style="display: none">-</td>';
                            echo '<td class="q2" style="display: none">-</td>';
                            echo '<td class="q2" style="display: none">-</td>';
                            echo '<td class="q2" style="display: none">-</td>';
                            echo '<td class="fondoQuimestre">' . $notasComportamiento[0]['q2'] . '</td>';

                            echo '</tr>';
                            ?>                           

                        </tbody>



                    </table>
                </div>



                <div class="alert alert-danger">
                    <strong>Este reporte no representa un reporte de calificaciones.</strong><br>
                    <strong>Es un seguimiento de notas por cada asignatura.</strong>
                </div>

            </div>

            <!--fin de notas-->

        </div>


        <!--<div class="alert alert-dark">-->
        <div class="row">
            <div class="col-lg-3" style="align-items: center">
                <?php
                if ($seccion == 'PAI') {
                    echo '<img src="imagenes/educandi/reporte.png">';
                    echo Html::a('Boletin PAI Quimestre I',
                            ['reportepai',
                                'alumno' => $modelAlumno->id,
                                'quimestre' => 'QUIMESTRE I',
                                'paralelo' => $modelCurso->id
                            ],
                            ["class" => 'reportes']);
                }
                ?>
            </div>
            <div class="col-lg-3 mx-auto">
                <?php
                if ($seccion == 'PAI') {
                    echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
                    echo Html::a('Boletin PAI Quimestre II',
                            ['reportepai',
                                'alumno' => $modelAlumno->id,
                                'quimestre' => 'QUIMESTRE II',
                                'paralelo' => $modelCurso->id
                            ],
                            ["class" => 'reportes']);
                }
                ?>
            </div>

            <div class="col-lg-3 mx-auto">

                <?php
                echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
                echo Html::a('Informe Aprendizaje',
                        ['libretas',
                            'alumno' => $modelAlumno->id,
                            'paralelo' => $modelCurso->id,
                            'quimestre' => 'q2',
                            'reporte' => 'LIBRETAQ1V1',
                        ],
                        ["class" => 'reportes']);
                ?>
            </div>
            <div class="col-lg-3 mx-auto">
                <?php
                echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
                echo Html::a('Informe Resumen Final',
                        ['reporteresumen',
                            'alumno' => $modelAlumno->id,
                            'paralelo' => $modelCurso->id
                        ],
                        ["class" => 'reportes']);
                ?>

                <?php
//                    if($modelRepoLib->valor == 2){
//                        echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
//                        echo Html::a('Reporte Final',
//                                ['reportetotal', 
//                                    'alumno' => $modelAlumno->id,
//                                    'paralelo' => $modelCurso->id
//                                ], 
//                                ["class" => 'reportes']);
//                    }elseif($modelRepoLib->valor == 3){
//                        echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
//                        echo Html::a('Reporte Final',
//                                ['reportetotal2', 
//                                    'alumno' => $modelAlumno->id,
//                                    'paralelo' => $modelCurso->id
//                                ], 
//                                ["class" => 'reportes']);
//                    }
                ?>
            </div>
        </div>
        <!--</div>-->



    </div>
</div>

<?php

function proyectos($alumnoId, $paraleloId) {
    $proyComp = new backend\models\ComportamientoProyectos($alumnoId, $paraleloId);

    $totalTieneProyectos = $proyComp->tiene_proyectos();

    if ($totalTieneProyectos == 0) {
        $proy = false;
    } else {
        $proy = $proyComp->arrayNotasProy;
    }


    return $proy;
}

function comportamiento($alumnoId, $paraleloId) {
    $proyComp = new backend\models\ComportamientoProyectos($alumnoId, $paraleloId);

    return $proyComp->arrayNotasComp;
}




function consulta_comportamientos_y_proyectos($alumnoId, $paraleloId, $usuario) {
        $con = Yii::$app->db;
        $query = "select 	usuario, paralelo_id, alumno_id, comportamiento_notaq1, comportamiento_notaq2, proyectos_notaq1, proyectos_notaq2 
                    from 	scholaris_proceso_comportamiento_y_proyectos
                    where	paralelo_id = $paraleloId
                                    and alumno_id = $alumnoId
                                    and usuario = '$usuario';";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
?>

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

    function detalleCalculo() {
        $('#muestracalculo').fadeOut(1);
        $('#ocultacalculo').fadeIn(1);
        $('#calculo').fadeIn(1);
    }

    function ocultarCalculo() {
        $('#muestracalculo').fadeIn(1);
        $('#ocultacalculo').fadeOut(1);
        $('#calculo').fadeOut(1);
    }


</script>

<?php

function comportamientoEmula($nota, $seccion) {
    $sentencias = new \backend\models\Notas();

    if (isset($nota)) {
        $nota = $nota;
    } else {
        $nota = 0;
    }

    $res = $sentencias->homologa_comportamiento($nota, $seccion);
    return $res;
}

function proyectosEmula($nota) {
    $sentencias = new \backend\models\Notas();

    if (isset($nota)) {
        $nota = $nota;
    } else {
        $nota = 0;
    }

    $res = $sentencias->homologa_cualitativas($nota);
    return $res;
}
?>