<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisCalificacionesInicialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recepción de archivos: ' . $modelClase->curso->name
        . ' - ' . $modelClase->paralelo->name
        . ' / ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name
        . ' / ' . $modelClase->materia->name
        . ' / Clase# ' . $modelClase->id
;

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-tarea-inicial-recibir">
    <div class="alert alert-info">
        <strong><?= $modelArchivo->titulo ?></strong>
    </div>

    <div class="container">
        <div class="table table-responsive">
            <table class="table table-hover table-striped table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ESTUDIANTE</th>
                        <th align="center">ARCHIVO</th>
                        <th align="center">OBSERVACIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($modelAlumnos as $alumno) {
                        $i++;
                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td>' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';
                        
                        echo '<td>';
                        echo Html::a($alumno['archivo'],
                                    ['descargaral', 'archivo' => $alumno['archivo']],
                                    ['class' => 'btn btn-link']);
                        
                        echo'</td>';
                        
                        echo'<td>';
                        
                        if($alumno['observacion_profesor']){
                            echo Html::a('Observación ingresada',
                                    ['updateobservacion', 'id' => $alumno['id']],
                                    ['class' => 'text-success']);
                        }else{
                            echo Html::a('Sin observación',
                                    ['updateobservacion', 'id' => $alumno['id']],
                                    ['class' => 'btn btn-link']);
                        }
                        echo'</td>';
                        
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

