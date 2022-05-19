<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Calificación parcial ' . $modelBloque->name . ' / ' . $modelClase->curso->name
        . ' ' . $modelClase->paralelo->name;

$this->params['breadcrumbs'][] = ['label' => 'Detalle de clases', 'url' => ['clases']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="profesor-inicio-calificacioncovid19" style="padding-left: 40px; padding-right: 40px">

    <div class="alert-info">
        <strong>Reglas de calificación para el parcial</strong><br>
        <?= $modelBloque->calificacion->descripcion_calificacion ?>
        <br>

        <strong>Total de actividades realizadas en el parcial:</strong>
        <br>
        <?= count($totalActividades) ?>
        <hr>
        <strong>Estado del parcial: </strong><?= $estado ?>

    </div>


    <div class="table table-responsive">
        <table class="table table-hover table-condensed">
            <tr>
                <td><strong>#</strong></td>
                <td><strong>Estudiante</strong></td>
                <td><strong>Total presentados</strong></td>
                <td><strong>Portafolio</strong></td>
                <td><strong>Presentación</strong></td>
                <td><strong>Contenido</strong></td>
                <td><strong>Padre</strong></td>
                <td><strong>Total</strong></td>
            </tr>
            <?php
            $i = 0;



            foreach ($modelAlumnos as $alumno) {

                $calificacionCovid = new \backend\models\CalificacionCovidParcial($codigoCalifiacion, $alumno['id'], $periodoId);


                $modelTotalDeberes = $calificacionCovid->get_total_actividades($modelClase->id, $modelBloque->id);


                $calificacionCovid->get_notas_por_clase($modelClase->id, $modelBloque->id, $automatico, count($totalActividades), count($modelTotalDeberes));


                $i++;
                echo '<tr>';
                echo '<td>' . $i . '</td>';
                echo '<td>' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';
                echo '<td>' . count($modelTotalDeberes) . '</td>';

                if ($estado == 'abierto') {
                    echo '<td><input type="number" value="' . $calificacionCovid->notasPortafolio . '" id="portafolio' . $alumno['id'] . '"'
                    . 'onchange="cambiaportafolio(' . $alumno['id'] . ',' . $modelBloque->id . ',' . $calificacionCovid->maximoPortafolio . ',' . $modelClase->id . ',\'portafolio\',' . $automatico . ');"></td>';


                    echo '<td><input type="number" value="' . $calificacionCovid->notaPresentacion . '" id="presentacion' . $alumno['id'] . '"'
                    . 'onchange="cambiapresentacion(' . $alumno['id'] . ',' . $modelBloque->id . ',' . $calificacionCovid->maximoPortafolio . ',' . $modelClase->id . ',\'presentacion\',' . $automatico . ');"></td>';

//                    echo '<td><input type="text" name="contenido" value="'.$calificacionCovid->notaContenido.'"></td>';

                    echo '<td><input type="number" value="' . $calificacionCovid->notaContenido . '" id="contenido' . $alumno['id'] . '"'
                    . 'onchange="cambiacontenido(' . $alumno['id'] . ',' . $modelBloque->id . ',' . $calificacionCovid->maximoContenido . ',' . $modelClase->id . ',\'contenido\',' . $automatico . ');"></td>';


                    echo '<td>' . $calificacionCovid->notaPadre . '</td>';

                    echo '<td>' . $calificacionCovid->notaTotal . '</td>';
                } else {

                    echo '<td>' . $calificacionCovid->notasPortafolio . '</td>';
                    echo '<td>' . $calificacionCovid->notaPresentacion . '</td>';
                    echo '<td>' . $calificacionCovid->notaContenido . '</td>';
                    echo '<td>' . $calificacionCovid->notaPadre . '</td>';
                    echo '<td>' . $calificacionCovid->notaTotal . '</td>';
                }


                echo '</tr>';
            }
            ?>
        </table>
    </div>    
</div>


<script>
//    function cambianota(inscripcionId, tipoQuimestre, tipo) {
//
//
//        var id = '#' + tipo + inscripcionId;
//        var valor = $(id).val();
//        var parametros = {
//            "inscriptionId": inscripcionId,
//            "tipoQuimestre": tipoQuimestre,
//            "campo": tipo,
//            "valor": valor
//        };
//
//        var url = '<?php // Url::to(['actualizanota'])  ?>';
//
//        if (valor == 1 || valor == 0.75 || valor == 0.50 || valor == 0) {
//            $.ajax({
//                data: parametros,
//                url: url,
//                type: 'POST',
//                beforeSend: function () {},
//                success: function (response) {
//                    //$("#alumno").html(response);
//                }
//            });
//        } else {
//            alert('Valor no permitido!!! \n\n\Recuerde que sus valores son: 1  -  0.75  - 0.50  -  0\nDe acuerdo a las rúbricas entregadas por el MEC.')
//        }
//    }


    function cambiaportafolio(alumnoId, bloqueId, valorMaximo, claseId, queCalifica, automatico) {


        if (automatico == 1) {
            var id = '#' + queCalifica + alumnoId;
            var valor = $(id).val();
            var url = '<?= Url::to(['actualizanotacovid']) ?>';

            if (valor <= valorMaximo) {
                var motivo = prompt("Motivo de cambio de nota");
                var parametros = {
                    "bloqueId": bloqueId,
                    "alumnoId": alumnoId,
                    "claseId": claseId,
                    'queCalifica': queCalifica,
                    "valor": valor,
                    "motivo": motivo,
                    "automatico": automatico
                };

                $.ajax({
                    data: parametros,
                    url: url,
                    type: 'POST',
                    beforeSend: function () {},
                    success: function (response) {
                        //$("#alumno").html(response);
                    }
                });
            } else {
                alert('Valor no permitido!!! \n\n\Recuerde que sus valores son: menores a ' + valorMaximo + ' y mayores a 0\nDe acuerdo a las rúbricas entregadas por el MEC.');
            }
        } else {
            var id = '#' + queCalifica + alumnoId;
            var valor = $(id).val();

            var parametros = {
                "bloqueId": bloqueId,
                "alumnoId": alumnoId,
                "claseId": claseId,
                'queCalifica': queCalifica,
                "valor": valor,
                "motivo": false,
                "automatico": automatico
            };

            var url = '<?= Url::to(['actualizanotacovid']) ?>';

            if (valor <= valorMaximo) {
                $.ajax({
                    data: parametros,
                    url: url,
                    type: 'POST',
                    beforeSend: function () {},
                    success: function (response) {
                        //$("#alumno").html(response);
                    }
                });
            } else {
                alert('Valor no permitido!!! \n\n\Recuerde que sus valores son: menores a ' + valorMaximo + ' y mayores a 0\nDe acuerdo a las rúbricas entregadas por el MEC.');
            }
        }


    }

    function valida_nota_contenido(valorMaximo, nota) {
       if(valorMaximo == 1){
           if(nota != 1 || nota != 0.75 || nota != 0.50 || nota != 0.25 || nota != 0){
                alert('La calificación no correcponde a estas notas: 1, 0.75, 0.50, 0.25, 0');
                exit;
            }
        }else if(valorMaximo == 5){
            
            if(nota <= 5 && nota >= 1){
//                alert('si vale');
            }else{
                alert('La calificación debe estar comprendida entre: 1 y 5');
                exit;
            }
        }
    
    }


    function cambiacontenido(alumnoId, bloqueId, valorMaximo, claseId, queCalifica, automatico) {
        var id = '#' + queCalifica + alumnoId;
        var valor = $(id).val();
        var url = '<?= Url::to(['actualizanotacovid']) ?>';
        
        valida_nota_contenido(valorMaximo, valor);
        
        
        if (automatico == 1) {


            if (valor <= valorMaximo) {
                var motivo = prompt("Motivo de cambio de nota");
                var parametros = {
                    "bloqueId": bloqueId,
                    "alumnoId": alumnoId,
                    "claseId": claseId,
                    'queCalifica': queCalifica,
                    "valor": valor,
                    "motivo": motivo,
                    "automatico": automatico
                };

                $.ajax({
                    data: parametros,
                    url: url,
                    type: 'POST',
                    beforeSend: function () {},
                    success: function (response) {
                        //$("#alumno").html(response);
                    }
                });
            } else {
                alert('Valor no permitido!!! \n\n\Recuerde que sus valores son: menores a ' + valorMaximo + ' y mayores a 0\nDe acuerdo a las rúbricas entregadas por el MEC.');
            }
        } else {
            var id = '#' + queCalifica + alumnoId;
            var valor = $(id).val();

            var parametros = {
                "bloqueId": bloqueId,
                "alumnoId": alumnoId,
                "claseId": claseId,
                'queCalifica': queCalifica,
                "valor": valor,
                "motivo": false,
                "automatico": automatico
            };

            var url = '<?= Url::to(['actualizanotacovid']) ?>';

            if (valor <= valorMaximo) {
                $.ajax({
                    data: parametros,
                    url: url,
                    type: 'POST',
                    beforeSend: function () {},
                    success: function (response) {
                        //$("#alumno").html(response);
                    }
                });
            } else {
                alert('Valor no permitido!!! \n\n\Recuerde que sus valores son: menores a ' + valorMaximo + ' y mayores a 0\nDe acuerdo a las rúbricas entregadas por el MEC.');
            }
        }


    }



    function cambiapresentacion(alumnoId, bloqueId, valorMaximo, claseId, queCalifica, automatico) {

        if (automatico == 1) {


            var id = '#' + queCalifica + alumnoId;
            var valor = $(id).val();



            var url = '<?= Url::to(['actualizanotacovid']) ?>';

            if (valor == 1 || valor == 0.75 || valor == 0.50 || valor == 0) {

                var motivo = prompt("Motivo de cambio de nota");
                var parametros = {
                    "bloqueId": bloqueId,
                    "alumnoId": alumnoId,
                    "claseId": claseId,
                    'queCalifica': queCalifica,
                    "valor": valor,
                    "motivo": motivo,
                    "automatico": automatico
                };


                $.ajax({
                    data: parametros,
                    url: url,
                    type: 'POST',
                    beforeSend: function () {},
                    success: function (response) {
                        //$("#alumno").html(response);
                    }
                });
            } else {
                alert('Valor no permitido!!! \n\n\Recuerde que sus valores son: menores a 7 y mayores a 0\nDe acuerdo a las rúbricas entregadas por el MEC.')
            }
        } else {


            var id = '#' + queCalifica + alumnoId;
            var valor = $(id).val();

            var parametros = {
                "bloqueId": bloqueId,
                "alumnoId": alumnoId,
                "claseId": claseId,
                'queCalifica': queCalifica,
                "valor": valor,
                "motivo": false,
                "automatico": automatico
            };

            var url = '<?= Url::to(['actualizanotacovid']) ?>';

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
            } else {
                alert('Valor no permitido!!! \n\n\Recuerde que sus valores son: menores a 7 y mayores a 0\nDe acuerdo a las rúbricas entregadas por el MEC.')
            }

        }
    }

</script>

