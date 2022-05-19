<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $materia->name . ' - Conceptos Relacionados';
//echo '<pre>';
//print_r($materia);
//die();
?>
<div class="scholaris-materia-conceptos-relacionados-pai-index1">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2"><!-- INICIO DE CABECERA -->
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>(Esta materia está configurado en el lenguaje <strong style="color: black">'<?=$materia->language_code ?>'</strong>)</small>
                </div>
            </div><!-- FIN DE CABECERA -->


            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Volver a Asignaturas</span>',
                        ['scholaris-materia/index'],
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
            <div class="row" style="margin-top: 15px;padding-left: 50px;padding-right: 50px;">
                <div class="col-lg-4 col-md-4 my-text-medium " style="margin-bottom:10px">
                    <h6>Agregar Concepto Relacionado</h6>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 card shadow" style="padding:20px">
                            <?php $form = ActiveForm::begin(); ?>

                            <?= $form->field($model, 'materia_id')->hiddenInput(['value' => $materia->id])->label(false) ?>

                            <?= $form->field($model, 'contenido_es')->textInput(['placeholder' => 'ES','value' => 'NC'])->label('Concepto Relacionado Español') ?>
                            <?=  $form->field($model, 'contenido_en')->textInput(['placeholder' => 'EN','value' => 'NC'])->label('Concepto Relacionado Ingles') ?>
                            <?= $form->field($model, 'contenido_fr')->textInput(['placeholder' => 'FR','value' => 'NC'])->label('Concepto Relacionado Frances') ?>

                            <br>
                            <div class="form-group">
                            <?= Html::submitButton('Agregar', ['class' => 'btn btn-primary my-text-medium']) ?>
                            </div>
                            <br>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    

                </div>
                <div class="col-lg-8 col-md-8 my-text-medium text-center" style="width: 600px;padding-left:100px">
                    <!--<h6>Concepto Relacionados Utilizados</h6>-->

                    <div class="table-responsive scroll-400">
                        <table class="table table-hover my-text-medium">
                            <thead>
                                <tr>
                                    <th colspan="3" >CONCEPTO</th>
                                    <th colspan="2">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background-color: #e7e7e7">
                                    <td><strong>CONCEPTO ESPAÑOL</strong></td>
                                    <td><strong>CONCEPTO INGLÉS</strong></td>
                                    <td><strong>CONCEPTO FRANCÉS</strong></td>
                                    <td></td>
                                    <td></td>
                                    
                                </tr>
                                <?php
                                    foreach ($conceptos as $concepto) {
                                ?>
                                    <tr>
                                       <td><?=$concepto['contenido_es'] ?></td>
                                       <td><?=$concepto['contenido_en'] ?></td>
                                       <td><?=$concepto['contenido_fr'] ?></td>
                                                <td>
                                                    <!-- Boton Modal Editar -->
                                                <a type="button" class="btn" data-bs-toggle="modal" data-bs-target="#edit<?=$concepto['id'] ?>">
                                                    <i class="fas fa-pencil-alt" style="color:blue"></i>
                                                </a>

<!-- Modal Editar-->
<div class="modal fade" id="edit<?=$concepto['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editando Concepto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      <?= Html::beginForm(['update', 'id' => $concepto['id']],'post'); ?>
      
        <!-- type, input name, input value, options -->
        <?php
            if($materia->language_code == 'ES'){
                echo Html::input('text', 'contenido_es', $concepto['contenido_es'],['class' => 'form-control']);
                echo Html::input('hidden', 'contenido_en', $concepto['contenido_en'],['class' => 'form-control']);
                echo Html::input('hidden', 'contenido_fr', $concepto['contenido_fr'],['class' => 'form-control']);
            }
            if($materia->language_code == 'EN'){
                echo Html::input('hidden', 'contenido_es', $concepto['contenido_es'],['class' => 'form-control']);
                echo Html::input('text', 'contenido_en', $concepto['contenido_en'],['class' => 'form-control']);
                echo Html::input('hidden', 'contenido_fr', $concepto['contenido_fr'],['class' => 'form-control']);
            }
            if($materia->language_code == 'FR'){
                echo Html::input('hidden', 'contenido_es', $concepto['contenido_es'],['class' => 'form-control']);
                echo Html::input('hidden', 'contenido_en', $concepto['contenido_en'],['class' => 'form-control']);
                echo Html::input('text', 'contenido_fr', $concepto['contenido_fr'],['class' => 'form-control']);
            }
        ?>
        <?= Html::input('hidden', 'id', $concepto['id']) ?>
        <div style="text-align:end">
        <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success my-text-medium submit']) ?>
        </div>

        <?= Html::endForm() ?>

      </div>
    </div>
  </div>
</div>

                                                </td>
                                                <td>
                                                    <?php
                                                        echo Html::a(
                                                        '<i class="fas fa-trash-alt" style="color:red"></i>',
                                                        ['eliminar','id' => $concepto['id']],
                                                        ['class' => 'btn' ]
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
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>

