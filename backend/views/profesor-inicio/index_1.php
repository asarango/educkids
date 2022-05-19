<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis asignaturas';
//$this->params['breadcrumbs'][] = $this->title;
?>


<div class="portal-inicio-index animate__animated animate__fadeIn">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1"><h4><img src="ISM/main/images/submenu/aula.png" width="64px" style="" class="img-thumbnail"></h4></div>
                <div class="col-lg-11"><h4><?= Html::encode($this->title) ?></h4></div>
            </div>
            <hr>

            <p>
                |                                
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>                
                |
            </p>
            

            <div class="table table-responsive">
                <table class="table table-striped table-condensed table-hover" id="table-clases" style="">
                    <thead>
                        <tr bgcolor="#ff9e18" class="text-center">
                            <th>ID</th>
                            <th>CURSO</th>
                            <th>PARALELO</th>
                            <th>ASIGNATURA</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($clases as $clase) {
                            ?>
                            <tr>
                                <td class="text-center"><?= $clase['clase_id'] ?></td>
                                <td><?= $clase['curso'] ?></td>
                                <td class="text-center"><?= $clase['paralelo'] ?></td>
                                <td><?= $clase['materia'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button style="font-size: 10px; border-radius: 0px;" id="btnGroupDrop1" type="button" 
                                                class="btn btn-outline-warning btn-sm dropdown-toggle" 
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                >
                                            Acciones
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <li>
                                                <?= Html::a('Sábana Digital', ['reporte-sabana-profesor/index1','id'=> $clase['clase_id']],
                                                                              ['class' => 'dropdown-item', 'style' => 'font-size:10px']) 
                                                ?>
                                            </li>
                                            
                                            <li>
                                                <?= Html::a('Actividades', ['profesor-inicio/actividades','id'=> $clase['clase_id']],
                                                                              ['class' => 'dropdown-item', 'style' => 'font-size:10px']) 
                                                ?>
                                            </li>
                                            
                                            <li>
                                                <?= Html::a('Comportamiento', ['scholaris-asistencia-profesor/index'],
                                                                              ['class' => 'dropdown-item', 'style' => 'font-size:10px']) 
                                                ?>
                                            </li>
                                            
                                            <li>
                                                <?= Html::a('Best Fit - PAI', ['scholaris-notas-pai/index1', 'id' => $clase['clase_id']],
                                                                              ['class' => 'dropdown-item', 'style' => 'font-size:10px']) 
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
<!--                    <tfoot>
                        <tr bgcolor="#ff9e18" class="text-center">
                            <th>ID</th>
                            <th>CURSO</th>
                            <th>PARALELO</th>
                            <th>ASIGNATURA</th>                        
                            <th>ACCIONES</th> 
                        </tr>
                    </tfoot>-->
                </table>
            </div>


        </div>


    </div>

</div>

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>



    $('#table-clases').DataTable();


</script>