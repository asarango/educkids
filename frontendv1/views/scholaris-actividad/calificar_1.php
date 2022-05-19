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
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="scholaris-actividad-calificar">

    <h3><?= Html::encode($this->title) ?></h3>
    <div class="row">


        <div class="col">

            <div class="row my-4">
                <div class="col">
                    <div class="jumbotron">
                        <?php if ($estado == 'abierto') { ?>
                            <h4>Colocar la calificación:</h4>

                            <div class="d-flex flex-wrap">

                                <!--inicio datos de actividad-->
                                <div class="card" style="width: 30rem;">
                                    <div class="card-body">
                                        <h5 class="card-title">Datos</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            <?= $modelCalificarUnitario->op_student->last_name . ' ' . $modelCalificarUnitario->op_student->first_name . ' ' . $modelCalificarUnitario->op_student->middle_name . ' ' ?>
                                        </h6>

                                        <h6 class="card-subtitle mb-2 text-muted">
                                            <?php
                                            if ($modelCalificarUnitario->actividad->tipo_calificacion == 'P') {
                                                echo '<p>' . $modelCalificarUnitario->criterio->criterio . '</p>';
                                                echo '<p>' . $modelCalificarUnitario->actividad->title . '</p>';
                                            } else {
                                                echo '<p>' . $modelCalificarUnitario->tipo_actividad->nombre_nacional . '</p>';
                                                echo '<p>' . $modelCalificarUnitario->actividad->title . '</p>';
                                            }
                                            ?>
                                        </h6>

                                        <hr>
                                        <?= Html::beginForm(['registra'], 'POST'); ?>
                                        <?php //echo Html::textInput("nota",'',['type' => 'number','style'=>'width:100%;', 'pattern' => '[0-9]{1,5}','step'=>"any"]) ?>
                                        <?php
                                        echo Html::textInput("nota", '', ['id' => 'calificar', 'type' => 'number', 'style' => 'width:100%;', 'min' => $modelMinimo->valor, 'max' => $modelMaximo->valor, 'step' => "any"]);
                                        echo Html::hiddenInput("notaId", $modelCalificarUnitario->id);
                                        ?>
                                        <div class="form-group">
                                            <?= Html::submitButton('Registrar', ['class' => 'btn btn-outline-primary']); ?>
                                        </div>
                                        <?= Html::endForm(); ?>
                                        <hr>

                                        <!--echo Html::a('Comportamiento', ['clases', "id" => $clases->id],['class' => 'card-link']);-->

                                    </div>
                                </div>
                                <!--fin datos de actividad-->




                            </div>
                        <?php
                        } else {
                            echo '<h3>Bloque cerrado no puede calificar</h3>';
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>



        <div class="col">
            <div class="table table-responsive">
                <font size="2">
                <table class="table table-condensed table-hover">
                    <tr>
                        <th>Estudiantes</th>
                        <?php
                        if(isset($modelCriterios)){
                            foreach ($modelCriterios as $criterio) {
                            echo '<th>' . $criterio->criterio->criterio . '</th>';
                        }
                        }else{
                            echo '<th>NOTA</th>';
                        }
                        
                        ?>
                        <th>Acción</th>
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
                                if($estado == 'abierto'){
                                    echo '<td>'
                                            . '<input type="text" id="al'.$notas->id.'" value="'.$notas->calificacion.'" onchange="cambiarNota('.$notas->id.');">'
                                            . '</td>';
                                }else{
                                    echo '<td>'.$notas->calificacion.'</td>';
                                }
                                
                            }
                        }
                        echo '<td>' . Html::a('Anular', [
                            'anular',
                            "actividadId" => $notas->idactividad,
                            'alumnoId' => $grupo->estudiante_id
                                ], ['class' => 'card-link']) .
                        '</td>';
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
    document.getElementById("calificar").focus();

    function cambiarNota(id){
        console.log('ola k ase');
        console.log(id);
        var idx = '#al'+id;
        var nota = $(idx).val();
        
        console.log(nota);
        
        var url = "<?= Url::to(['registra']) ?>";
        
        $.post(
                url,
                {nota:nota, notaId:id},
                function(result){
                    $("#res").html(result);
                }
            );
        
    }
</script>