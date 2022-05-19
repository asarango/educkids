<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acciones de la actividad por estudiente';
//$this->params['breadcrumbs'][] = $this->title;
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Detalle de Actividades', ['profesor-inicio/actividades-detalle', 
                                                       "bloque_id" => $modelActividad->bloque_actividad_id,
                                                       'clase_id' => $modelActividad->paralelo_id
                                                      ]
            ); ?>
        </li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Actividad', ['actividad', "actividad" => $modelActividad->id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="scholaris-actividad-calificar" style="padding-left: 40px; padding-right: 40px">

    <h4><strong><?= Html::encode($this->title) ?></strong></h4>
    <div class="row">

        <div class="col" style="padding: 30px; background-color: #fff; box-shadow: 1px 10px 10px #999">
            
            <p> <strong>
                <?= 
                    $modelActividad->title  ." / ". $modelActividad->clase->materia->name ." / "
                    .$modelActividad->clase->curso->name.' - '. $modelActividad->clase->paralelo->name
                ?>
                </strong>
            </p>
            
            <div class="table table-responsive">
                <font size="2">
                <table class="table table-condensed table-striped table-hover">
                    <tr>
                        <th>Estudiantes</th>
                        <?php
                        if (isset($modelCriterios)) {
                            foreach ($modelCriterios as $criterio) {
                                echo '<th>' . $criterio->criterio->criterio . '</th>';
                            }
                        } else {
                            echo '<th>NOTA</th>';
                        }
                        ?>
                        <th colspan="3" class="text-center">Acción</th>
                    </tr>

                    <?php
                    foreach ($modelGrupo as $grupo) {
                        echo '<tr>';
//                            echo '<td>' . $grupo->alumno->last_name . ' ' . $grupo->alumno->first_name . ' ' . $grupo->alumno->middle_name . '</td>';
                        echo '<td>' . $grupo['last_name'] . ' ' . $grupo['first_name'] . ' ' . $grupo['middle_name'] . '</td>';

                        foreach ($modelCalificaciones as $notas) {
//                                if ($grupo->estudiante_id == $notas->idalumno) {
                            if ($grupo['alumno_id'] == $notas->idalumno) {
                                if ($estado == 'abierto') {
                                    echo '<td>'
                                    . '<input class="input" type="text" id="al' . $notas->id . '" value="' . $notas->calificacion . 
                                            '" onchange="cambiarNota(' . $notas->id . ');" onkeypress="return NumCheck(event, this)" '
                                            . 'style="width : 60px; text-align: right; border: none; border-bottom: solid 1px #ccc; background-color: #cfcfcf">'
                                    . '</td>';


                                    echo '<td align="center">' . Html::a('<i class="fa fa-ban" aria-hidden="true" style="color: red"></i>', [
                                        'anular',
                                        "actividadId" => $notas->idactividad,
//                                            'alumnoId' => $grupo->estudiante_id
                                        'alumnoId' => $grupo['alumno_id']
                                            ], ['class' => 'card-link', 'title' => 'Anular']) .
                                    '</td>';


                                    $modelArchSubidos = \backend\models\ScholarisActividadDeber::find()
                                            ->where([
//                                                    'alumno_id' => $grupo->estudiante_id,
                                                'alumno_id' => $grupo['alumno_id'],
                                                'actividad_id' => $modelActividad->id
                                            ])
                                            ->all();
                                    echo '<td>' . Html::a(' <i class="fa fa-cloud-upload" aria-hidden="true" style="color: green">'.count($modelArchSubidos).'</i>', [
                                        'verarchivos',
                                        "actividadId" => $notas->idactividad,
//                                            'alumnoId' => $grupo->estudiante_id
                                        'alumnoId' => $grupo['alumno_id']
                                            ], ['class' => 'card-link', 'title' => 'archivos subidos']) .
                                    '</td>';


                                    if ($notas->observacion) {
                                        echo '<td>' . Html::a(substr($notas->observacion, 0, 20) . '...', [
                                            'updateobservacion',
                                            "actividadId" => $notas->idactividad,
//                                        'alumnoId' => $grupo->estudiante_id
                                            'alumnoId' => $grupo['alumno_id']
                                                ], ['class' => 'text-success']) .
                                        '</td>';
                                    } else {
                                        echo '<td>' . Html::a('Ingresar Observación', [
                                            'updateobservacion',
                                            "actividadId" => $notas->idactividad,
//                                        'alumnoId' => $grupo->estudiante_id
                                            'alumnoId' => $grupo['alumno_id']
                                                ], ['class' => 'card-link']) .
                                        '</td>';
                                    }
                                } else {
                                    echo '<td>' . $notas->calificacion . '</td>';
                                }
                            }
                        }
                        echo '</tr>';
                    }
                    ?>

                </table>
                </font>
            </div>              
        </div>

    </div>

</div>

<script>
    $(function ()
    {
        $('.input').keyup(function (e) {
            if (e.keyCode == 38)//38 para arriba
                mover(e, -1);
            if (e.keyCode == 40)//40 para abajo
                mover(e, 1);
        });
    });


    function mover(event, to) {
        let list = $('input');
        let index = list.index($(event.target));
        index = Math.max(0, index + to);
        list.eq(index).focus();
    }
</script>

<script>
    document.getElementById("calificar").focus();

    function cambiarNota(id) {
        var idx = '#al' + id;
        var nota = $(idx).val();

        var minima = <?= $modelMinimo->valor ?>;
        var maxima = <?= $modelMaximo->valor ?>;

        if (nota >= minima && nota <= maxima) {
            var url = "<?= Url::to(['registra']) ?>";

            $.post(
                    url,
                    {nota: nota, notaId: id},
                    function (result) {
                        $("#res").html(result);
                    }
            );
        } else {
            alert("La calificación debe estar ente " + minima + " y " + maxima);
            location.reload();
        }

    }

    function NumCheck(e, field) {
        key = e.keyCode ? e.keyCode : e.which

        // backspace
        if (key == 8)
            return true

        // 0-9
        if (key > 47 && key < 58) {
            if (field.value == "")
                return true

            regexp = /.[0-9]{2}$/
            return !(regexp.test(field.value))
        }

        // .

        if (key == 46) {
            if (field.value == "")
                return false
            regexp = /^[0-9]+$/
            return regexp.test(field.value)
        }
        // other key

        if (key == 9)
            return true

        return false
    }
</script>