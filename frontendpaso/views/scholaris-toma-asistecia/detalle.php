<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Detalle de Registro de Asistencia de Estudiantes: ' . $modelAsistencia->paralelo->course->name . ' - ' . $modelAsistencia->paralelo->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-toma-asistecia-registrar">

    <div class="container">

        <div class="table table-responsive">
            <table class="table table-condensed table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Estudiante</th>
                        <th>Estado</th>
                        <th>Asiste</th>
                        <th bgcolor="#F4FAD8">Falta</th>
                        <th bgcolor="#F4FAD8">Justi.</th>
                        <th>Atraso</th>
                        <th>Justi.</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $i = 0;
                    foreach ($detalle as $det) {
                        $i++;
                        echo '<tr>';
                        echo '<td>' . $i . '</td>';
                        echo '<td>' . $det['last_name'] . ' ' . $det['first_name'] . ' ' . $det['middle_name'] . '</td>';
                        echo '<td>' . $det['inscription_state'] . '</td>';
                        echo '<td>' . $det['asiste'] . '</td>';
                        echo '<td bgcolor="#F4FAD8">';
                        if ($det['falta'] == true) {
                            echo '<input type="checkbox" onclick="registro(false,' . $det['id'] . ',\'falta\')" checked>';
                        } else {
                            echo '<input type="checkbox" onclick="registro(true,' . $det['id'] . ',\'falta\')" >';
                        }
                        echo '</td>';

                        echo '<td bgcolor="#F4FAD8">';
                        echo $det['falta_justificada'] == true ? 'J' : ''; 
                        echo '</td>';

                        echo '<td>';
                        if ($det['atraso'] == true) {
                            echo '<input type="checkbox" onclick="registro(false,' . $det['id'] . ',\'atraso\')" checked>';
                        } else {
                            echo '<input type="checkbox" onclick="registro(true,' . $det['id'] . ',\'atraso\')" >';
                        }
                        echo '</td>';

                        echo '<td>';
                        echo $det['atraso_justificado'] == true ? 'J' : '' ;
                        echo '</td>';

                        echo '<td>';
                        echo Html::a('Justificar', ['justificar', 'id' => $det['id']], ['class' => 'btn btn-link']);
                        echo '</td>';


                        echo '</tr>';
                    }
                    ?>
                </tbody>

            </table>
        </div>

    </div>
</div>


<script>

    function registro(opcionAcambiar, id, campo) {


        var url = "<?= Url::to(['detalle']) ?>";
        var parametros = {
            "id": id,
            "opcion": opcionAcambiar,
            "campo": campo
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
//                $("#paralelo").html(response);
                location.reload();

            }
        });


    }
</script>