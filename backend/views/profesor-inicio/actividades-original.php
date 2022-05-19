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
$this->title = 'Bloques de actividades: ' . ' <small>' . $modelClase->materia->name .
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

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['index']); ?>
        </li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Mis Asignaturas', ['clases']); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>
<div class="portal-inicio-actividades" style="padding-left: 40px; padding-right: 40px">    

        <div id="accordion">
            <?php
            foreach ($modelBloques as $bloque) {
                if ($fecha > $bloque->hasta) {
                    $estado = 'Cerrado';
                } else {
                    $estado = 'Abierto';
                }
                ?>

                <div class="">
                    <div class="card-header bg-info" id="<?= $bloque->abreviatura ?>">
                        <h5 class="mb-0">
                            <div class="row">
                                <div class="col-md-3">
                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse<?= $bloque->id ?>" aria-expanded="false" aria-controls="collapseTwo">
                                        <?= $bloque->name ?>
                                    </button>  
                                </div>   
                                <div class="col-md-6">DESDE: <small><?= $bloque->desde ?></small> HASTA: <small><?= $bloque->hasta ?></small></div>
                                <div class="col-md-3">ESTADO: <small><?= $estado ?></small></div>
                            </div>                        
                        </h5>
                    </div>
                    <div id="collapse<?= $bloque->id ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">

                            <!--para subir archivos-->
                            <div class="table table-responsive">
                                <table class="table table-hover table-condensed table-striped table-bordered">
                                    <tr>
                                        <td><strong>TIPO DE CALIFICACIÓN:</strong></td>
                                        
                                        <td>
                                            <?php
                                                if(isset($modelTipoCalificacion->valor) == 1 && $modelClase->mallaMateria->tipo == 'COMPORTAMIENTO'){
                                                    
                                                    echo '<strong>'.$bloque->calificacion->codigo.' </strong>'.$bloque->calificacion->descripcion_calificacion;                                                    
                                                }elseif(isset ($modelTipoCalificacion->valor) == 2 && $modelClase->mallaMateria->tipo != 'COMPORTAMIENTO'){
                                                    echo '<strong>'.$bloque->calificacion->codigo.' </strong>'.$bloque->calificacion->descripcion_calificacion;
                                                }
                                            ?>
                                        </td>
                                        
                                        <td>
                                            <?php 
                                                if($bloque->codigo_tipo_calificacion != 'SINCODIGO' || $bloque->codigo_tipo_calificacion==''){
                                                    echo Html::a('Calificar',['calificacion', 
                                                                         'claseUsada' => $bloque->calificacion->codigo, 
                                                                         'bloque_id' => $bloque->id,
                                                                         'clase_id' => $modelClase->id]);
                                                }
                                                ?>
                                            <strong></strong>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td width="10%">
                                            <?php
                                            echo Html::a('Subir archivos PUD', [
                                                'scholaris-archivos-pud/index1',
                                                "claseId" => $modelClase->id,
                                                "bloqueId" => $bloque->id,
                                                    ], ['class' => 'btn btn-warning btn-block']);
                                            ?>
                                        </td>

                                        <td colspan="2">
                                            <?php
                                            $modelArchivosPud = backend\models\ScholarisArchivosPud::find()
                                                    ->where(['clase_id' => $modelClase->id, 'bloque_id' => $bloque->id])
                                                    ->all();

                                            foreach ($modelArchivosPud as $arch) {

                                                echo Html::a('| ' . $arch->nombre . ' |', ['scholaris-archivos-pud/descargar', "id" => $arch->id], ['class' => 'card-link']);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <?php
                                    if ($estado == 'Abierto') {
                                        echo Html::a('Crear Actividad', [
                                            'scholaris-actividad/create',
                                            "claseId" => $modelClase->id,
                                            "bloqueId" => $bloque->id,
                                                ], ['class' => 'btn btn-success btn-block', 'target' => 'blank']);
                                    }
                                    ?>
                                </div>

                                <div class="col-md-3">
                                    <?php
                                    if ($seccion == 'PAI') {
                                        echo Html::a('Notas Profesor PAI', [
                                            'reporte-notas-profesor/parcial',
                                            "claseId" => $modelClase->id,
                                            "bloqueId" => $bloque->id,
                                                ], ['class' => 'btn btn-primary btn-block', 'target' => 'blank']);
                                    } else {
                                        ?>

                                        <?php
                                        echo Html::a('Notas Profesor NACIONAL', [
                                            'reporte-notas-profesor-nac/parcial',
                                            "claseId" => $modelClase->id,
                                            "bloqueId" => $bloque->id,
                                                ], ['class' => 'btn btn-primary btn-block', 'target' => 'blank']);
                                    }
                                    ?>
                                </div>

                                <div class="col-md-3">
                                    <?php
                                    if ($estado == 'Abierto') {

                                        if ($modelClase->mallaMateria->tipo == 'COMPORTAMIENTO') {
                                            echo Html::a('Observaciones Libreta', [
                                                'scholaris-actividad/create',
                                                "claseId" => $modelClase->id,
                                                "bloqueId" => $bloque->id,
                                                    ], ['class' => 'btn btn-default btn-block']);
                                        }
                                    }
                                    ?>
                                </div>



                            </div>         


                            <br>


                            <font size='2px'>
                            <div class="table table-responsive">
                                <table class="table table-hover table-condensed">
                                    <tr>
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
                                    $modelActividades = ScholarisActividad::find()
                                            ->leftJoin("scholaris_bloque_semanas", "scholaris_bloque_semanas.id = scholaris_actividad.semana_id")
                                            ->where([
                                                'paralelo_id' => $modelClase->id,
                                                'bloque_actividad_id' => $bloque->id
                                            ])
                                            ->orderBy(['calificado' => SORT_DESC, 'scholaris_bloque_semanas.semana_numero' => SORT_DESC])
                                            ->all();


                                    foreach ($modelActividades as $actividad) {
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
                                            if ($actividad->calificado == 'SI') {
//                                                $modelToCal = $sentencia->toma_total_calificados($actividad->id, $totalAlu);
                                                $modelToCal = $sentencia->get_calificaciones($actividad->id);
//                                                $modelCalif = $sentencia->toma_total_calificados_con_nulos($actividad->id);
                                                $modelCalif = $sentencia->toma_total_calificaciones($actividad->id, $totalAlu);
//                                                echo '<td bgcolor="#E7FA9F">' . $modelTotal . '</td>';

                                                $modelTotal = $modelToCal; 
//                                                $modelTotal = $modelToCal; 
                                                
                                                if ($modelTotal == $modelCalif) {
                                                    echo '<td bgcolor="#E7FA9F">' . $modelTotal.'/'.$modelCalif . '</td>';
                                                } else {
                                                    echo '<td bgcolor="#FF0000">' . $modelTotal.'/'.$modelCalif . '</td>';
                                                }
                                            } else {
                                                echo '<td>--</td>';
                                            }
                                            ?>

                                            <td>
                                                <?php
                                                echo Html::a('Abrir', [
                                                    'scholaris-actividad/actividad',
                                                    "actividad" => $actividad->id,
                                                        ], ['class' => 'card-link', 'target' => 'blank']);
                                                ?>
                                            </td>

                                        </tr>
                                        <?php
                                    }
                                    ?>                          

                                </table>
                            </div>
                            </font>


                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

</div>
