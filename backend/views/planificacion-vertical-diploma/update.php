<?php

use backend\models\PlanificacionVerticalDiploma;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actualizar Planificación Vertical Diploma';
$this->params['breadcrumbs'][] = $this->title;

//print_r($planUnidad);



?>
<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small><b>BLOQUE Nº: </b> 
                    <?php
                        //$modelPlanifVertDipl = PlanificacionVerticalDiploma::find();
                    ?>
                        <?= $modelPlanifVertDipl->planificacionBloqueUnidad->curriculoBloque->last_name ?><b> | 
                        <?= $modelPlanifVertDipl->planificacionBloqueUnidad->unit_title  ?></b> | 
                        <?= $modelPlanifVertDipl->planificacionBloqueUnidad->planCabecera->scholarisMateria->name  ?></b>
                    </small>
                </div>
            </div>
            <!-- FIN DE CABECERA -->


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
                            '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificación Vertical</span>',
                            ['index1', 'unidad_id' => $modelPlanifVertDipl->planificacionBloqueUnidad->id],
                            ['class' => 'link']
                    );
                    ?>
                    |
                    
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    |
                    <?php
                    // Html::a(
                    //         '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Actualizar detalles</span>',
                    //         ['update', 'id' => $planUnidad->id],
                    //         ['class' => 'link']
                    // );
                    ?>
                    |
                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->
            <hr>
            <!-- inicia cuerpo de card -->
            
            <div class="card shadow p-4" style="margin: 20px;">
                <?php $form = ActiveForm::begin(); ?>

                    <div class="row p-3">
                        <div class="col-lg-2 col-md-2"><b>Objetivos de la Asignatura</b></div>
                        <div class="col-lg-10 col-md-10">
                        <?= $form->field($modelPlanifVertDipl, 'objetivo_asignatura')->textarea(['rows' => 3])->label(false) ?>
                        </div>
                    </div>

                    <div class="row p-3">
                        <div class="col-lg-2 col-md-2"><b>Concepto Clave</b></div>
                        <div class="col-lg-10 col-md-10">
                        <?= $form->field($modelPlanifVertDipl, 'concepto_clave')->textarea(['rows' => 3])->label(false) ?>
                        </div>
                    </div>

                    <div class="row p-3">
                        <div class="col-lg-2 col-md-2"><b>Objetivos de la Evaluación</b></div>
                        <div class="col-lg-10 col-md-10">
                        <?= $form->field($modelPlanifVertDipl, 'objetivo_evaluacion')->textarea(['rows' => 3])->label(false) ?>
                        </div>
                    </div>

                    <div class="row p-3">
                        <div class="col-lg-2 col-md-2"><b>Instrumentos</b></div>
                        <div class="col-lg-10 col-md-10">
                        <?= $form->field($modelPlanifVertDipl, 'intrumentos')->textarea(['rows' => 3])->label(false) ?>
                        </div>
                    </div>



                    <div class="form-group" style="margin-top: 10px; text-align: right;">
                        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
            </div>
            

            <hr>

            <!-- inicia formulario de tdc -->
            <div class="card shadow" style="margin: 20px;">
            <div class="row p-3">
                <?=
                    $this->render('formtdc',[
                        'modelPlanifVertDiplTDC' => $modelPlanifVertDiplTDC,
                        'modelPlanifVertDiplId' => $modelPlanifVertDipl->id
                    ]);
                ?>
            </div>            
            </div>
            <!-- finaliza formulario de tdc -->

                    <hr>
            <!-- inicia formulario de habilidades -->
            <div class="card shadow" style="margin: 20px;">
            <div class="row p-3">
                <?=
                    $this->render('formhabilidades',[
                        'modelPlanifVertDiplId' => $modelPlanifVertDipl->id,
                        'modelPlanifVertDiplHab' => $modelPlanifVertDiplHab
                    ]);
                ?>
            </div>            
            </div>            
            <!-- finaliza formulario de habilidades -->

            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>