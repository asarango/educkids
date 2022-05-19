<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPduCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'PDU: ' . $modelPlanCabecera->clase->materia->name
        . ' ' . $modelPlanCabecera->clase->course->name
        . ' ' . $modelPlanCabecera->clase->paralelo->name
        . ' / ' . $modelPlanCabecera->bloque->name;




?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['profesor-inicio/index']); ?>
        </li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Regresar', ['plan-profesor/index1']); ?>
        </li> 
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>

<div class="plan-pdu-cabecera-index">

    <div class="container">
        <h3>PLANIFICACIÓN MICROCURRICULAR</h3>


        <div class="panel panel-primary">
            <div class="panel-heading">1.- DATOS INFORMATIVOS</div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="table table-responsive">
                            <table class="tamano10 table table-condensed table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <td class="fondotd"><strong>DOCENTE: </strong></td>
                                        <td><?= $modelPlanCabecera->clase->profesor->last_name . ' ' . $modelPlanCabecera->clase->profesor->x_first_name ?></td>

                                        <td class="fondotd"><strong>Área / Asignatura: </strong></td>
                                        <td><?= $modelPlanCabecera->clase->materia->name ?></td>

                                        <td class="fondotd"><strong>Grado / Curso: </strong></td>
                                        <td><?= $modelPlanCabecera->clase->course->name ?></td>

                                        <td class="fondotd"><strong>Paralelo: </strong></td>
                                        <td><?= $modelPlanCabecera->clase->paralelo->name ?></td>
                                    </tr>

                                    <tr>
                                        <td class="fondotd"><strong>Número de unidad: </strong></td>
                                        <td><?= $modelPlanCabecera->bloque->orden . ' ' . $modelPlanCabecera->clase->profesor->x_first_name ?></td>

                                        <td class="fondotd"><strong>Períodos: </strong></td>
                                        <td><?= $modelPlanCabecera->periodos ?></td>

                                        <td class="fondotd"><strong>Fecha de inicio: </strong></td>
                                        <td><?= $modelPlanCabecera->bloque->bloque_inicia ?></td>

                                        <td class="fondotd"><strong>Fecha de Finalizacón: </strong></td>
                                        <td><?= $modelPlanCabecera->bloque->bloque_finaliza ?></td>
                                    </tr>
                                        
                                    <tr>
                                        <td class="fondotd"><strong>Estado: </strong></td>
                                        <td><?= $modelPlanCabecera->estado ?></td>
                                    </tr>

                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-footer">
                <?php echo Html::a('Actualizar', ['plan-pdu-cabecera/update','id' => $modelPlanCabecera->id],['class' => 'btn btn-primary']); ?>
                <?php
                    if($modelPlanCabecera->estado != 'aceptado'){
                        echo Html::a('Eliminar', ['plan-pdu-cabecera/delete','id' => $modelPlanCabecera->id],['class' => 'btn btn-danger']); 
                    }
                    
                ?>
            </div>
        </div>  
        
        
        <div class="panel panel-default">
            <div class="panel-heading">2.- PLANIFICACIÓN</div>
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">
                        <p class="tamano10"><strong>Título de la unidad de planificación: </strong><?= $modelPlanCabecera->planificacion_titulo ?></p>
                        <p class="tamano10">
                            <strong>Objetivo de la unidad:</strong>
                            <?= $modelPlanCabecera->objetivoPorNivel->descripcion ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="panel-footer">
                
                <div class="row">
                    <div class="col-md-6 tamano10 fondotd">
                        <div class="row">
                            <div class="col-md-10">
                                <strong>Eje transversal: </strong>
                                
                                <?php
                                                foreach ($modelEjes as $eje){
                                                    echo $eje->parametro->nombre.', ';
                                                }
                                ?>
                                
                            </div>
                            <div class="col-md-2">
                                <?php echo Html::a('Editar', ['plan-pdu-ejes/index1','id' => $modelPlanCabecera->id],['class' => 'btn btn-primary']); ?>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-6 tamano10 fondotd2">
                        <div class="row">
                            <div class="col-md-10">
                                <strong>Valor institucional: </strong>
                                
                                <?php
                                                foreach ($modelValor as $valor){
                                                    echo $valor->parametro->nombre.', ';
                                                }
                                ?>
                                
                            </div>
                            <div class="col-md-2">
                                <?php echo Html::a('Editar', ['plan-pdu-valores/index1','id' => $modelPlanCabecera->id],['class' => 'btn btn-primary']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>  
        
        <div class="panel panel-default">
            <div class="panel-heading">
                    2.1- PLANIFICACIÓN - DESTREZAS
                    <?php echo Html::a('Nuevo', ['plan-pdu-valores/index1','id' => $modelPlanCabecera->id],['class' => 'glyphicon glyphicon-plus']); ?>
            </div>
            <div class="panel-body">
                
            </div>
            <div class="panel-footer">                
            </div>
        </div>

    </div>

</div>
