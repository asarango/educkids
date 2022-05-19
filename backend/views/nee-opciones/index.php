<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'NEE OPCIONES';

?>

<div class="nee-opciones-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2"><!-- INICIO DE CABECERA -->
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/nee-opciones.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>Aqui puede agregar las opciones para los estudiantes NEE</small>
                </div>
            </div><!-- FIN DE CABECERA -->


            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio </span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <!-- inicio de menu derecha -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> Crear Opciones NEE </span>',
                            ['create'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" >
                 <div class="table table-responsive" style="padding: 20px;">
                    <table id="tabla" class="table table-hover table-sptriped table-condensed my-text-medium">
                        <thead>
                            <tr style="background-color: #ff9e18;">
                                <th>CÓDIGO</th>
                                <th>CATEGORÍA</th>
                                <th>ORDEN</th>
                                <th>CONTENIDO</th>
                                <th style="text-align: center">ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php
                           foreach($opciones as $opcion){
                               ?>
                            <tr>
                                <td><?=$opcion['codigo'] ?></td>
                                <td><?=$opcion['categoria'] ?></td>
                                <td><?=$opcion['orden'] ?></td>
                                <td><?=$opcion['nombre'] ?></td>
                                <td style="text-align: center"><?php
                                    if($opcion['estado'] == 1){
                                        echo '<i class="fas fa-check-circle" style="color:green"></i>';
                                    }else{
                                        echo '<i class="fas fa-times-circle" style="color:#ab0a3d"></i>';
                                    }    
                                    $opcion['estado'] 
                                    ?></td>
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
                                                        <a onclick="ajaxDelete(<?=$opcion['id']?>)" type="button" class="dropdown-item" style="font-size: 10px">
                                                            Eliminar
                                                        </a>
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
            <!-- fin cuerpo de card -->

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
    $('#tabla').DataTable();
    
       
    function ajaxDelete(id){
        var url='<?=Url::to(['delete']) ?>';
        var params = {
            id: id
        };
        
        $.ajax({
            url:url,
            data:params,
            type:'POST',
            beforeSend:function(){},
            success: function(){}
        });
    }
</script>

