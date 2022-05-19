<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use backend\controllers\NeeController;
use backend\models\NeexClase;

//print_r($model);
//echo '<pre>';
//print_r($materiasNee);
?>
<!--SCRIPTS Y JQUERYS PARA SELECT 2-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />


<div class="row">
    <h5 style="text-align:start; margin-top:10px;color:#0a1f8f"> 3.- FECHA DE ELABORACIÓN Y DURACIÓN PREVISTA</h5>
    <div class="col-lg-12 col-md-12" style="margin:5px">
        <div class="table-responsive">
            <table class="table table-hover my-text-medium">
                <thead>
                    <tr colspan="2" ></tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>FECHA DE ELABORACIÓN: </strong><?= $model->created_at ?></td> 
                        <td><strong>DURACIÓN PREVISTA: </strong>4 BLOQUES </td> 
                    </tr>
                    <tr>
                        <td><strong>ELABORADO POR: </strong> <?= $model->created ?></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-12 col-md-12" style="margin-left: 20px; margin-right: 20px; margin-bottom: 10px">

        <h6 class="my-text-medium">Escoja Asignatura:</h6>
        
       
            <?= Html::beginForm(['insert-class', 'id' => ''], 'post') ?>
          
                <!--type, input name, input value, options-->
                <?= Html::input('hidden', 'nee_id', $model->id, ['class' => 'form-control']) ?>

                <select  name="clase_id" class="select2 select2-hidden-accessible" style="width: 60%;" tabindex="-1" aria-hidden="true">
                    <option selected="selected" value="" >Asignatura</option>
                    <?php
                    foreach ($materiasSelect as $disponible) {
                        echo '<option value="' . $disponible['clase_id'] . '">' . $disponible['materia'] . '</option>';
                    }
                    ?>
                </select>
         
            
         
                <?= Html::submitButton('Ingresar', ['class' => 'btn btn-success submit my-text-medium']) ?>
           
            <?= Html::endForm() ?>
       

        <div class="table responsive" style="width: 95%">
            <table class="table table-hover table-striped my-text-medium">
                <thead>
                    <tr class="text-center">
                        <th>Materia / Profesor</th>
                        <th>Grado</th>
                        <th>Diagnóstico Inicia</th>
                        <th>Diagnóstico Finaliza</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($materiasNee as $seleccionada) {
                        ?>
                        <tr class="text-center">
                            <td><?= $seleccionada->clase->materia->name . '-' . $seleccionada->clase->profesor->last_name . ' ' . $seleccionada->clase->profesor->x_first_name ?></td>
                            <td><?= $seleccionada->grado_nee ?></td>
                            <td>
                                <?php
                                if ($seleccionada->diagnostico_inicia == 'Aquí su diagnóstico') {
                                    echo '<i type="button" class="fas fa-exclamation-triangle" style="color:#ff9e18 " ></i>';
                                } else {
                                    echo '<i type="button" class="fas fa-check-circle" style="color:green" ></i>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($seleccionada->diagnostico_finaliza == null) {
                                    echo '<i type="button" class="fas fa-exclamation-triangle" style="color:#ff9e18 " ></i>';
                                } else {
                                    echo '<i type="button" class="fas fa-check-circle" style="color:green" ></i>';
                                }
                                ?>
                            </td>
                            <td>
                                <!-- Boton Modal Editar -->
                                <a type="button" class="btn" data-bs-toggle="modal" data-bs-target="#edit<?= $seleccionada->id ?>">
                                    <i class="fas fa-pencil-alt" style="color: #0a1f8f" ></i>
                                </a>

                                <!-- Modal Editar -->
                                <div class="modal fade" id="edit<?= $seleccionada->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">NEE-<?= $seleccionada->clase->materia->name ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body" style="text-align: start" >
                                                <?= Html::beginForm(['update-class', 'id' => $seleccionada->id], 'post') ?>

                                                <!--type, input name, input value, options-->
                                                <?= Html::input('hidden', 'id', $seleccionada->id, ['class' => 'form-control']) ?>
                                                <label>GRADO:</label>
                                                <?=
                                                Html::input('number', 'grado_nee', $seleccionada->grado_nee, ['class' => 'form-control',
                                                    'min' => '1', 'max' => '3'
                                                ])
                                                ?>

                                                <label>FECHA INICIA:</label>
                                                <?=
                                                Html::input('text', 'fecha_inicia', $seleccionada->fecha_inicia, ['class' => 'form-control',
                                                    'disabled' => true,
                                                    'readonly' => true
                                                ])
                                                ?>

                                                <label>DIAGNÓSTICO INICIA:</label>
                                                <?=
                                                Html::textarea('diagnostico_inicia', $seleccionada->diagnostico_inicia, ['class' => 'form-control',
                                                    'rows' => '3'
                                                ])
                                                ?>

                                                <label>FECHA FINALIZA:</label>
                                                <?= Html::input('date', 'fecha_finaliza', $seleccionada->fecha_finaliza, ['class' => 'form-control']) ?>

                                                <label>DIAGNÓSTICO FINALIZA:</label>
                                                <?=
                                                Html::textarea('diagnostico_finaliza', $seleccionada->diagnostico_finaliza, ['class' => 'form-control',
                                                    'rows' => '3'
                                                ])
                                                ?>

                                                <div style="text-align: end; margin-bottom: 5px; margin-top: 5px">
                                                    <?= Html::submitButton('Actualizar', ['class' => 'submit btn btn-success my-text-medium']) ?>
                                                </div>
                                                <?= Html::endForm() ?>
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


<!-- SCRIPT PARA SELECT2 -->
<script>
    buscador();
    function buscador() {
        $('.select2').select2({
            closeOnSelect: true
        });
    }

//Funcion para insertar materia NEE
    function ajaxInsertaMateria(obj) {
        var claseId = obj.value;
//        alert(claseId);
        var neeId = '<?= $model->id ?>';
        var url = '<?= Url::to(['insert-class']) ?>';
        var params = {
            clase_id: claseId,
            nee_id: neeId,
            pestana: 'fecha_elab'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                location.reload();
            }
        });
    }




</script>


