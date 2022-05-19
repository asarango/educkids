<?php

use yii\helpers\Html;
use kartik\grid\GridView;


$sentencia = new \backend\models\SentenciasMenu();

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permisos del perfil: ';

?>

<div class="rol-permisos">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 col-md-8">

            <!-- cabecera de pantalla -->
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/curriculum.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <b><?= $modelRol->rol ?></b>
                </div>
            </div>
            <hr>


            <div class="row">
                <div class="col-lg-10 col-md-10">
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Volver a perfiles</span>',
                        ['index'],
                        ['class' => 'link']
                    ); ?>
                    |
                </div>

                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <!-- aqui van iconos de acciones del controlador -->
                </div>
            </div>
            <!-- fin de cabecera de pantalla -->

            <!-- inicia cuerpo de pantalla -->

            <div class="row p-3">
                <div class="col-lg-5 col-md-5 my-text-small">
                    <!-- Para no asignados -->

                    <b class="text-color-s">PERMISOS ASIGNADOS</b>
                    <div class="mimenu" style="overflow-y: hidden; height: 100%;">
                        <ul class="uno">

                            <?php
                            foreach ($modelMenu as $menu) {
                                $menuId = $menu->id;
                                $menus = $sentencia->get_no_asignados($modelRol->id, $menuId);
                            ?>
                                <li><a href="#"><b><?= $menu->nombre ?></b></a>
                                    <ul class="dos">

                                        <?php
                                        foreach ($menus as $m) {

                                            // if ($comp->id == $det->comportamiento_id) {

                                            echo '<li><a href="'
                                                . yii\helpers\Url::to(['asignacion', 'id' => $m['id'], 'accion' => 'a', 'rolId' => $modelRol->id])
                                                . '"><i class="fas fa-check"> ' . $m['nombre']
                                                . '</i></a></li>';
                                           
                                        }
                                        ?>

                                    </ul>
                                </li>


                            <?php
                            }
                            ?>


                        </ul>
                    </div>

                </div>
                <div class="col-lg-2 col-md-2 my-text-small"></div>
                <!--fin de no asignado-->
                <div class="col-lg-5 col-md-5 my-text-small">
                    <!-- Para no asignados -->

                    <b class="text-color-t">PERMISOS NO ASIGNADOS</b>
                    <div class="mimenu" style="background-color: #ff9e18; overflow-y: hidden; height: 100%;">
                        <ul class="uno">

                            <?php
                            foreach ($modelMenu as $menu) {
                                $menuId = $menu->id;
                                $menus2 = $sentencia->get_asignados($modelRol->id, $menuId);
                            ?>
                                <li><a href="#"><b><?= $menu->nombre ?></b></a>
                                    <ul class="dos">

                                        <?php
                                        foreach ($menus2 as $m2) {

                                            // if ($comp->id == $det->comportamiento_id) {
                                            echo '<li><a href="' 
                                                    . yii\helpers\Url::to(['asignacion', 'id' => $m2['operacion_id'], 'accion' => 'q', 'rolId' => $modelRol->id]) 
                                                    . '"><i class="fas fa-ban"> ' 
                                                    . $m2['nombre'] 
                                                    . '</i></a></li>';                                            
                                        }
                                        ?>

                                    </ul>
                                </li>


                            <?php
                            }
                            ?>


                        </ul>
                    </div>

                </div>
                <!-- fin Para asignados -->
            </div>
            <!-- fin cuerpo de pantalla -->

        </div>

    </div>

</div>



