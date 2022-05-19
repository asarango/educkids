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
        <div class="card shadow col-lg-10 col-md-10">
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
            <div class="row" style="margin-top: 20px;">
                
                <div class="col-lg-4 col-md-4" style="text-align: left">
                    <?php
                      if($anterior){
                          echo Html::a('<i class="fas fa-backward"></i> '.$anterior['student'], ['index1',
                                'actividad_id' => $modelActividad->id,
                                'actual' => $anterior['student']
                              ]);
                      }
                    ?>
                </div>
                <div class="col-lg-4 col-md-4">
                    <?php
                      if($actual){
                          echo '<h6><b>'.$actual['student'].'</b></h6>';
                      }
                     //Html::a('')
                    ?>
                </div>
                <div class="col-lg-4 col-md-4" style="text-align: right">
                    <?php
                      if($siguiente){
                      
                          echo Html::a($siguiente['student'].' <i class="fas fa-forward"></i>', ['index1',
                                'actividad_id' => $modelActividad->id,
                                'actual' => $siguiente['student']
                              ]);
                      
                      }                     
                    ?>
                </div>
                
                
                
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