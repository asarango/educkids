<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notificación: ';
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
                    <small>
                        <?= $mensaje->message->asunto.
                            ' ('. $mensaje->message->remite_usuario.' - '.$mensaje->message->created_at.')' 
                        ?>
                    </small>
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

                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Volver a notificaciones</span>',
                            ['index'],
                            ['class' => 'link']
                    );
                    ?>
                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">

                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 30px;">

                    <div class="col-lg-8 col-md-8">
                        <div class="card p-3" style="height: 100px;">
                            <h4><b>ASUNTO: <?= $mensaje->message->asunto ?></b></h4>
                            <p><b>DE: </b><?= $mensaje->message->remite_usuario ?>
                            <b> || EL: </b><?= $mensaje->message->created_at ?></p>
                        </div>                        
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <div class="card p-3" style="height: 100px;">
                            <?php 
                                if($mensaje->estado == 'leído'){
                                    echo '<i class="fas fa-glasses" style="20px; color: #65b2e8"> Su mensaje se encuentra leído</i>';
                                }elseif($mensaje->estado == 'recibido'){
                                    echo '<i class="fas fa-receipt" style="20px; color: #9e28b5"> Su mensaje se encuentra recibido</i>';
                                }elseif($mensaje->estado == 'me_gusta'){
                                    echo '<i class="fas fa-thumbs-up" style="20px; color: green"> El mensje me gusta </i>';
                                }elseif($mensaje->estado == 'no_me_gusta'){
                                    echo '<i class="fas fa-thumbs-down" style="20px; color: #ab0a3d"> El mensaje no me gusta</i>';
                                }
                            ?>
                        </div>                        
                    </div>                    

            </div>
                            
            <div class="row" style="margin-top:10px; margin-bottom: 15px;">
                <div class="col-lg-8 col-md-8">
                    <div class="card p-3">
                        <h5><b>DETALLE DE LA NOTIFICACIÓN:</b></h5>
                        <?= $mensaje->message->texto ?>
                    </div>                    
                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="card p-3">
                    <h5><b>ACCIONES DE LA NOTIFICACIÓN:</b></h5>

                    <p style="margin-top: 20px;">
                        <?php                                        
                            echo Html::a('<i class="fas fa-hourglass zoom" style="color: #0a1f8f; font-size:25px;"> Recordar</i>',['cambiar-estado', 
                                'id' => $mensaje->id,
                                'estado' => 'recordar'
                                ]
                            );
                        
                        ?>
                    </p>
                    
                    <p>
                        <?php                                        
                            echo Html::a('<i class="fas fa-thumbs-up zoom" style="color: green; font-size:25px;"> Me gusta</i>',['cambiar-estado', 
                                'id' => $mensaje->id,
                                'estado' => 'me_gusta'
                                ]
                            );
                        
                        ?>
                    </p>

                    <p>
                        <?php                                        
                            echo Html::a('<i class="fas fa-thumbs-down zoom" style="color: #ab0a3d; font-size:25px;"> No me gusta</i>',['cambiar-estado', 
                                'id' => $mensaje->id,
                                'estado' => 'no_me_gusta'
                                ]
                            );
                        
                        ?>
                    </p>

                    <p>
                        <?php                                        
                            echo Html::a('<i class="fas fa-glasses zoom" style="color: #65b2e8; font-size:25px;"> Sin reacción</i>',['cambiar-estado', 
                                'id' => $mensaje->id,
                                'estado' => 'leído'
                                ]
                            );
                        
                        ?>
                    </p>

                    </div>                    
                </div>
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