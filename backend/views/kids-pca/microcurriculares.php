<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

//print_r($microcurriculares);
//print_r($modelMicro);
//die();
$numMicro = count($microcurriculares);
$orden = $numMicro + 1;
?>
<div class="row" style="margin-bottom:5px; padding:10px; padding-left:50px; padding-right-50px">

    <!-- Button trigger modal -->
    <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="fas fa-plus-square">Agregar</i>
    </a>

    <div class="col-md-12 col-sm-12">
        <div class="table table-responsive">
            <table class="table table-hover table-stripped table-bordered my-text-medium" style="font-size: 10px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>UNIDAD MICROCURRICULAR</th>
                        <th colspan="2" style="text-align: center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($microcurriculares as $micro){
                            ?>
                            <tr>
                                <td><?= $micro['orden'] ?></td>
                                <td><?= $micro['experiencia'] ?></td>
                                <td class="text-center" ><?= Html::a('<i class="fas fa-cogs" style="color: #9e28b5"></i>', ['kids-experiencia/index1', 'id' => $micro['id']]) ?></td>
                                <td class="text-center">
                                    <!-- Button trigger modal -->
<a type="button" data-bs-toggle="modal" data-bs-target="#modal<?=$micro->id?>">
<i class="fas fa-edit" style="color: #0a1f8f"></i>
</a>

<!-- Modal -->
<div class="modal fade" id="modal<?=$micro->id?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editando <?=$micro['experiencia'] ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <?php $form = ActiveForm::begin(['id' => 'updateMicro', 'options' => ['method' => 'post'] ]); ?>
      <!-- <ph $form = ActiveForm::begin(['id' => 'builder/saveform','options' => ['method' => 'post']]) ?> -->

                <?= $form->field($micro, 'pca_id')->hiddenInput(['value' => $pcaId])->label(false) ?>

                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <?=
                        $form->field($micro, 'fecha_inicia')->textInput([
                            'type' => 'date'
                        ])->label('Fecha inicio')
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <?=
                        $form->field($micro, 'fecha_termina')->textInput([
                            'type' => 'date'
                        ])->label('Fecha finaliza')
                        ?>
                    </div>
                </div>

                <?= $form->field($micro, 'orden')->textInput(['type' => 'number', 'value' => $micro['orden']]) ?>

                <?= $form->field($micro, 'experiencia')->textarea(['rows' => '3']) ?>

                <?= $form->field($micro, 'observaciones')->textarea(['rows' => '3']) ?>
                
                <?= $form->field($micro, 'estado')->hiddenInput(['value' => 'INICIANDO'])->label(false) ?>
                
                <?= $form->field($micro, 'updated')->hiddenInput(['value' => $userLog])->label(false) ?>
                
                <?= $form->field($micro, 'updated_at')->hiddenInput(['value' => $today])->label(false) ?>

                <div class="form-group" style="margin-top:10px">
                    <?= Html::submitButton('Actualizar', ['class' => 'btn btn-secondary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
      </div>
                            <div class="modal-footer">

                            </div>

    </div>
  </div>
</div>

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


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Unidad Microcurricular</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($modelMicro, 'pca_id')->hiddenInput(['value' => $pcaId])->label(false) ?>

                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <?=
                        $form->field($modelMicro, 'fecha_inicia')->textInput([
                            'type' => 'date'
                        ])->label('Fecha inicio')
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <?=
                        $form->field($modelMicro, 'fecha_termina')->textInput([
                            'type' => 'date'
                        ])->label('Fecha finaliza')
                        ?>
                    </div>
                </div>

                <?= $form->field($modelMicro, 'orden')->textInput(['type' => 'number', 'value' => $orden]) ?>

                <?= $form->field($modelMicro, 'experiencia')->textarea(['rows' => '3']) ?>

                <?= $form->field($modelMicro, 'observaciones')->textarea(['rows' => '3']) ?>
                
                <?= $form->field($modelMicro, 'estado')->hiddenInput(['value' => 'INICIANDO'])->label(false) ?>
                
                <?= $form->field($modelMicro, 'created')->hiddenInput(['value' => $userLog])->label(false) ?>
                
                <?= $form->field($modelMicro, 'created_at')->hiddenInput(['value' => $today])->label(false) ?>
                
                <?= $form->field($modelMicro, 'updated')->hiddenInput(['value' => $userLog])->label(false) ?>
                
                <?= $form->field($modelMicro, 'updated_at')->hiddenInput(['value' => $today])->label(false) ?>

                <div class="form-group" style="margin-top:10px">
                    <?= Html::submitButton('Agregar', ['class' => 'btn btn-secondary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>