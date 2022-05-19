<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisAsistenciaAlumnosNovedades;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registro de comportamiento estudiantil: ' . $modelClase->materia->name .
        ' / ' . $modelClase->course->name . '"' . $modelClase->paralelo->name .
        '" / ' . $modelClase->profesor->last_name .
        ' ' . $modelClase->profesor->x_first_name;
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['/profesor-inicio/index']); ?>
        </li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Mis clases', ['/profesor-inicio/clases']); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>
<div class="comportamiento-index">



    <div class="container">

        <div class="row">
            <div class="col-md-6">

                <div class="table table-responsive">
                    <font size="2px">
                    <table class="table-striped table-hover table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estuadiantes</th>
                                <th>Novedades</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $num = 0;
                            foreach ($modelGrupo as $alumno) {
                                $num++;
                                echo '<tr>';
                                echo '<td>' . $num . '</td>';
                                echo '<td>' . $alumno->alumno->last_name . ' ' . $alumno->alumno->first_name . '</td>';

                                $total = ScholarisAsistenciaAlumnosNovedades::find()
                                        ->where([
                                            'asistencia_profesor_id' => $modelAsistencia->id,
                                            'grupo_id' => $alumno->id
                                        ])
                                        ->all();

                                echo '<td><span class="">';
                                echo Html::a(count($total), ['detalle', "alumnoId" => $alumno->estudiante_id, 'asistenciaId' => $modelAsistencia->id], ['class' => 'btn btn-primary']);
                                echo '</span></td>';


                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>  
                    </font>
                </div>            
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="panel panel-warning">
                        <div class="panel-heading">Destreza / Actividades para la clase de hoy:</div>
                        <div class="panel-body">
                            <?php
                            foreach ($modelActividades as $data) {
                                
                                //$modelHora = backend\models\ScholarisHorariov2Hora::findOne($data->hora_id);
                                
                                echo '<p><strong>(' . $data->title . ')</strong></p>';
                                echo '<hr>';
                                echo '<p>' . $data->descripcion . '</p>';
                                echo '<hr>';
                                echo '<p>' . $data->tareas . '</p>';
                            }
                            ?>
                        </div>

                        <div class="panel-footer">
                            <?php
                            //echo Html::a('Ingresar', ['/scholaris-asistencia-profesor/index'], ['class' => 'btn btn-warning']);
                            ?>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="panel panel-success">
                        <div class="panel-heading">Observaciones:</div>
                        <div class="panel-body">
                            <?php
                            foreach ($modelTemas as $tema) {
                                echo '<div class="row">';
                                echo '<div class="col-md-3">' . $tema->tema . '</div>';
                                echo '<div class="col-md-7">' . $tema->observacion . '</div>';
                                echo '<div class="col-md-2">';
                                echo Html::a('Eliminar', ['quitartema', "id" => $tema->id], ['class' => 'card-link']);
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <div class="panel-footer">
                            <?php
                            echo Html::a('Nuevo tema', ['nuevotema', "asistenciaId" => $modelAsistencia->id], ['class' => 'btn btn-link']);
                            ?>
                        </div>
                    </div>
                </div>
            </div>


            

        </div>
    </div>





</div>
