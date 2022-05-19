<?php

use yii\helpers\Html;
use yii\grid\GridView;

// echo '<pre>';
// print_r($conceptosRelacionadosDisponibles);
// die();
//    print_r($conceptosRelacionadosSeleccionados);
//    die();
//echo '<pre>';
//     print_r($bloqueUnidad->planCabecera->scholarisMateria);
//     die();
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
            <!-- Aqui se muestran los conceptos -->
            <div id="global">
                <h6>Esta planificación está <?= $estado ?></h6>
            </div>
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
                        foreach ($conceptosRelacionadosDisponibles as $disponible) {
                            ?>
                            <tr>

                                <?php
//                                if ($bloqueUnidad->planCabecera->scholarisMateria->language_code == 'ES') {
                                    ?>
                                    <td>
                                        <?=
                                        Html::a(
                                                '<strong>' . $disponible['contenido_es'] . '</strong>',
                                                ['asignar-contenido', 'plan_unidad_id' => $bloqueUnidad->id,
                                                    'tipo' => 'concepto_relacionado',
                                                    'contenido' => $disponible['contenido_es'],
                                                    'pestana' => 'conceptos_relacionados'],
                                                ['class' => 'link']
                                        );
                                        ?>
                                    </td>
                                    <?php
//                                }
                                ?>

                                    
                                

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
                    foreach ($conceptosRelacionadosSeleccionados as $seleccionados) {
                        ?>
                        <tr>
                            <th style="width:20px"><?= $contador ?></th>
                            <td style="text-align:center"><?= '<strong>' . $seleccionados['contenido'] . '</strong>' ?></td>
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
                                        [
                                            'quitar-contenido', 'id' => $seleccionados['id'],
                                            'pestana' => 'conceptos_relacionados'
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