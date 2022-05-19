<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Hola - ' . $resUser->partner->name;
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="secretaria-index">



    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 p-5 text-center">

            <h4><?= Html::encode($this->title) ?></h4>
            <hr>



            <div class="card mb-3">

                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        
                        <?php
                            if(isset($user->avatar)){
                                $avatar = $user->avatar;
                            }else{
                                $avatar = 'no-photo.jpeg';
                            }
                        ?>

                        
                        <img src="ISM/avatars/<?= $avatar ?>" width="250px" class="img-fluid rounded-start" >

                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="card-body">
                            <h5 class="card-title text-color-s"><b><?= $resUser->partner->name ?></b></h5>
                            
                            <p class="text-color-t">
                                <strong>Tu perfil asignado es: <?= $user->rol->rol ?></strong>
                            </p>
                            
                            <p class="card-text" style="text-align: left">En esta pantalla puedes observar tu configuración de usuario, 
                                puedes actualizar la información y revisar configuraciones.
                            </p>
                            
                            <p class="card-text" style="text-align: left">
                                Si deseas consultar información de años anteriores, debers solicitar a tu Administrador de 
                                <b><?= Yii::$app->name ?></b> que te cambie de año lectivo
                            </p>
                            <p class="text-color-s"><small class=""><b>OPCIONES DE USUARIO</b></small></p>
                            <?php
                                echo Html::a('<span class="badge rounded-pill" style="background-color: #65b2e8">Cambiar contraseña</span>', ['profesor-inicio/cambiarclave']);
                                echo Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5">Cambiar avatar</span>', ['site/under']);
                                echo Html::a('<span class="badge rounded-pill" style="background-color: #ff9e18">Modificar CV</span>', ['site/under']);
                                echo Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f">Enviar mensaje</span>', ['site/under']);
                            ?>


                        </div>
                    </div>
                </div>

            </div>


        </div>

    </div>



</div>


