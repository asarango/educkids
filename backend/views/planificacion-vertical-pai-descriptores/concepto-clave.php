<?php
use yii\helpers\Html;
use yii\grid\GridView;

//   echo '<pre>';
//    print_r($conceptosClaveDisponibles);
//    die();
//   print_r($conceptosClaveSeleccionados);
//   print_r($conceptosClaveSeleccionados);
$condicionClass = new backend\models\helpers\Condiciones;
$estado = $bloqueUnidad->planCabecera->estado;
$isOpen = $bloqueUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado,$isOpen,$bloqueUnidad->settings_status);
?>


<div class="row text-center" style="margin-top:15px;">
    
    <?php
    if($condicion == false ){
        ?>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style=" background-color: #eee">
        <h4 style="text-align:center">DISPONIBLES</h4>
        <hr>
        <!-- Aqui se muestran los conceptos -->
        <h6>Esta planificación está <?=$estado?></h6>
    </div>

    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 border border-1 border-warning">
        <h4 style="text-align:center">SELECCIONADOS</h4>
        <hr>
        <!-- Tabla que muestra conceptos seleccionados -->
        <div>
            <table class="table table-hover table-striped my-text-medium">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col" style="text-align:center">CONTENIDO</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $contador= 1;
                        foreach ($conceptosClaveSeleccionados as $seleccionados) {
                    ?>
                    <tr>
                        <th><?= $contador ?></th>
                        <td><?= '<strong>'.$seleccionados['contenido'].'</strong>' ?></td>
                        
                    </tr>    
                    <?php
                        $contador = $contador + 1;
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    }else{
        ?>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style=" background-color: #eee">
        <h4 style="text-align:center">DISPONIBLES</h4>
        <hr>
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
                    foreach ($conceptosClaveDisponibles as $disponibles) {
                    ?>
                        <tr>
                            <td style="width:30px">
                                <?= 
                                    Html::a(
                                        '<strong>'.$disponibles['contenido_es'].'</strong>',
                                        ['asignar-contenido','plan_unidad_id' =>$bloqueUnidad->id,
                                            'tipo' => $disponibles['tipo'],
                                            'contenido' => $disponibles['contenido_es'],
                                            'pestana' => 'concepto_clave'],
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
    </div>

    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 border border-1 border-warning">
        <h4 style="text-align:center">SELECCIONADOS</h4>
        <hr>
        <!-- Tabla que muestra conceptos seleccionados -->
        <div>
            <table class="table table-hover table-striped my-text-medium">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col" style="text-align:center">CONTENIDO</th>
                        <th scope="col">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $contador= 1;
                        foreach ($conceptosClaveSeleccionados as $seleccionados) {
                    ?>
                    <tr>
                        <th><?= $contador ?></th>
                        <td><?= '<strong>'.$seleccionados['contenido'].'</strong>' ?></td>
                        <td>
                            <?=
                                Html::a(
                                    '<i class="far fa-trash-alt" style="color:red"></i>',
                                    ['quitar-contenido','id' => $seleccionados['id'],
                                        'pestana' => 'concepto_clave'
                                    ],
                                    ['class' => 'link']
                                );
                            ?>
                        </td>
                    </tr>    
                    <?php
                        $contador = $contador + 1;
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    }
    ?>
    
    
</div>