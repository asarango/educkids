<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisArea;

$this->title = 'Estudiantes NEE';

//echo '<pre>';
//print_r($estudiantes);
//die();
//echo '<pre>';
//print_r($nee);
//die();
?>

<!--Scripts para que funcionen AJAX de select 2 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />


<div class="estudiantes-nee-index" style="padding-left: 40px; padding-right: 40px">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/menu.png" width="64px" style="" class="img-thumbnail"></h4>
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
                    <!-- inicio de menu derecha -->

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->
            <hr>

            <!--Inicia Card Principal-->
            <div class="row" style="margin-top: 20px">

                <div class="col-lg-12 col-md-12 text-center">
                    <?= Html::beginForm(['create','pestana' => 'datos_estudiante' ],'post') ?>
                    <div class="row">
                        <div class="col-lg-10 col-md-10">
                            <select id="estudiante" name="id" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option selected="selected" value="" >Escoja un estudiante...</option>
                                <?php
                                foreach ($estudiantes as $estudiante) {
                                    echo '<option value="' . $estudiante['id'] . '">' . $estudiante['student'] . '</option>';
                                }
                                ?>
                            </select> 
                        </div>
                        <div class="col-lg-2 col-md-2" style="text-align: end">
                            <?= Html::submitButton('Ingresar NEE', ['class' => 'submit btn btn-primary my-text-medium', 
                                                                    'style' => 'background-color:#0a1f8f']) 
                            ?>
                        </div>
                    </div>
                    <?= Html::endForm() ?>

                </div>
                <hr>

                <div class="col-lg-12 col-md-12">
                    <h6 class="my-text-medium">ESTUDIANTES EN SEGUIMIENTO:</h6>
                    <div class="table responsive">
                        <table class="table table-hover table-striped my-text-medium">
                            <thead>
                                <tr>

                                    <td><strong>Estudiante</strong></td>
                                    <td><strong>Acción</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($nee as $n) {
                                    ?>
                                    <tr>
                                        <td><?=$n['student'] ?> </td>
                                        <td>
                                            <?php
                                                echo Html::a(
                                                    'Ver Ficha',
                                                    ['ficha','nee_id' => $n['id'] ,
                                                      'pestana' => 'datos_estudiante'
                                                    ],
                                                    ['class' => 'badge rounded-pill bg-warning text-dark']
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

            </div>
            <!-- fin de card principal -->
        </div>
    </div>



    <!-- SCRIPT PARA SELECT2 -->
    <script>
        buscador();
        function buscador() {
            $('.select2').select2({
                closeOnSelect: true
            });
        }

    </script>
