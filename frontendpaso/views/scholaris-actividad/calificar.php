<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Calificación de actividades';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Actividad', ['actividad', "actividad" => $modelActividad->id]); ?>
        </li>
        <li class="breadcrumb-item">
            <?= Html::a('Anular calificaciones', ['anularcalificaciones',"id" => $modelActividad->id], ['class' => 'card-link']) ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="scholaris-actividad-calificar">

    
    <div class="row">

        <div class="container">

            <div class="col">
                <div class="table table-responsive">
                    <font size="2">
                    <table class="table table-condensed table-hover">
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
                            <!--<th>Acción</th>-->
                        </tr>

                        <?php
                        foreach ($modelGrupo as $grupo) {
                            echo '<tr>';
//                        if ($estado == 'abierto') {
//                            echo '<td>';
//                            echo Html::a($grupo->alumno->last_name . ' ' . $grupo->alumno->first_name . ' ' . $grupo->alumno->middle_name, [
//                                'individual',
//                                "actividadId" => $modelActividad->id,
//                                'alumnoId' => $grupo->estudiante_id
//                                    ], ['class' => 'card-link']);
//                            echo '</td>';
//                        } else {
//                            echo '<td>' . $grupo->alumno->last_name . ' ' . $grupo->alumno->first_name . ' ' . $grupo->alumno->middle_name . '</td>';
//                        }
                            echo '<td>' . $grupo->alumno->last_name . ' ' . $grupo->alumno->first_name . ' ' . $grupo->alumno->middle_name . '</td>';

                            foreach ($modelCalificaciones as $notas) {
                                if ($grupo->estudiante_id == $notas->idalumno) {
                                    //echo '<td>' . $notas->criterio->criterio.'-'.$notas->calificacion . '</td>';
                                    //echo '<td>' . $notas->calificacion . '</td>';
                                    //echo '<td>'. Html::textInput("nota", '', ['id' => 'calificar', 'type' => 'number', 'style' => 'width:100%;', 'min' => $modelMinimo->valor, 'max' => $modelMaximo->valor, 'step' => "any"]).'</td>';
                                    if ($estado == 'abierto') {
                                        echo '<td>'
                                        . '<input type="text" id="al' . $notas->id . '" value="' . $notas->calificacion . '" onchange="cambiarNota(' . $notas->id . ');" onkeypress="return NumCheck(event, this)">'
                                        . '</td>';
                                    } else {
                                        echo '<td>' . $notas->calificacion . '</td>';
                                    }
                                }
                            }
//                            echo '<td>' . Html::a('Anular', [
//                                'anular',
//                                "actividadId" => $notas->idactividad,
//                                'alumnoId' => $grupo->estudiante_id
//                                    ], ['class' => 'card-link']) .
//                            '</td>';
                            echo '</tr>';
                        }
                        ?>

                    </table>
                    </font>
                </div>              
            </div>
        </div>

    </div>

</div>

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