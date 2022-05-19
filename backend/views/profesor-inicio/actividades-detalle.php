<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisActividad;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$seccion = $modelClase->course->section0->code;
$totalAlu = count($modelGrupo);

$sentencia = new \backend\models\SentenciasNotas();

$fecha = date('Y-m-d H:i:s');
$this->title = 'Actividades de asignatura';




$modelActividades = ScholarisActividad::find()
    ->leftJoin("scholaris_bloque_semanas", "scholaris_bloque_semanas.id = scholaris_actividad.semana_id")
    ->where([
        'paralelo_id' => $modelClase->id,
        'bloque_actividad_id' => $bloque->id
    ])
    ->orderBy(['calificado' => SORT_DESC, 'scholaris_bloque_semanas.semana_numero' => SORT_DESC])
    ->all();

?>


<div class="portal-inicio-actividades-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-12 col-md-12">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <p>
                        <?=
                        ' <small>' . $modelClase->materia->name .
                            ' - ' .
                            'Clase #:' . $modelClase->id .
                            ' - ' .
                            $modelClase->course->name .
                            ' - ' .
                            $modelClase->paralelo->name .
                            ' - ' .
                            $modelClase->profesor->x_first_name .
                            ' ' .
                            $modelClase->profesor->last_name .
                            ' ' .
                            ' / TOTAL ALUMNOS:' .
                            $totalAlu .
                            '</small>';
                        ?>
                        <?php
                        if ($fecha > $bloque->hasta) {
                            $estado = 'Cerrado';
                        } else {
                            $estado = 'Abierto';
                        }
                        ?>
                        <br>

                        <strong><?= $bloque->name ?></strong> / <strong>Desde: </strong> <?= $bloque->desde ?> /
                        <strong>Hasta: </strong><?= $bloque->hasta ?> /
                        <strong>Estado: </strong><?= $estado ?>
                    </p>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    |
                    <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="far fa-file"></i> Parciales</span>',
                        ['actividades', 'id' => $modelClase->id],
                        ['class' => 'link']
                    );
                    ?>
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Mis asignaturas</span>',
                        ['profesor-inicio/index'],
                        ['class' => 'link']
                    );
                    ?>
                    |

                </div>
                <div class="col-lg-6 col-md-6" style="text-align: right;">

                    |
                    <?php
                    if ($estado == 'Abierto') {
                        echo Html::a('<span class="badge rounded-pill" style="background-color: #009042"><i class="far fa-file"></i> Crear Actividad</span>', [
                            'scholaris-actividad/create',
                            "claseId" => $modelClase->id,
                            "bloqueId" => $bloque->id,
                        ], ['class' => 'link', 'target' => 'blank']);
                    }
                    ?>
                    |
                    <?php
                    if ($seccion == 'PAI') {
                        echo Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="far fa-file"></i> Notas Profesor PAI</span>', [
                            'reporte-notas-profesor/parcial',
                            "claseId" => $modelClase->id,
                            "bloqueId" => $bloque->id,
                        ], ['class' => 'link', 'target' => 'blank']);
                    } else {
                    ?>

                    <?php
                        echo Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="far fa-file"></i> Notas Profesor NACIONAL</span>', [
                            'reporte-notas-profesor-nac/parcial',
                            "claseId" => $modelClase->id,
                            "bloqueId" => $bloque->id,
                        ], ['class' => 'link', 'target' => 'blank']);
                    }
                    ?>
                    |
                    <?php
                    if ($estado == 'Abierto') {

                        if ($modelClase->mallaMateria->tipo == 'COMPORTAMIENTO') {
                            echo Html::a('<span class="badge rounded-pill" style="background-color: #898b8d"><i class="far fa-file"></i> Observaciones Libreta</span>', [
                                'scholaris-actividad/create',
                                "claseId" => $modelClase->id,
                                "bloqueId" => $bloque->id,
                            ], ['class' => 'link']);
                        }
                    }
                    ?>
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>


            <div class="row">
                <div class="col-lg-8 col-md-8">

                    <!-- INICIA CALIFICADOS SI -->
                    <div class="row" style="padding: 15px">
                        <div class="card" style="border: solid 1px #0a1f8f;">

                            <p style="margin-top:10px; color: #0a1f8f;"><b><u>Actividades Calificadas</u></b></p>
                            <div class="table table-responsive">
                                <table class="table table-hover table-condensed" style="font-size: 10px;">
                                    <tr style="background-color: #0a1f8f; color: #898b8d;">
                                        <th>Semana</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>#</th>
                                        <th>Insumo</th>
                                        <th>Título</th>
                                        <th>Tipo</th>
                                        <th>Califica</th>
                                        <th>Calificados</th>
                                        <th>Acción</th>
                                    </tr>
                                    <?php
                                    foreach ($modelActividades as $actividad) {
                                    ?>


                                        <?php
                                        if ($actividad->calificado == 'SI') {
                                        ?>

                                            <tr>
                                                <td>
                                                    <?php
                                                    if (isset($actividad->semana->nombre_semana)) {
                                                        echo $actividad->semana->nombre_semana;
                                                    } else {
                                                        echo '';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= $actividad->inicio ?></td>
                                                <?php
                                                $modelHora = backend\models\ScholarisHorariov2Hora::findOne($actividad->hora_id);
                                                ?>
                                                <td><?php
                                                    if ($modelHora) {
                                                        echo $modelHora->sigla;
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?></td>
                                                <td><?= $actividad->id ?></td>
                                                <td><?= $actividad->insumo->nombre_nacional ?></td>
                                                <td><?= $actividad->title ?></td>
                                                <td><?= $actividad->tipo_calificacion ?></td>
                                                <td><?= $actividad->calificado ?></td>

                                                <?php
                                                //                                                $modelToCal = $sentencia->toma_total_calificados($actividad->id, $totalAlu);
                                                $modelToCal = $sentencia->get_calificaciones($actividad->id);
                                                //                                                $modelCalif = $sentencia->toma_total_calificados_con_nulos($actividad->id);
                                                $modelCalif = $sentencia->toma_total_calificaciones($actividad->id, $totalAlu);
                                                //                                                echo '<td bgcolor="#E7FA9F">' . $modelTotal . '</td>';

                                                $modelTotal = $modelToCal;
                                                //                                                $modelTotal = $modelToCal; 

                                                if ($modelTotal == $modelCalif) {
                                                    echo '<td bgcolor="#E7FA9F">' . $modelTotal . '/' . $modelCalif . '</td>';
                                                } else {
                                                    echo '<td bgcolor="#FF0000">' . $modelTotal . '/' . $modelCalif . '</td>';
                                                }
                                                ?>

                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button style="font-size: 10px; border-radius: 0px" id="btnGroupDrop1" type="button" class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Acciones
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                            <li>
                                                                <?= Html::a(
                                                                    'Abrir',
                                                                    ['scholaris-actividad/actividad', "actividad" => $actividad->id],
                                                                    ['class' => 'dropdown-item', 'style' => 'font-size:10px', 'target' => 'blank']
                                                                )
                                                                ?>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>

                                </table>
                            </div>


                        </div>
                    </div><!-- TERMINA CALIFICADAS -->

                    <!-- inicia no calificadas -->
                    <div class="row" style="padding: 15px">
                        <div class="card" style="border: solid 1px #ff9e18;">

                            <p style="margin-top:10px; color: #ff9e18;"><b><u>Actividades No Calificadas</u></b></p>
                            <div class="table table-responsive">
                                <table class="table table-hover table-condensed" style="font-size: 10px;">
                                    <tr style="background-color: #ff9e18;">
                                        <th>Semana</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>#</th>
                                        <th>Insumo</th>
                                        <th>Título</th>
                                        <th>Tipo</th>
                                        <th>Califica</th>
                                        <th>Calificados</th>
                                        <th>Acción</th>
                                    </tr>
                                    <?php
                                    foreach ($modelActividades as $actividad) {
                                    ?>


                                        <?php
                                        if ($actividad->calificado == 'SI') {
                                        } else {
                                        ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    if (isset($actividad->semana->nombre_semana)) {
                                                        echo $actividad->semana->nombre_semana;
                                                    } else {
                                                        echo '';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= $actividad->inicio ?></td>
                                                <?php
                                                $modelHora = backend\models\ScholarisHorariov2Hora::findOne($actividad->hora_id);
                                                ?>
                                                <td><?php
                                                    if ($modelHora) {
                                                        echo $modelHora->sigla;
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?></td>
                                                <td><?= $actividad->id ?></td>
                                                <td><?= $actividad->insumo->nombre_nacional ?></td>
                                                <td><?= $actividad->title ?></td>
                                                <td><?= $actividad->tipo_calificacion ?></td>
                                                <td><?= $actividad->calificado ?></td>
                                                <td>--</td>

                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button style="font-size: 10px; border-radius: 0px" id="btnGroupDrop1" type="button" class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Acciones
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                            <li>
                                                                <?= Html::a(
                                                                    'Abrir',
                                                                    ['scholaris-actividad/actividad', "actividad" => $actividad->id],
                                                                    ['class' => 'dropdown-item', 'style' => 'font-size:10px', 'target' => 'blank']
                                                                )
                                                                ?>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </td>
                                            <?php

                                        }
                                            ?>
                                            </tr>
                                        <?php
                                    }
                                        ?>

                                </table>
                            </div>

                        </div>
                    </div><!-- TERMINA NO CALIFICADAS -->

                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="row" style="padding: 15px">
                        <div class="card p-3" style="background-color: #898b8d; color: #ccc; border: none;">


                            <!--para subir archivos-->

                            <p><b><u>TIPO DE CALIFICACIÓN</u></b></p>

                            <?php
                            if (isset($modelTipoCalificacion->valor) == 1 && $modelClase->mallaMateria->tipo == 'COMPORTAMIENTO') {

                                echo '<strong>' . $bloque->calificacion->codigo . ' </strong>' . $bloque->calificacion->descripcion_calificacion;
                            } elseif (isset($modelTipoCalificacion->valor) == 2 && $modelClase->mallaMateria->tipo != 'COMPORTAMIENTO') {
                                echo $bloque->calificacion->codigo.': ' . $bloque->calificacion->descripcion_calificacion;
                            }
                            
                            if (($bloque->codigo_tipo_calificacion != 'SINCODIGO' || $bloque->codigo_tipo_calificacion == '') && $bloque->tipo_bloque == 'PARCIAL') {
                                echo Html::a('Calificar <span class="badge">' . $totalCalificados . '</span>', [
                                    'calificacion',
                                    'claseUsada' => $bloque->calificacion->codigo,
                                    'bloque_id' => $bloque->id,
                                    'clase_id' => $modelClase->id
                                ]);
                            }
                            ?>


                        </div>
                    </div>

                    <div class="row" style="padding: 0px 15px 15px 15px;">
                        <div class="card p-3" style="background-color: #898b8d; color: #ccc; border: none;">
                            <p style="margin-top:10px;"><b><u>ARCHIVOS PUD MANUALES</u></b></p>
                            <div class="col-lg-3 col-md-3">
                                <?php
                                echo Html::a('<i class="fas fa-upload"></i>', [
                                    'scholaris-archivos-pud/index1',
                                    "claseId" => $modelClase->id,
                                    "bloqueId" => $bloque->id,
                                ], ['class' => 'link', 'title' => 'Subir pud']);
                                ?>
                            </div>
                            <div class="col-lg-9 col-md-9">
                                <?php
                                $modelArchivosPud = backend\models\ScholarisArchivosPud::find()
                                    ->where(['clase_id' => $modelClase->id, 'bloque_id' => $bloque->id])
                                    ->all();

                                foreach ($modelArchivosPud as $arch) {
                                    echo Html::a('| ' . $arch->nombre . ' |', ['scholaris-archivos-pud/descargar', "id" => $arch->id], ['class' => 'card-link']);
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- aqui termina para subir archivos -->

                </div>

            </div>
        </div>

    </div>


</div>

</div>

</div>

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
    $('#example').DataTable();
</script>