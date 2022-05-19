<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Editar Planificación de tema para desagregación de destrezas';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>';
//print_r($model);
//die();
$estado = $model->planCabecera->estado;
?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>


<div class="planificacion-desagregacion-cabecera-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        (
                        <?=
                        $model->planCabecera->ismAreaMateria->materia->nombre . ' - ' . $model->unit_title . ' / ' . $model->curriculoBloque->last_name
                        ?>
                        )
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Temas</span>',
                            ['index1', 'id' => $model->plan_cabecera_id],
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

                <div class="col-lg-3 col-md-3">

                </div>

                <div class="col-lg-6 col-md-6 card shadow" style="padding: 20px" >
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?= $form->field($model, 'unit_title')->textInput(['maxlength' => true])->label('Tema de la Unidad') ?>
                            <br>
                            <?= $form->field($model, 'enunciado_indagacion')->textarea(['rows' => '6']) ?>
                        </div>    
                        <br>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <!--Contenidos-->
                            <!--EDITOR DE TEXTO KARTIK-->
<!--                            <textarea name="contenido" ><?= $model->contenidos ?></textarea>
                            <script>
                                    CKEDITOR.replace( 'contenido',{
                                        customConfig: '/ckeditor_settings/config.js'                                
                                        } );
                            </script>-->
                        </div>    
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">

                            <?php
          
                            if (
                                    $model->is_open == 1 &&
                                    ($estado == 'INICIANDO' || $estado == 'DEVUELTO')
                            ) {

                                echo $form->field($model, 'settings_status')->radioList([
                                    'configurado' => 'CONFIGURADO',
                                    'en-proceso' => 'EN PROCESO'
                                ])->label('ESTADO DE CONFIGURACIÓN:');
                                ?>
                                <br>
                                <div class="form-group">
                                    <?= Html::submitButton('Actualizar', ['class' => 'btn btn-primary']) ?>
                                </div>

                                <?php
                            } else {
                                ?>
                                <h6>Esta planificación está.<?= $estado ?>.</h6>

                                <?php
                            }
                            ?>    
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

                <div class="col-lg-3 col-md-3">

                </div>



            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>