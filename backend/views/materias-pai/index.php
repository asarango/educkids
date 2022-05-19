<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Materias de PAI';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($materias);
?>
<div class="materias-pai-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/libros.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        (Esta pantalla muestra todas las materias pertenecientes al PAI)
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
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin: 25px;">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-hover table-striped my-text-medium">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">CÓDIGO</th>
                            <th scope="col" style="text-align:center" >MATERIA</th>
                            <th scope="col">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contador = 1;
                        foreach ($materias as $materia) {
                        ?>
                        <tr>
                            <th scope="row">
                                <?= $contador ?>
                            </th>
                            <td scope="row">
                                <?= $materia['id'] ?>
                            <td style="" >
                                <?= $materia['materia'] ?>
                            </td>
                            <td>
                                <?=
                                    Html::a(
                                        '<span class="bagde rounded-pill bg-warning text-dark">
                                            Enfoque
                                            <i class="far fa-lightbulb"  ></i>
                                        </span>',
                                        ['mapa-enfoques','materia_id' => $materia['id']],
                                        ['class' => 'link']
                                        );
                                ?>
                            <!-- <span class="badge bg-warning text-dark">
                                Enfoques
                                <i class="far fa-lightbulb"></i>
                            </span> -->
                            </td>
                        </tr>

                        <?php
                        $contador = $contador+1;
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