<?php

use yii\helpers\Html;
use yii\grid\GridView;

// echo '<pre>';
// print_r($criteriosSeleccionados);
$condicionClass = new backend\models\helpers\Condiciones;
$estado = $bloqueUnidad->planCabecera->estado;
$isOpen = $bloqueUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado,$isOpen,$bloqueUnidad->settings_status);
?>


<div class="row text-center" style="margin-top:15px; background-color: #eee">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <h4 style="text-align:center">DISPONIBLES</h4>
        <hr>
        <?php
        if ($condicion == false) {
            ?>
            <h6>Esta planificación está <?= $estado ?></h6>
            <?php
        } else {
            ?>
            <!-- Aqui se muestran los criterios -->
            <div id="global">
                <table class="table table-hover my-text-medium">
                    <thead>
                        <tr>
                            <th scope="col" style="width:10px" >CRITERIO</th>
                            <th scope="col" style="text-align:center" >DESCRIPCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($criteriosDisponibles as $criterio) {
                            ?>
                            <tr>
                                <th scope="row" style="text-align:center" >
                                    <?= $criterio['criterio'] ?>
                                </th>
                                <td style="text-align:justify">
                                    <!-- HREF QUE PASA DE CRITERIOS DISPONIBLES A CRITERIOS SELECCIONADOS -->
                                    <?=
                                    Html::a(
                                            '<strong>' . $criterio['criterio_detalle'] . '</strong><br>' . $criterio['descriptor_detalle'],
                                            ['asignar', 'plan_unidad_id' => $bloqueUnidad->id, 'descriptor_id' => $criterio['descriptor_id']
                                                , 'pestana' => 'objetivos_especificos'],
                                            ['class' => 'link']
                                    );
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>                  
            </div>
            <?php
        }
        ?>

    </div>

    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 border border-1 border-warning">
        <h4 style="text-align:center">SELECCIONADOS</h4>
        <hr>
        <!-- Tabla que muestra criterios seleccionados -->
        <div>
            <table class="table table-hover my-text-medium">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col" style="text-align:center">DESCRIPCIÓN</th>
                        <th scope="col" style="text-align:center">CRITERIO</th>
                        <?php
                        if ($condicion == false) {
                            ?>

                            <?php
                        } else {
                            ?>
                            <th scope="col">ACCIÓN</th>
                            <?php
                        }
                        ?>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($criteriosSeleccionados as $seleccionado) {
                        ?>
                        <tr>
                            <th scope="row" style="text-align:center"><li></th>
                    <td style="text-align:justify">
                        <?= $seleccionado['descriptor_detalle'] ?>
                    </td>
                    <td style="text-align:center">
                        <?= $seleccionado['criterio'] ?>
                    </td>
                    <?php
                    if ($condicion == false) {
                        ?>

                        <?php
                    } else {
                        ?>
                        <td>
                            <?php
                            echo Html::a(
                                    '<i class="far fa-trash-alt" style="color:red"></i>',
                                    ['quitar', 'id' => $seleccionado['id']
                                        , 'pestana' => 'objetivos_especificos'],
                                    ['class' => 'link']
                            );
                            ?>

                        </td>
                        <?php
                    }
                    ?> 
                    </tr>
                    <?php
                }
                ?>    
                </tbody>
            </table>
        </div>
    </div>
</div>