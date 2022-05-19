<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\Rol;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
?>

<div class="usuario-index">    

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 col-md-8">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/empleados.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div>
            <hr>


            <div class="row">
                <div class="col-lg-6 col-md-6">
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Perfiles de usuario</span>',
                        ['rol/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="far fa-file"></i> Permisos</span>',
                        ['operacion/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                </div>

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <?= Html::a('<i class="far fa-plus-square"> Crear usuario</i>', ['create'], ['class' => 'link']) ?>
                    |
                    <?= Html::a('<i class="fas fa-handshake"> Sincronizar docentes</i>', ['profesores'], ['class' => 'link']) ?>
                    |
                    <?= Html::a('<i class="far fa-handshake"> Sincronizar ppff</i>', ['padres'], ['class' => 'link']) ?>
                </div>
            </div>


            <!-- inicia tabla de Usuario -->

            <div class="table table-responsive">
                <table id="tabla" class="table table-hover table-striped table-condensed">
                    <thead>
                        <tr style="background-color: #ff9e18;">
                            <th>AVATAR</th>
                            <th>USUARIO</th>
                            <th>PERFIL</th>
                            <th>ESTADO</th>
                            <th>CORREO</th>
                            <th>FIRMA</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($users as $user) {
                            
                            if($user['avatar'] == NULL || $user['avatar'] == ''){
                                $avatar = 'no-photo.jpeg';
                            }else{
                                $avatar = $user['avatar'];
                            }
                            
                            if($user['firma'] == NULL || $user['firma'] == ''){
                                $firma = 'firma-digital.png';
                            }else{
                                $firma = $user['avatar'];
                            }

                            // isset($user['avatar']) ? $avatar = $user['avatar'] : $avatar = 'no-photo.jpeg';
                            // isset($user['firma']) ? $firma = $user['firma'] : $firma = 'firma-digital.png';
                        ?>
                            <tr>
                                <td class="text-center"><img src="ISM/avatars/<?= $avatar ?>" width="25px"></td>
                                <td><?= $user['usuario'] ?></td>
                                <td><?= $user['rol'] ?></td>
                                <td class="text-center">
                                    <?php 
                                    if($user['activo']==1 ){
                                        ?>
                                        <img src= "https://cdn-icons.flaticon.com/png/512/5610/premium/5610944.png?token=exp=1641918006~hmac=31b706a7cda17b84cdbde8fa175a170e" width="20px">
                                        <?php
                                    }else{
                                        ?>
                                        <img src="https://cdn-icons-png.flaticon.com/512/1828/1828843.png" width="20px">
                                        <?php
                                    }
                                    
                                    ?>
                                </td>
                                <td><?= $user['email'] ?></td>
                                <td class="text-center"><img src="ISM/firmas/<?= $firma ?>" width="40px" height="20"></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button style="font-size: 10px; border-radius: 0px" id="btnGroupDrop1" type="button" class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <li>
                                                <?= Html::a(
                                                    'Editar',
                                                    ['update', 'id' => $user['usuario']],
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
                    <tfoot>
                        <tr style="background-color: #ff9e18;">

                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Fin tabla de Usuario -->

        </div>

    </div>


</div>

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>



<script>
    

    $('#tabla').DataTable();
</script>