<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClase */

$this->title = 'Retirar estudiante: ' . $model->alumno->last_name . ' ' . $model->alumno->first_name . ' ' . $model->alumno->middle_name . ' ' .
        'de la clase: ' . $model->clase_id . ' ' .
        'materia: ' . $model->clase->materia->name . ' ' .
        'profesor: ' . $model->clase->profesor->last_name . ' ' . $model->clase->profesor->x_first_name
;
;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Clases', 'url' => ['scholaris-clase/index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-clase-retirar">

    <div class="alert alert-danger">
        <p>Al eliminar el estudiante, se perderán todas sus calificaciones y promedios de reportes, sin opción a recuperar.</p>
        <p><strong>Las notas que se eliminarán son las siguientes:</strong></p>
    </div>


    <div class="container">
        <div class="table table-responsive">
            <table class="table table-condensed table-hover table-striped">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Bloque</td>
                        <td>Actividad</td>
                        <td>Calificacion</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;

                    foreach ($modelActividades as $data) {
                        $i++;
                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td>' . $data['bloque'] . '</td>';
                        echo '<td>' . $data['title'] . '</td>';
                        echo '<td>' . $data['calificacion'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="alert alert-danger">
        <p><strong>Clic en Eliminar si está seguro de borrar la información:</strong></p>
        <br>
        <?php
        echo Html::beginForm(['retirar', 'post']);
        echo '<label class="control-label">Ingrese el motivo de retiro del estudiante:</label>';
        echo '<input type="text" name="motivo" class="form-control" required="">';
        echo '<input type="hidden" name="grupoId" class="form-control" value="' . $model->id . '">';

        echo'<br>';

        echo Html::submitButton(
                'Eliminar alumno de la clase',
                ['class' => 'btn btn-danger']
        );
        echo Html::endForm();
        ?>
    </div>

</div>