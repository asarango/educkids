<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = $modelDestreza->content;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="planificacion-desagregacion-cabecera-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        (
                        <?= $modelDestreza->opcion_desagregacion  ?>
                        |
                        <?php
                        if ($modelDestreza->is_active) {
                            echo '<i class="fas fa-check-circle" style="font-color: green;"></i>';
                        }

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
                        '<span class="badge rounded-pill" style="background-color: #65b2e8">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> Criterio de evaluaciòn
                        </span>',
                        ['destrezas-detalle', 'criterio_evaluacion_id' => $modelDestreza->desagregacion_evaluacion_id],
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
            <div class="row" style="margin-top: 15px;">
                <div class="col-lg-12 col-md-12">
                    <div class="row" style="margin: 15px; border: solid 1px #ccc;">
                        <div class="col-lg-12 col-md-12 p-5">
                            <?php $form = ActiveForm::begin(); ?>

                            <?php
                            echo $form->field($modelDestreza, 'content')->textarea();
                            ?>
                            <br>
                            <?=
                            $form->field($modelDestreza, 'opcion_desagregacion')->radioList([
                                'ORIGINAL'  => 'ORIGINAL',
                                'GRADAR'   => 'GRADAR',
                                'DESAGREGAR'   => 'DESAGREGAR'
                            ])->label('OPCIÒN');
                            ?>
                            <br>
                            <?=
                            $form->field($modelDestreza, 'is_active')->radioList([
                                true  => 'SI',
                                false => 'NO'
                            ])->label('¿ACTIVO?');
                            ?>


                            <br>
                            <div class="form-group">
                                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>

                    </div>
                </div>
            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>