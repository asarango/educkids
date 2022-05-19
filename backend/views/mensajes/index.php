<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notificaciones';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($detalle);
//die();
?>
<div class="planificacion-aprobacion-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
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
                |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-plus-square" aria-hidden="true"></i> Crear notificación</span>',
                            ['create'],
                            ['class' => 'link']
                    );
                    ?>
                    
                    |
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 10px; margin-left: 60px">

                <!-- inicia tabla de Usuario -->

            <div class="table table-responsive">
                <table id="tabla" class="table table-hover table-striped table-condensed">
                    <thead>
                        <tr style="background-color: #ff9e18;">
                            <th>DESDE</th>
                            <th>FECHA</th>
                            <th>ASUNTO</th>
                            <th>ESTADO</th>
                            <th>RECIBIDO</th>
                            <th>LEÍDO</th>
                            <th class="text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($mensajes as $mensaje){
                                echo '<tr>';
                                echo '<td>'.$mensaje->desde.'</td>';
                                echo '<td>'.$mensaje->creado.'</td>';
                                echo '<td>'.$mensaje->asunto.'</td>';
                                echo '<td>';
                                if($mensaje->estado == 'leído'){
                                    echo '<i class="fas fa-glasses" style="color: #65b2e8;"> LEÍDO</i>';
                                }elseif($mensaje->estado == 'me_gusta'){
                                    echo '<i class="fas fa-thumbs-up" style="color: green;"> ME GUSTA</i>';
                                }elseif($mensaje->estado == 'no_me_gusta'){
                                    echo '<i class="fas fa-thumbs-down" style="color: #ab0a3d;"> NO ME GUSTA</i>';
                                }elseif($mensaje->estado == 'recordar'){
                                    echo '<i class="fas fa-hourglass" style="color: #0a1f8f;"> RECORDAR</i>';
                                }elseif($mensaje->estado == 'recibido'){
                                    echo '<i class="fas fa-receipt" style="color: #9e28b5;"> RECIBIDO</i>';
                                }
                                echo '</td>';                    
                                echo '<td>'.$mensaje->fecha_recepcion.'</td>';
                                echo '<td>'.$mensaje->fecha_lectura.'</td>';
                                echo '<td class="text-center">';
                                echo Html::a('<i class="fas fa-eye"></i>',
                                            ['detalle', 'id' => $mensaje->id],
                                            ['style' => 'color: #ab0a3d', 'title' => 'Ver Detalle']);
                                echo '</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #ff9e18;">

                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Fin tabla de Usuario -->

            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>


<script>
    

    $('#tabla').DataTable();
</script>