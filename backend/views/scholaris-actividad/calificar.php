<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividad #: '.$modelActividad->id.' | '. $modelActividad->title;

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <p>(
                        <?=
                        ' <small>' . $modelActividad->clase->materia->name .
                            ' - ' .
                            'Clase #:' . $modelActividad->clase->id .
                            ' - ' .
                            $modelActividad->clase->curso->name . ' - ' . $modelActividad->clase->paralelo->name . ' / ' .
                            $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name . ' / ' .
                            'Es calificado: ' . $modelActividad->calificado . ' / ' .
                            'Tipo de actividad: ' . $modelActividad->tipo_calificacion .
                            '</small>';
                        ?>
                        )
                    </p>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    |
                    <?php echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fas fa-chart-line"></i> DETALLE - ACTIVIDAD</span>',
                        ['actividad', "actividad" => $modelActividad->id],
                        ['class' => '', 'title' => 'DETALLE -ACTIVIDAD']
                    ); ?>
                    |
                    


                </div>
                <!-- fin de primeros botones -->
                
                <!--botones derecha-->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    |
                        <?php echo Html::a(
                        '<span class="badge rounded-pill bg-cuarto"><i class="fa fa-plus-circle" aria-hidden="true"></i> Califiación detallada</span>',
                        ['calificacion/index1', "actividad_id" => $modelActividad->id],
                        ['class' => '', 'title' => '']
                    ); ?>
                    |
                </div> <!-- Fin de botones derecha -->
            </div><!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->

            

            <!-- comienza cuerpo  -->
            
            <div class="table table-responsive">
                    <font size="2">                    
                    <table class="table table-condensed table-striped table-hover table-bordered">
                        <tr>
                            <th>Estudiantes</th>
                            <?php
                            if (isset($modelCriterios)) {
                                foreach ($modelCriterios as $criterio) {
                                    echo '<th class="text-center">' . $criterio->criterio->criterio . '</th>';
                                }
                            } else {
                                echo '<th>NOTA</th>';
                            }
                            ?>
                            <th class="text-center">TOTAL ARCHIVOS</th>
                        </tr>

                        <?php
                        foreach ($modelGrupo as $grupo) {
                            $modelArchSubidos = \backend\models\ScholarisActividadDeber::find()
                                    ->where([
//                                      'alumno_id' => $grupo->estudiante_id,
                                        'alumno_id' => $grupo['alumno_id'],
                                        'actividad_id' => $modelActividad->id
                                    ])
                                    ->all();


                            echo '<tr>';
                            echo '<td>' . $grupo['last_name'] . ' ' . $grupo['first_name'] . ' ' . $grupo['middle_name'] . '</td>';
                            
                            foreach ($modelCalificaciones as $notas) {
                                if ($grupo['alumno_id'] == $notas->idalumno) {                                   
                                    if ($estado == 'abierto') {
                                        /// SI EL BLOQUE ESTA ABIERTO
                                        /// REALIZA EL INPUT
                                        echo '<td align="center" width="10%">' //NOTAS
                                        . '<input   class="input" type="text" id="al' . $notas->id . '" value="' . $notas->calificacion . 
                                                    '" onchange="cambiarNota(' . $notas->id . ');" onkeypress="return NumCheck(event, this)" '
                                                . 'style="width : 60px; text-align: right; border: none; border-bottom: solid 1px #ccc; background-color: #cfcfcf">'
                                        . '</td>';//FIN NOTAS
                                        
                                    } else {
                                        /// SI EL BLOQUE ES CERRADO SOLO MUESTRA LA CALIFICACION SIN OPCION A MODIFICAR
                                        echo '<td>' . $notas->calificacion . '</td>';
                                    }
                                    
                                    
//                                    echo '<td>' . substr($notas->observacion, 0, 20).' ... ' . '</td>';
                                    
                                }
                            }
                            echo '<td class="text-center">' . count($modelArchSubidos) . ' archivos subidos</td>';

                            echo '</tr>';
                        }
                        ?>
                    </table>
                    </font>
                </div>    
            
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>

<script>
    $(function() 
    {
      $('.input').keyup(function(e) {
        if(e.keyCode==38)//38 para arriba
          mover(e,-1);
        if(e.keyCode==40)//40 para abajo
          mover(e,1);
      });
    });


function mover(event, to) {
   let list = $('input');
   let index = list.index($(event.target));
   index = Math.max(0,index + to);
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