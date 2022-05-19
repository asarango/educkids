<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OperacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permisos de perfil';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="operacion-index">


    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 col-md-8 p-3">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/operation.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div>
            <hr>

            <p>
                |
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="far fa-file"></i> Nuevo</span>', ['create'], ['class' => 'link']); ?>
                |
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fas fa-link"></i> Perfiles</span>', ['rol/index'], ['class' => 'link']); ?>
                |
            </p>


            <div class="table table-responsive p-2">
                <table id="example" class="table table-striped table-hover">
                    <thead>
                        <tr bgcolor="#ff9e18">
                            <th>ID</th>
                            <th>MENU</th>
                            <th>OPERACIÓN</th>
                            <th>NOMBRE</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($operations as $oper) {
                            echo '<tr>';
                            echo '<td>' . $oper->id . '</td>';
                            echo '<td>' . $oper->menu->nombre . '</td>';
                            echo '<td>' . $oper->operacion . '</td>';
                            echo '<td>' . $oper->nombre . '</td>';
                            echo '<td>';
                        ?>
                            <div class="btn-group" role="group">
                                <button style="font-size: 10px; border-radius: 0px" id="btnGroupDrop1" type="button" class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Acciones
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li>                                    
                                        <?= Html::a(
                                            'Editar',
                                            ['update', 'id' => $oper->id ],
                                            ['class' => 'dropdown-item', 'style' => 'font-size:10px']
                                        )
                                        ?>
                                    </li>
                                    
                                    <li>                                    
                                        <?= Html::a(
                                            'Eliminar',
                                            ['delete', 'id' => $oper->id ],
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
                        <?php
                            echo '</td>';

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr bgcolor="#ff9e18">
                            <th>ID</th>
                            <th>MENU</th>
                            <th>OPERACIÓN</th>
                            <th>NOMBRE</th>
                            <th>ACCIONES</th>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>

    </div>




    <?php // echo $this->render('_search', ['model' => $searchModel]);    
    ?>



</div>



<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
    $('#example').DataTable();
</script>