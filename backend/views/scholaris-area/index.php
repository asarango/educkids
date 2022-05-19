<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisArea;

//echo'<pre>';
//print_r($areas);

$this->title = 'Areas';
?>

<div class="scholaris-area-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/areas.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div><!-- FIN DE CABECERA -->

            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fas fa-folder-plus"></i> Crear Area</span>',
                        ['create'],
                        ['class' => 'link']
                    );
                    ?>

                    |
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <div class="row">
                <!-- #################### inicia cuerpo de card ##########################-->
                <div class="table table-responsive" style="padding: 20px;">
                    <table id="tabla" class="table table-hover table-sptriped table-condensed my-text-medium">
                        <thead>
                            <tr style="background-color: #ff9e18;">
                                <th>ID</th>
                                <th>AREA</th>
                                <th>NOMBRE MEC</th>
                                <th>SECCIÓN</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($areas as $area) {
                                ?>
                                <tr>
                                    <td><?= $area['id'] ?></td>
                                    <td><?= $area['area'] ?></td>
                                    <td><?= $area['nombre_mec'] ?></td>
                                    <td><?= $area['seccion_general'] ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button style="font-size: 10px; border-radius: 0px" id="btnGroupDrop1" type="button" class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Acciones
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li>
                                                    <?=
                                                    Html::a(
                                                            'Editar',
                                                            ['update', 'id' => $area['id']],
                                                            ['class' => 'dropdown-item', 'style' => 'font-size:10px']
                                                    )
                                                    ?>
                                                </li>

                                                <li>
                                                    <?=
                                                    Html::a(
                                                            'Eliminar',
                                                            ['delete', 'id' => $area['id']],
                                                            [
                                                                'class' => 'dropdown-item', 'style' => 'font-size:10px',
                                                                'data' => [
                                                                    'method' => 'post',
                                                                //  'params' => ['derp' => 'herp'], // <- extra level
                                                                ],
                                                            ]
                                                    )
                                                    ?>
                                                </li>

                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div><!-- ######################## fin cuerpo de card #######################-->


        </div><!-- fin de card principal -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
    $('#tabla').DataTable();
</script>