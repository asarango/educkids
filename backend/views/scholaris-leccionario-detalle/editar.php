<?php

use yii\helpers\Html;
use yii\grid\GridView;

$modelHoras = new backend\models\ScholarisLeccionarioDetalleSearch();

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisLeccionarioDetalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$modelHoras = new backend\models\ScholarisLeccionarioDetalleSearch();
//$diferencia = '00:00:00';


$this->title = 'Edición de Asistencias y comportamientos: '
        . $modelDetalle->paralelo->paralelo->course->name . ' - '
        . $modelDetalle->paralelo->paralelo->name . ' / '
        . $modelDetalle->paralelo->fecha;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-leccionario-detalle-editar">


    <?php
    if ($modelDetalle->estado == 'pendiente') {
        $color = 'danger';
    } else {
        $color = 'success';
    }
    ?>

    <div class="alert alert-<?= $color ?>">
        <p>
            <strong><?= $modelDetalle->hora->sigla ?> - </strong>
            <?= $modelDetalle->clase->materia->name ?> - 
            <?= $modelDetalle->clase->profesor->last_name . ' ' . $modelDetalle->clase->profesor->x_first_name ?> - 
            <?= $modelDetalle->estado ?> 
        </p>
        <p>
            <strong>Hora de Inicio:</strong>
            <?= $modelDetalle->desde ?>
        </p>

        <?php
        if (isset($modelDetalle->asistencia_id)) {
            echo '<p>';
            echo '<strong>Hora de Registro:</strong>';
            $modelAsistencia = \backend\models\ScholarisAsistenciaProfesor::find()
                    ->where(['id' => $modelDetalle->asistencia_id])
                    ->one();
            echo $modelAsistencia->hora_ingresa;
            echo '</p>';
            echo '<p>';
            echo '<strong>Diferencia de Registro:</strong>';
            $diferencia = $modelHoras->restar_horas($modelAsistencia->hora_ingresa, $modelDetalle->desde);
            echo $diferencia;
            echo '</p>';
        } else {
            echo '<p>';
            echo '<strong>Profesor falta a esta hora de clase</strong>';
            echo '</p>';
        }
        ?>

        <?php
        ?>
        <p>            
            <?php
            if ($modelDetalle->falta == true) {
                echo '<strong>Falta Justificada:</strong>';
                if ($modelDetalle->justifica_falta == true) {
                    echo '<p>Falta se encuentra justificada</p>';
                    echo '<p>' . $modelDetalle->motivio_justificacion_falta . '</p>';
                } else {
                    echo '<p><h3>¡El profesor no ha justificado su falta!</h3></p>';
                }
            }
            ?>
        </p>

    </div>

    <hr>


    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">Justificaciones del Docente</div>
                <div class="panel-body">
                    <?php
                    if (isset($diferencia)) {
                        $diferencia = $diferencia;
                    } else {
                        $diferencia = '00:00:00';
                    }
                    profesor($modelDetalle, $diferencia)
                    ?>
                </div>                
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Novedades de Estudiantes:</div>
                <div class="panel-body">
                    <?php
                    if ($modelDetalle->asistencia_id) {
                        alumnos($modelDetalle);
                    } else {
                        echo '<h3>¡NO EXISTEN NOVEDADES CON LOS ESTUDIANTES!</h3>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

function profesor($modelDetalle, $diferencia) {
    if ($modelDetalle->justifica_falta == true && $modelDetalle->falta == true && $modelDetalle->asistencia_id == null) {
        echo '<h3>¡FALTA SE ENCUENTRA JUSTIFICADA!</h3>';
    } else if ($modelDetalle->justifica_falta == !true && $modelDetalle->falta == true) {
        echo Html::beginForm(['justificaprofesor', 'post']);
//            echo '<input type="text" class="form-control" id="" placeholder="" name="" required>';
        echo '<label class="control-label">Motivo de justificación de la falta:</label>';
        echo '<input type="hidden" name="detalle" value="' . $modelDetalle->id . '">';
        echo '<input type="hidden" name="codigo" value="0">';
        echo '<input type="hidden" name="tiempo" value="00:00:00">';
        echo '<textarea name="motivo" class="form-control" required></textarea>';
        echo '<br>';
        echo Html::submitButton('Aceptar', ['class' => 'btn btn-warning']);
        echo Html::endForm();
    } else if ($diferencia != '00:00:00' && $modelDetalle->justifica_atraso != true) {
        echo Html::beginForm(['justificaprofesor', 'post']);
//            echo '<input type="text" class="form-control" id="" placeholder="" name="" required>';            
        echo '<input type="hidden" name="detalle" value="' . $modelDetalle->id . '">';
        echo '<input type="hidden" name="codigo" value="1">';
        echo '<label class="control-label">Tiempo de justificación:</label>';
        echo '<input type="text" name="tiempo" placeholder="00:00:00" required class="form-control">';
        echo '<label class="control-label">Motivo de justificación de la falta:</label>';
        echo '<textarea name="motivo" class="form-control" required></textarea>';
        echo '<br>';
        echo Html::submitButton('Aceptar', ['class' => 'btn btn-warning']);
        echo Html::endForm();
    } else {
        echo '<h3>¡EL ATRASO SE ENCUENTRA JUSTIFICADO!</h3>';
    }
}

function alumnos($modelDetalle) {
    $modelNovedades = backend\models\ScholarisAsistenciaAlumnosNovedades::find()
            ->where(['asistencia_profesor_id' => $modelDetalle->asistencia_id])
            ->all();

    foreach ($modelNovedades as $novedad) {
        
    }
}
?>
