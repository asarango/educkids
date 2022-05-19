<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisAsistenciaAlumnosNovedades;
use yii\helpers\Url;
use backend\models\PlanificacionOpciones;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registro de comportamiento estudiantil';

//extraccion parametros para faltas automaticas
$modelOp = PlanificacionOpciones::find()->where([
    'tipo' => 'FALTA_A_CLASES'
])->asArray()->all();
$idFalta = $modelOp[0]['opcion']; //id
$obsFalta = $modelOp[1]['opcion']; //obs

//revision de numero de observaciones, por materia
$total = ScholarisAsistenciaAlumnosNovedades::find()
    ->where([
        'asistencia_profesor_id' => $modelAsistencia->id
    ])
    ->all();
//conteo de alumnos con novedades
$numEstNovedades = ScholarisAsistenciaAlumnosNovedades::find()
    ->select(["grupo_id"])
    ->distinct()
    ->where([
        'asistencia_profesor_id' => $modelAsistencia->id
    ])
    ->asArray()->all();
//conteo de alumnos sin asistencia
$numEstSinAsistir = ScholarisAsistenciaAlumnosNovedades::find()
    ->select(["grupo_id"])
    ->distinct()
    ->where([
        'asistencia_profesor_id' => $modelAsistencia->id,
        'comportamiento_detalle_id' => $idFalta //id, para contar alumnos con no asistencia
    ])
    ->asArray()->all();



//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comportamiento-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-10 col-md-10">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <p>
                        <?php
                        echo $modelClase->ismAreaMateria->materia->nombre .
                            ' / ' . $modelClase->paralelo->course->name . '"' . $modelClase->paralelo->name .
                            '" / ' . $modelClase->profesor->last_name .
                            ' ' . $modelClase->profesor->x_first_name;
                        ?>
                    </p>
                </div>
            </div>
            <hr>

            <p>
                |
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>
                |
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="far fa-file"></i> Mis clases de hoy</span>',
                    ['scholaris-asistencia-profesor/index'],
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
            </p>

            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="table table-responsive" style="height: 500px; overflow-y: scroll">
                        <table class="table table-striped table-hover table-condensed table-bordered my-text-small">
                            <thead>
                                <tr style="background-color: #898b8d; text-align: center;color: #ab0a3d;">
                                    <th>#</th>
                                    <th><a href="#" onclick="show_obs()"><i class="text-center fas fa-circle" style="font-size:15px;color:#ab0a3d"></a></i></th>
                                    <th>Estudiantes</th>
                                    <th>Novedades
                                        <table>
                                            <tr>
                                                <td style="color: #ab0a3d;">#:</td>
                                                <td style="color: #0a1f8f;font-size: 13px; "><?php echo count($numEstNovedades) ?></td>
                                            </tr>
                                        </table>
                                    </th>
                                    <th>Asistencia
                                        <table>
                                            <tr>
                                                <td style="color: #ab0a3d;">#:</td>
                                                <td style="color: #0a1f8f;font-size: 13px; "><?php echo count($numEstSinAsistir) ?></td>
                                            </tr>
                                        </table>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $num = 0;
                                $divisor = 5;

                                foreach ($modelGrupo as $alumno) {
                                    $num++;
                                    $numObsAlumno = 0;
                                    $color = consulta_color($num);

                                    //******** NUMERO  *****************//
                                    echo '<tr>';
                                    echo '<td>' . $num . '</td>';

                                    //******** COLOR N.E.E  *****************//                                   
                                    if (($num % $divisor) == 0) {
                                        echo '<td class="text-center"><i class="fas fa-circle btn-n" style="font-size:15px;color:' . $color . '; display:none"></i></td>';
                                    } else {
                                        echo '<td></td>';
                                    }

                                    //******** ESTUDIANTES  *****************//
                                    echo '<td>' . $alumno['last_name'] . ' ' . $alumno['first_name'] . '</td>';
                                    $numObsAlumno = consulta_num_falta_por_alumno($total, $alumno['id']);

                                    //******** NOVEDADES  *****************//
                                    echo '<td class="text-center">';
                                    echo Html::a(
                                        $numObsAlumno, //count($total),
                                        ['detalle', "alumnoId" => $alumno['estudiante_id'], 'asistenciaId' => $modelAsistencia->id],
                                        ['class' => 'link']
                                    );
                                    echo '</td>';

                                    //******** ASISTENCIA  *****************//
                                    //verifica si existe un registro con falta.
                                    $resp = consulta_falta_automatica($total, $idFalta, $alumno['id']);
                                    if ($resp) {
                                        echo '<td class="text-center"><a href="#" onclick="borrar_falta_a_clases_estudiante(' . $alumno['estudiante_id'] . ',' . $modelAsistencia->id . ')"><span id="\'' . $alumno['estudiante_id'] . '\'" class="fas fa-user-times" style="color:red"></span></a></td>';
                                    } else {
                                        echo '<td class="text-center"><a href="#" onclick="falta_a_clases_estudiante(' . $alumno['estudiante_id'] . ',' . $modelAsistencia->id . ')"><span id="\'' . $alumno['estudiante_id'] . '\'" class="fas fa-user-check" style="color:green"></span></a></td>';
                                    }
                                    echo '</tr>';
                                } //FIN FOREACH
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="row shadow" style="margin-right: 10px">
                        <p>
                            <b><u>
                                <h4>Destreza / Actividades para la clase de hoy:</h4>
                            </u></b>
                        </p>
                        <div class="table table-responsive">
                            <table class="table table-striped table-hover table-condensed">
                                <thead>
                                    <tr style="background-color: #898b8d; color: #ab0a3d">
                                        <th>TÍTULO</th>
                                        <th>DESCRIPCIÓN</th>
                                        <th>TAREAS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($modelActividades as $data) {
                                    //$modelHora = backend\models\ScholarisHorariov2Hora::findOne($data->hora_id);
                                    echo '<tr>';
                                    echo '<td>';
                                    echo '<p><strong> ■ ' . $data->title . '</strong></p>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<p>' . $data->descripcion . '</p>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<p>' . $data->tareas . '</p>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                <tbody>
                            </table>
                        </div>
                    </div>

                    <hr><!-- para los temas dictados en la clase de hoy -->
                    <div class="row shadow" style="margin-right: 10px">
                        <p>
                            <b><u>
                                <h6>Temas Adicionales</h6>
                            </u></b>
                        </p>
                        <?php
                        echo Html::a(
                            '<i class="fas fa-file"></i> Adicionar Temas',
                            ['nuevotema', "asistenciaId" => $modelAsistencia->id],
                            ['class' => 'link']
                        );
                        ?>                        

                        <div class="table table-responsive">
                            <table class="table table-striped table-hover table-condensed">
                                <thead>
                                    <tr style="background-color: #898b8d; color: #ab0a3d">
                                        <th>TEMA</th>
                                        <th>OBSERVACIÓN</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($modelTemas as $tema) {
                                        echo '<tr>';
                                        echo '<td>' . $tema->tema . '</td>';
                                        echo '<td>' . $tema->observacion . '</td>';
                                        echo '<td>';
                                        echo Html::a('<i class="fas fa-ban"></i> Eliminar', ['quitartema', "id" => $tema->id], ['class' => 'link']);
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--/********************************************************************************************************************* */-->
<!--PROGRAMACION PHP-->
<?php

function consulta_falta_automatica($total, $idFalta, $idAlumno)
{
    //consulta si existe una falta, con el codigo 1ch, por cada alumno, para cambiar el icono de asistencia
    $resp = false;
    foreach ($total as $fila) {
        if (($fila->grupo_id) == $idAlumno and ($fila->comportamiento_detalle_id) == $idFalta) {
            $resp = true;
        }
    }
    return $resp;
}
function consulta_num_falta_por_alumno($total, $idAlumno)
{
    //consulta el numero de faltas por estudiante    
    $resp = 0;
    foreach ($total as $fila) {
        if (($fila->grupo_id) == $idAlumno) {
            $resp = $resp + 1;
        }
    }
    return $resp;
}

function consulta_color($num)
{
    $color = 'green';
    if (($num % 2) == 0) {
        $color = 'red';
    }
    if (($num % 3) == 0) {
        $color = 'orange';
    }
    return $color;
}
?>

<!--/********************************************************************************************************************* */-->
<!--PROGRAMACION JAVASCRIPT-->
<script>
    function show_obs() {
        $(".btn-n").show();
    }

    function falta_a_clases_estudiante(alumnoId, claseId) {
        var url = '<?= Url::to(['comportamiento/falta-auto-estudiante']) ?>';

        params = {
            idAlumno: alumnoId,
            idClase: claseId
        }
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function() {},
            success: function() {
                location.reload();
            }
        });
    }

    function borrar_falta_a_clases_estudiante(alumnoId, claseId) {
        var url = '<?= Url::to(['comportamiento/borrar-falta-auto-estudiante']) ?>';
        params = {
            idAlumno: alumnoId,
            idClase: claseId
        }
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function() {},
            success: function() {

                location.reload();
            }
        });
    }
</script>