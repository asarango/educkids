<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisCalificacionesInicialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = 'Observaciones de calificaciones de Iniciales: ' . $modelAlumno->last_name.' '.$modelAlumno->first_name.' '.$modelAlumno->middle_name
        .' / '.$modelClase->curso->name
        .' / '.$modelClase->paralelo->name
        .' / '.$modelClase->profesor->last_name.' '.$modelClase->profesor->x_first_name
        .' / '.$modelClase->materia->name
        .' / '.$modelQuimestre->nombre
;

$this->params['breadcrumbs'][] = ['label' => 'Calificaciones', 'url' => ['index1','id' => $modelClase->id]];

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <?= Html::a('Generar Reporte', ['reporteindividual','alumno'=>$modelAlumno->id, 
                                    'quimestre'=>$modelQuimestre->id,
                                    'clase' => $modelClase->id], ['class' => 'btn btn-warning']) ?>
</div>


<div class="scholaris-calificaciones-inicial-observaciones">
    <div class="table table-responsive">
        <table class="table table-hover table-condensed table-striped table-bordered">
            <tr>
                <td align="center"><strong>FECHA</strong></td>
                <td align="center"><strong>CÓDIGO</strong></td>
                <td align="center"><strong>DESTREZA</strong></td>
                <td align="center"><strong>CALIFICACIÓN</strong></td>
                <td align="center"><strong>OBSERVACIÓN</strong></td>
                
            </tr>
            
            <?php
            
            foreach ($modelDatos as $data){
                echo '<tr>';
                echo '<td align="center">'.$data['creado_fecha'].'</td>';
                echo '<td align="center">'.$data['codigo_destreza'].'</td>';
                echo '<td align="">'.$data['destreza_desagregada'].'</td>';
                echo '<td align="center">'.$data['calificacion'].'</td>';
                
                echo '<td align="">';
                echo '<textarea class="form-control" cols="80" onchange="cambiaObservacion(this,'.$data['id'].')">';
                echo $data['observacion'];
                echo '</textarea>';
                
                echo '</td>';
                echo '</tr>';
            }
            
            ?>
            
            
        </table>
    </div>
</div>

<script>
    function cambiaObservacion(obj, id){
        var url = "<?= \yii\helpers\Url::to(['cambiaobservacion']) ?>";

            var parametros = {
                "observacion": $(obj).val(),
                "id": id
            };

            $.ajax({
                data: parametros,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (response) {
                    //$("#bloque").html(response);
                }
            });
    }
</script>