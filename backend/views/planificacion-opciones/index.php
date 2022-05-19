<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use backend\controllers\PlanificacionOpciones;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación Opciones Generales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planificacion-opciones-generales-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2"><!-- INICIO DE CABECERA -->
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
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
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> Crear opción</span>',
                            ['create'],
                            ['class' => 'link']
                    );
                    ?>
                    |
                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 15px;">

                <div class="row">
                    <!-- #################### inicia cuerpo de card ##########################-->
                    <div class="table table-responsive" style="padding: 20px;">
                        <table id="tablas" class="table table-hover table-sptriped table-condensed my-text-medium">
                            <thead>
                                <tr style="background-color: #ff9e18;">
                                    <td>ID</td>
                                    <td>TIPO</td>
                                    <td>CATEGORÍA</td>
                                    <td>DESCRIPCIÓN</td>
                                    <td>SECCIÓN</td>
                                    <td>ESTADO</td>
                                    <td colspan="2">ACCIONES</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($model as $opcion) {
                                    ?>
                                    <tr>
                                        <td><?= $opcion['id'] ?></td>
                                        <td><?= $opcion['tipo'] ?></td>
                                        <td><?= $opcion['categoria'] ?></td>
                                        <td><?= $opcion['opcion'] ?></td>
                                        <td><?= $opcion['seccion'] ?></td>
                                        <td>
                                            <?php
                                            if ($opcion['estado'] == 1) {
                                                ?>
                                            <i class="fas fa-check-circle" style="color: green" ></i>
                                                <?php
                                            } else {
                                                ?>
                                            <i class="fas fa-times-circle" style="color:#ab0a3d"></i>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="dropdown" role="group">
                                                <button style="font-size: 10px; border-radius: 0px" id="btnGroupDrop1" type="button" class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Acciones
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <li>
                                                        <?=
                                                        Html::a(
                                                                'Editar',
                                                                ['update', 'id' => $opcion['id']],
                                                                ['class' => 'dropdown-item', 'style' => 'font-size:10px']
                                                        )
                                                        ?>
                                                    </li>

                                                    <li>
                                                        
                                                        <?=
                                                        Html::a(
                                                                'Eliminar',
                                                                ['delete', 'id' => $opcion['id']],
                                                                ['class' => 'dropdown-item', 'style' => 'font-size:10px']
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
                </div>
                <!-- ######################## fin cuerpo de card #######################-->

            </div>
            <!-- fin cuerpo de card -->

        </div>
    </div>

</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
    $('#tablas').DataTable();   
</script>
