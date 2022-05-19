<?php

use common\widgets\Alert;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// $this->title = 'Actividad ' . $modelActividad->title;

?>
<div class="table table-responsive">
    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th></th>
                <?php
                foreach ($estadisticas['criterios'] as $criterio) {
                    echo '<th class="text-center" colspan="2"><h6>' . $criterio['criterio'] . '</h6></th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <?php
                foreach ($estadisticas['criterios'] as $criterio) {
                    echo '<td class="text-center" style="background-color: #ab0a3d; color:white"><h6>F</h6></td>';
                    echo '<td class="text-center" style="background-color: #ab0a3d; color:white"><h6>Σ</h6></td>';
                }
                ?>
            </tr>
            <tr>
                <?php
                foreach ($estadisticas['parciales'] as $bloque) {
                    echo '<tr>';
                    echo '<td class="text-center" style="background-color: #65b2e8;color:white">' . $bloque['abreviatura'] . '</td>';
                    foreach ($estadisticas['criterios'] as $criterio) {

                        $cantidad = get_cantidad_actividades($modelActividad->paralelo_id, $bloque['id'], $criterio['criterio']);                                                         

                        isset($cantidad[0]['total']) ? $formativas = $cantidad[0]['total'] : $formativas = 0;
                        isset($cantidad[1]['total']) ? $sumativas = $cantidad[1]['total'] : $sumativas = 0;

                        echo '<td class="text-center text-color-online" id="id" onclick="detalle_criterio()">' . $formativas . '</td>';
                        echo '<td class="text-center text-color-kids" id="id" onclick="detalle_criterio()">' . $sumativas . '</td>';
                        //echo '<td class="text-center text-color-kids">' . $sumativas . '</td>';
                    }
                    echo '</tr>';
                }

                // foreach ($estadisticas['criterios'] as $criterio) {
                //     foreach ($estadisticas['criUsados'] as $usado) {
                //         if ($usado['criterio'] == $criterio['criterio']) {
                //             isset($usado['total']) > 0 ? $totalUsados = $usado['total'] : $totalUsados = 0;
                //             echo '<th class="text-center"><h5 class="text-color-online">' . $totalUsados . '</h5></th>';
                //         } else {
                //             //echo '<th class="text-center"><h5>0</h5></th>';
                //         }
                //     }
                // }
                ?>
            </tr>
        </tbody>
    </table>
</div>

<?php

 function get_cantidad_actividades($claseId, $bloqueId, $criterio){
     $con = Yii::$app->db;
     $query = "select 	curso, paralelo, docente, materia, clase_id, bloque_id, bloque, tipo_actividad, criterio, total 
     from 	dw_estadisticas_criterios_pai
     where 	clase_id = $claseId
             and bloque_id = $bloqueId
             and criterio = '$criterio'
     order by bloque_id, criterio, tipo_actividad;";  

     $res = $con->createCommand($query)->queryAll();

     return $res;

 }

?>

<script>
    function detalle_criterio1() {
        alert('áqui el detalle');
    }
</script>