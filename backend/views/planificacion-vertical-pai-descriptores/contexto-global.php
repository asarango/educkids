<?php

use yii\helpers\Html;
use yii\grid\GridView;

// echo '<pre>';
//    print_r($conceptosClaveDisponibles);
//  print_r($contextoGlobalSeleccionados);
//  die();
// print_r($contextoGlobalDisponibles);
$condicionClass = new backend\models\helpers\Condiciones;
$estado = $bloqueUnidad->planCabecera->estado;
$isOpen = $bloqueUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado,$isOpen,$bloqueUnidad->settings_status);
?>


<div class="row text-center" style="margin-top:15px;">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style=" background-color: #eee">
        <h4 style="text-align:center">DISPONIBLES</h4>
        <hr>
        <?php
        if ($condicion == false) {
            ?>
            <h6>La planificación está <?= $estado ?></h6>
            <?php
        } else {
            ?>
            <!-- Aqui se muestran los conceptos -->
            <div id="global">
                <table class="table table-hover my-text-medium">
                    <thead>
                        <tr>
                            <th scope="col" style="width:10px" >CONTENIDO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($contextoGlobalDisponibles as $disponibles) {
                            ?>
                            <tr>
                                <td style="width:30px; text-align:justify">
                                    <?=
                                    Html::a(
                                            '<strong>' . $disponibles['contenido_es'] . '</strong>',
                                            ['asignar-contenido', 'plan_unidad_id' => $bloqueUnidad->id,
                                                'tipo' => $disponibles['tipo'],
                                                'contenido' => $disponibles['contenido_es'],
                                                'pestana' => 'contexto_global'],
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
        <!-- Tabla que muestra conceptos seleccionados -->
        <div>
            <table class="table table-hover my-text-medium">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col" style="text-align:center">CONTENIDO</th>
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
                    foreach ($contextoGlobalSeleccionados as $seleccionado) {
                        ?>
                        <tr>
                            <td><?= $contador ?></td>
                            <td><?= $seleccionado['contenido'] ?></td>
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
                                            ['quitar-contenido', 'id' => $seleccionado['id'],
                                                'pestana' => 'contexto_global'
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