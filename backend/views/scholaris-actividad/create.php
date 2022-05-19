<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisActividad;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Crear nueva actividad';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="scholaris-actividad-create">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/curriculum.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?= $modelClase->paralelo->course->name ?>
                        -
                        <?= $modelClase->paralelo->name ?>
                        -
                        <?= $modelClase->ismAreaMateria->materia->nombre ?>
                        -
                        <?= $modelClase->profesor->last_name ?>
                        <?= $modelClase->profesor->x_first_name ?>
                    </small>
                </div>
            </div>
            <hr>


            <div class="row">
                <div class="col-lg-10 col-md-10">
                    <!-- aqui el menu izquiero -->
                </div>

                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <!-- aqui el menu derecho -->
                </div>
            </div>


            <!-- inicia tabla -->
            <div class="row">

                <div class="d-flex align-items-start" style="background-color: #eee">
                    <div class="nav flex-column nav-pills me-3 card" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="width: 25%;">

                        <?php
                        foreach ($weeks as $week) {
                            ?>
                            <button class="nav-link text-segundo zoom" id="v-pills-home-tab" data-bs-toggle="pill" 
                                    data-bs-target="#v-pills-home" 
                                    type="button" role="tab" 
                                    aria-controls="v-pills-home" 
                                    aria-selected="true" 
                                    onclick="show_detail(<?= $week->id ?>, 'datos')">
                                <?= $week->nombre_semana ?><br>
                                del <?= $week->fecha_inicio ?> al <?= $week->fecha_finaliza ?>
                            </button>
                            <?php
                        }
                        ?>                        

                    </div>
                    <div class="tab-content" id="v-pills-tabContent" style="width: 100%">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                            
                            <!--detalle de los dias de la semana elegida-->
                            <div id="div-datos-semana"></div> 
                            
                            
                            <div class="card shadow p-3 animate__animated animate__bounce" id="div-form-crear"                                  
                                 style="margin-top: 10px; margin-bottom: 10px; display: none; border: solid 1px #0a1f8f">

                                <!--indica el dia-->
                                <div id="div-dia"></div>
                                <!--fin de indica dia-->
                                
                                
                                
<!--incia formulario-->        <?= Html::beginForm(['actividad/index1'], 'get', ['enctype' => 'multipart/form-data']) ?>
                                <input type="hidden" name="accion" value="crear">
                                <input type="hidden" name="clase_id" value="<?= $modelClase->id ?>">
                                <input type="hidden" name="bloque_id" value="<?= $week->bloque_id ?>">
                                <input type="hidden" name="semana_id" value="<?= $week->id ?>">

                                <div class="row" style="padding: 10px">
                                    <div class="col-lg-2 col-md-2">
                                        <div class="form-group">
                                            <label for="inicio">Fecha de entrega: </label><br>
                                            <label id="label-inicio"></label>
                                            <input type="hidden" name="inicio" id="input-inicio" class="form-control">
                                            <input type="hidden" name="calificado" value="<?= $calificado ?>"  class="form-control">
                                        </div>                                                                                        
                                    </div>

                                    <div class="col-lg-2 col-md-2">
                                        <div class="form-group">
                                            <label for="tipo_calificacion">Tipo de calificación: </label>
                                            <select name="tipo_calificacion" id="select-tipo-cal" onchange="show_insumos(this, <?= $week->id ?>)" required="" class="form-control">
                                                <option value="" selected="">Seleccione tipo calificación...</option>
                                                <?php
                                                if ($modelClase->paralelo->course->section0->code == 'PAI') {
                                                    ?>
                                                    <option value="P">PAI</option>
                                                    <?php
                                                }
                                                ?>
                                                <option value="N">NACIONAL</option>
                                            </select>
                                        </div>                                                                                        
                                    </div>

                                    <div class="col-lg-3 col-md-3">
                                        <div class="form-group">
                                            <label for="tipo_calificacion">Insumos: </label>
                                            <select name="tipo_actividad_id" id="select-tipo-actividad" required="" class="form-control"></select>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-lg-5 col-md-5">
                                        <div class="form-group">
                                            <label for="tipo_calificacion">Título: </label>
                                            <input type="text" name="title" required="" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="padding: 10px">
                                    
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label for="tipo_calificacion">Descripción: </label>
                                            <textarea name="descripcion" required="" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label for="tipo_calificacion">Tareas: </label>
                                            <textarea name="tareas" required="" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    
                                </div>
                                <hr>
                                
                                <div class="row text-center">
                                    
                                    <div class="col"></div>
                                    <div class="col">
                                        <?= Html::submitButton('GRABAR', [
                                            'class' => 'btn bg-primero submit',
                                            'style' => 'width: 100%'
                                        ]) ?>
                                    </div>
                                    <div class="col"></div>                                    
                                </div>
                                
                                <?= Html::endForm() ?>
                                <!--fin de formulario-->

                            </div>
                        </div>                        
                    </div>
                </div>               

            </div>
            <!-- fin de  tabla -->
        </div>
    </div>
</div>


<script>
    function show_detail(semanaId, accion) {
        var url = '<?= Url::to(['actividad/index1']) ?>';
        var claseId = '<?= $modelClase->id ?>';
        var params = {
            clase_id: claseId,
            semana_id: semanaId,
            accion: accion
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                $("#div-datos-semana").html(response);
            }
        });
    }

    function show_form(inicio, dia) {
        $("#div-form-crear").show();
        $("#input-inicio").val(inicio);
        $("#label-inicio").html('<b>'+inicio+'</b>');
        $("#select-tipo-cal").val('');
        $("#select-tipo-actividad").val('');        
        $("#div-dia").html('<h4><b><u>Actividad para el día: '+dia+'</u></b></h4>');        
                
    }

    function show_insumos(obj, semanaId) {
        var tipoCalificacion = obj.value;
        var claseId = '<?= $modelClase->id ?>';
        var url = '<?= Url::to(['actividad/index1']) ?>';
        params = {
            tipo_calificacion: tipoCalificacion,
            semana_id: semanaId,
            clase_id: claseId,
            accion: 'insumos'
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                $('#select-tipo-actividad').html(response);
            }
        });

    }
</script>