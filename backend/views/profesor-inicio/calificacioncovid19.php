<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Calificación quimestral de estudiantes: ' . $modelClase->curso->name
        . ' ' . $modelClase->paralelo->name;

$this->params['breadcrumbs'][] = ['label' => 'Detalle de clases', 'url' => ['clases']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="profesor-inicio-calificacioncovid19">

    <div class="container">


        <?php
        foreach ($modelQuimestresEmerg as $emer) {
            echo Html::a(' | ' . $emer->quimestre->nombre . ' | ', ['calificacionemergencia',
                'id' => $modelClase->id,
                'emergencia' => 'covid19',
                'quimestretipo' => $emer->id
            ]);
        }
        ?>


        <?php
        if (isset($modelAlumnos)) {
            echo '<div  class="row alert alert-info">';
            echo '<div  class="col-lg-4"></div>';
            echo '<div  class="col-lg-4">';
            echo '<div class="">ESTÁ TRABAJANDO EN EL ' . $modelTipoQui->quimestre->nombre . '</div>';
            echo '</div>';
            echo '<div  class="col-lg-4"></div>';
            echo '</div>';
        }
        ?>

        <div class="table table-responsive">

            <table class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th align="center">#</th>
                        <th>ESTUDIANTES</th>
                        
                        <th align="center">FAMILIA</th>
                        <th align="center">PORTAFOLIO</th>
                        <th align="center">CONTENIDO</th>
                        <th align="center">PRESENTACIÓN</th>
                        <th align="center">TOTAL</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $contenido = 'conte';
                    if (isset($modelAlumnos)) {
                        $i = 0;
                        foreach ($modelAlumnos as $alumno) {
                            $i++;
                            echo '<tr>';
                            echo '<td>' . $i . '</td>';
                            echo '<td>' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';
                            
                            echo '<td align="center">' . $alumno['padre'] . '</td>';
                            
//                                                        echo '<td align="center">' . $alumno['portafolio'] . '</td>';
//                            CAMBIO PARA INGRESAR NOTA AL PORTAFOLIO
                            echo '<td align="center">';
                            echo '<input type="number" value="' . $alumno['portafolio'] . '" id="portafolio' . $alumno['inscription_id'] . '" '
                            . 'onchange="cambiaportafolio(' . $alumno['inscription_id'] . ',' . $alumno['tipo_quimestre_id'] . ',\'portafolio\');">';
                            echo '</td>';
                            
                            
                            echo '<td align="center">';
                            echo '<input type="number" value="' . $alumno['contenido'] . '" id="contenido' . $alumno['inscription_id'] . '" '
                            . 'onchange="cambianota(' . $alumno['inscription_id'] . ',' . $alumno['tipo_quimestre_id'] . ',\'contenido\');">';
                            echo '</td>';
                            echo '<td align="center">';
                            echo '<input type="number" value="' . $alumno['presentacion'] . '" id="presentacion' . $alumno['inscription_id'] . '" '
                            . 'onchange="cambianota(' . $alumno['inscription_id'] . ',' . $alumno['tipo_quimestre_id'] . ',\'presentacion\');">';
                            echo '</td>';
                            echo '<td align="center">' . $alumno['total'] . '</td>';

                            echo '</tr>';
                        }
                    } else {
                        echo '<div class="alert alert-warning">Debe elegir un quimestre</div>';
                    }
                    ?>
                </tbody>

            </table>

        </div>

    </div>
</div>

<script>
    function cambianota(inscripcionId, tipoQuimestre, tipo) {
       

        var id = '#' + tipo + inscripcionId;
        var valor = $(id).val();
        var parametros = {
            "inscriptionId": inscripcionId,
            "tipoQuimestre": tipoQuimestre,
            "campo": tipo,
            "valor": valor
        };

        var url = '<?= Url::to(['actualizanota']) ?>';

        if (valor == 1 || valor == 0.75 || valor == 0.50 || valor == 0) {
            $.ajax({
                data: parametros,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (response) {
                    //$("#alumno").html(response);
                }
            });
        }else{
            alert('Valor no permitido!!! \n\n\Recuerde que sus valores son: 1  -  0.75  - 0.50  -  0\nDe acuerdo a las rúbricas entregadas por el MEC.')
        }
    }
    
    
    function cambiaportafolio(inscripcionId, tipoQuimestre, tipo) {

        var id = '#' + tipo + inscripcionId;
        var valor = $(id).val();
        var parametros = {
            "inscriptionId": inscripcionId,
            "tipoQuimestre": tipoQuimestre,
            "campo": tipo,
            "valor": valor
        };

        var url = '<?= Url::to(['actualizanota']) ?>';

        if (valor <= 7) {
            $.ajax({
                data: parametros,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (response) {
                    //$("#alumno").html(response);
                }
            });
        }else{
            alert('Valor no permitido!!! \n\n\Recuerde que sus valores son: menores a 7 y mayores a 0\nDe acuerdo a las rúbricas entregadas por el MEC.')
        }
    }
</script>