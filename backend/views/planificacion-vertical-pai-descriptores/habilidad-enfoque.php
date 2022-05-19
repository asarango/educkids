<?php

use yii\helpers\Html;
use yii\grid\GridView;

//  echo '<pre>';
//  print_r($habilidadesDisponibles);
//  print_r($habilidadesSeleccionadas);
//  die();
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
                            <th scope="col" style="width:10px" >TIPO</th>
                            <th scope="col" style="text-align:center" >CONTENIDO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($habilidadesDisponibles as $disponible) {
                            ?>
                            <tr>
                                <td>
                                    <?=
                                    '<strong>' . $disponible['orden_titulo2'] . '.- ' . $disponible['es_titulo2'] . '</strong>'
                                    ?>
                                </td>
                                <td>
                                    <?=
                                    Html::a(
                                            '<strong>' . $disponible['es_exploracion'] . '</strong>',
                                            ['asignar-contenido', 'plan_unidad_id' => $bloqueUnidad->id,
                                                'tipo' => 'habilidad_enfoque',
                                                'contenido' => $disponible['es_exploracion'],
                                                'pestana' => 'habilidad_enfoque'
                                            ],
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
                        <th scope="col" style="text-align:center">TIPO</th>
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
                    $contador = 1;
                    foreach ($habilidadesSeleccionadas as $seleccionada) {
                        ?>
                        <tr>
                            <td><?= $contador ?></td>
                            <td><?= $seleccionada['contenido'] ?></td>
                            <td><?= 'tipo' ?></td>
                            <?php
                            if ($condicion == false) {
                                ?>
                                <?php
                            } else {
                                ?>
                                <td>
                                    <?=
                                    Html::a(
                                            '<i class="far fa-trash-alt" style="color:red"></i>',
                                            ['quitar-contenido', 'id' => $seleccionada['id'],
                                                'pestana' => 'habilidad_enfoque'
                                            ],
                                            ['class' => 'link']
                                    );
                                    ?>
                                </td>
                                <?php
                            }
                            ?>

                        </tr>
                        <?php
                        $contador = $contador + 1;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>