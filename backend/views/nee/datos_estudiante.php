<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;


//print_r($age_family);
//die();
?>

<div class="row">
    <!-- ----------Solo es row con el nombre de la pestaña y Modal para agregar datos NEE---------------------------------->
    <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <h5 style="text-align:start; margin-top:10px;color:#0a1f8f">1.-DATOS INFORMATIVOS ESTUDIANTE</h5>
            </div>
            <div class="col-lg-6 col-md-6">

                <!-- Boton Modal Agregar Nee-->
                <a type="button" class="btn btn-link" style="color:#ab0a3d" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-plus-circle">Agregar datos NEE</i>
                </a>
            </div>
        </div> 

    </div>

    <!--     ------------------------------------------------------------------------------------------------------------   -->

    <div class="col-lg-12 col-md-12" style="margin:5px">
        <div class="table responsive">
            <table class="table table-hover my-text-medium">
                <thead>
                    <tr colspan="3"></tr>
                </thead>
                <tbody>

                    <!--------------------DATOS DEL ESTUDIANTE------------------------------>
                    <tr>
                        <td>
                            <strong>NOMBRES Y APELLIDOS DEL ESTUDIANTE:</strong>
                            <?php
                            echo $estudiante->first_name . ' ' . $estudiante->middle_name . ' ' . $estudiante->last_name;
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>EDAD:</strong>
                            <?= $age_family['student'] ?>
                        </td>
                        <td>
                            <strong>LUGAR - FECHA NACIMIENTO:</strong>
                            <?php
                            echo $estudiante->birth_date
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>N° CÉDULA DE IDENTIDAD</strong>
                            <?php
                            echo $estudiante->numero_identificacion;
                            ?>
                        </td>
                        <td>
                            <strong>CARNÉT DISCAPACIDAD:</strong>
                        </td>
                        <td>
                            <strong>TIPO DE DISCAPACIDAD:</strong>
                        </td>                        
                    </tr>

                    <tr>
                        <td>
                            <strong>INSTITUCIÓN EDUCATIVA:</strong>
                            <?= $instituto['name'] ?>
                        </td>
                        <td>
                            <strong>NIVEL EDUCACIÓN:</strong>
                            <?= $instituto['name'] ?>
                        </td>
                        <td>
                            <strong>TUTOR:</strong>
                            <?= $instituto['name'] ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>DIRECCIÓN DOMICILIARIA: </strong>
                            <?= $estudiante->x_main_street ?>
                        </td>
                        <td>
                            <strong>TELÉFONO: </strong>
                            <?= $estudiante->phone ?>
                        </td>
                    </tr>
                    <!-- -----------------------------FIN DE DATOS ESTUDIANTE --------------------------->


                    <!-- ---------------DATOS DE PADRES Y DE TUTOR--------------------------------------->
                    <tr>
                        <td>
                            <strong>MADRE: </strong>
                            <?php
                            if ($padres[0]->x_state == 'madre') {
                                echo $padres[0]->name;
                            }
                            ?>
                        </td>
                        <td>
                            <strong>OCUPACIÓN:</strong>
                        </td>
                        <td>
                            <strong>EDAD:</strong>
                            <?php
                            if (isset($age_family['madre'])) {
                                echo $age_family['madre'];
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>PADRE: </strong>
                            <?php
                            if ($padres[0]->x_state == 'padre') {
                                echo $padres[0]->name;
                            }
                            if (isset($padres[1]->x_state) == 'padre') {
                                echo $padres[1]->name;
                            }
                            ?>
                        </td>
                        <td>
                            <strong>OCUPACIÓN:</strong>
                        </td>
                        <td>
                            <strong>EDAD:</strong>
                            <?php
                            if (isset($age_family['padre'])) {
                                echo $age_family['padre'];
                            }
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td><strong>REPRESENTANTE: </strong> <?= $estudiante->representative ?> </td>
                    </tr>
                    <!--  -------------------------FIN DE DATOS DE PADRES Y TUTOR------------------------------ -->

                    <!-- -------------DATOS INFORMATIVOS DE LA EVALUACIÓN ---------------------------------------->

                    <tr>
                        <td>
                            <strong>FECHA DE LA EVALUACIÓN:</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>REMITE:</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>MOTIVO DE LA EVALUACIÓN:</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>TÉCNICAS E INSTRUMENTOS DE EVALUACIÓN</strong>
                        </td>
                    </tr>

                    <!-- ---------------------FIN DATOS INFORMATIVOS DE LA EVALUACIÓN ------------------------------------>

                </tbody>
            </table>
        </div>
    </div>
</div>




<!-- Modal Agregar Datos NEE-->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Datos Nee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
            <!-- ----------------------Inicia el formulario ---------------------------------------- -->
                    <?php $form = ActiveForm::begin(); ?>

                        <div class="row">
                            <div class="col-lg-6 col-md-6" >
                                <label>TIPO(S) DE DISCAPACIDAD(ES)</label>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                                    <option selected>Escoja tipos de discapacidad...</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6" >
                                <label>DESCRIPCIÓN:</label>
                                <input type="text" name="descripcion_discapacidad" class="form-control">
                            </div>
                        </div>
                        
                    <?php ActiveForm::end(); ?>
            <!-- ----------------------Termina el formulario ---------------------------------------- -->
                    </div>
                </div>
                
                
                
                
            </div>
        </div>
    </div>
</div>
