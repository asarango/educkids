<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\ResUsers;
use kartik\select2\Select2;
use backend\models\ScholarisTipoActividad;
use backend\models\ScholarisHorariov2Horario;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisActividad */
/* @var $form yii\widgets\ActiveForm */


$hoy = date("Y-m-d H:i:s");
$usuarioLogueado = Yii::$app->user->identity->usuario;
$modelUsuarios = ResUsers::find()->where(['login' => $usuarioLogueado])->one();

$usuario = $modelUsuarios->id;
$this->title = 'Datos de la nueva Actividad';

//print_r($modelSemana);
//die();


if ($modelSemana <> '0') {
    $semanaDet = $modelSemana->id;
} else {
    $semanaDet = 0;
}


//if($modelSemana == 0){
//    $semanaDet = 0;
//}else{
//    $semanaDet = $modelSemana->id;
//}
?>


<div class="scholaris-actividad-_formcreate">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/curriculum.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <p>
                        <?=
                        ' <small>' . $modelClase->materia->name .
                            ' - ' .
                            'Clase #:' . $modelClase->id .
                            ' - ' .
                            $modelClase->course->name .
                            ' - ' .
                            $modelClase->paralelo->name .
                            ' - ' .
                            $modelClase->profesor->x_first_name .
                            ' ' .
                            $modelClase->profesor->last_name .
                            ' ' .
                            '</small>';
                        ?>

                        <br>

                    </p>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="far fa-file"></i> Detalle semana</span>',
                        [
                            'create', 'claseId'     => $modelClase->id,
                            'bloqueId'    => $bloque,
                            'week_id'   => $modelSemana->id
                        ],
                        ['class' => 'link']
                    );
                    ?>
                    |


                </div>
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- Aqui menu auxiliar -->
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>

            <!-- inicia cuerpo -->
            <div class="row" style="">

                <div class="col-lg-2 col-md-2"></div>
                <div class="col-lg-8 col-md-8">
                    <?php $form = ActiveForm::begin(); ?>

                    <!--ocultos-->

                    <?= $form->field($model, 'create_date')->hiddenInput(['value' => $hoy])->label(FALSE) ?>

                    <?= $form->field($model, 'write_date')->hiddenInput(['value' => $hoy])->label(FALSE) ?>

                    <?= $form->field($model, 'create_uid')->hiddenInput(['value' => $usuario])->label(FALSE) ?>

                    <?= $form->field($model, 'write_uid')->hiddenInput(['value' => $usuario])->label(FALSE) ?>

                    <?= $form->field($model, 'color')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

                    <?= $form->field($model, 'inicio')->hiddenInput(['value' => $inicio])->label(FALSE) ?>

                    <?= $form->field($model, 'fin')->hiddenInput(['value' => $inicio])->label(FALSE) ?>

                    <?= $form->field($model, 'bloque_actividad_id')->hiddenInput(['value' => $bloque])->label(FALSE) ?>

                    <?= $form->field($model, 'a_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

                    <?= $form->field($model, 'b_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

                    <?= $form->field($model, 'c_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

                    <?= $form->field($model, 'd_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

                    <?= $form->field($model, 'paralelo_id')->hiddenInput(['value' => $modelClase->id])->label(FALSE) ?>

                    <?= $form->field($model, 'materia_id')->hiddenInput(['value' => $modelClase->idmateria])->label(FALSE) ?>

                    <?= $form->field($model, 'actividad_original')->hiddenInput(['value' => 0])->label(FALSE) ?>

                    <?= $form->field($model, 'semana_id')->hiddenInput(['value' => $semanaDet])->label(FALSE) ?>

                    <?= $form->field($model, 'archivo')->hiddenInput()->label(FALSE) ?>

                    <?= $form->field($model, 'descripcion_archivo')->hiddenInput()->label(FALSE) ?>

                    <?= $form->field($model, 'tipo_calificacion')->hiddenInput(['value' => $tipo])->label(FALSE) ?>

                    <!--fin ocultos-->


                    <div class="row" style="margin: 20px; padding: 10px; border: solid 1px #CCC">
                        <div class="col-lg-12 border-right">
                            <?php
                            $listData = ArrayHelper::map($modelInsumo, 'id', 'nombre_nacional');
                            echo $form->field($model, 'tipo_actividad_id')->widget(Select2::className(), [
                                'data' => $listData,
                                'options' => ['placeholder' => 'Seleccione Insumo...', ],
                                'pluginLoading' => false,
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],

                            ]);
                            ?>

                            <?= $form->field($model, 'calificado')->dropDownList(['SI' => 'SI', 'NO' => 'NO']) ?>

                            <?php
                            $listData = ArrayHelper::map($horas, 'id', 'sigla');
                            echo $form->field($model, 'hora_id')->dropDownList($listData, ['prompt' => 'Seleccione Hora...'])
                            ?>

                            <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('TÍTULO - ENSEÑANZA') ?>
                            <?= $form->field($model, 'descripcion')->textarea(['rows' => '3'])->label('DESCRIPCIÓN - ACTIVIDADES') ?>
                            <?= $form->field($model, 'tareas')->textarea(['rows' => '3'])->label('TAREAS') ?>


                            <?php
                            //            echo count($modelVideoConf);

                            if (count($modelVideoConf) == 1) {
                                echo $form->field($model, 'videoconfecia')->textInput(['value' => $modelVideoConf[0]->videoconfecia])->label('LINK DE VIDEOCONFERENCIA');
                            } elseif (count($modelVideoConf) > 1) {
                            ?>
                                <!-- Boton -->
                                <button data-toggle="modal" href="#mi_modal" class="btn btn-primary">Copiar link de video conferencia</button>
                                <br><br>
                                <?php echo $form->field($model, 'videoconfecia')->textInput()->label('LINK DE VIDEOCONFERENCIA'); ?>
                                <!-- Link -->
                                <!--<a data-toggle="modal" href="#mi_modal">Abrir ventana modal</a>-->

                                <!-- si se necesita cambiar tamaño de modal agregar modal-lg a la linea 
        <div class="modal-dialog"> por <div class="modal-dialog modal-lg">-->
                            <?php
                            } else {
                                echo $form->field($model, 'videoconfecia')->textInput()->label('LINK DE VIDEOCONFERENCIA');
                            }
                            ?>


                            <hr>

                            <?php
                            // if (count($modelAulas) > 0) {
                            //     echo $form->field($model, 'link_aula_virtual')->textInput(['value' => $modelAulas->link_aula_virtual])->label('LINK DE AULA VIRTUAL');
                            // } else {
                            //     echo $form->field($model, 'link_aula_virtual')->textInput(['maxlength' => true])->label('LINK DE AULA VIRTUAL');
                            // }
                            ?>

                            <?php // $form->field($model, 'link_aula_virtual')->textInput(['maxlength' => true])->label('LINK DE AULA VIRTUAL') 
                            ?>
                        </div>


                    </div>

                    <div class="row" style="margin: 20px;">
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-outline-success']) ?>
                    </div>


                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-lg-2 col-md-2"></div>
            </div>
            <!-- fin de cuerpo -->
        </div>
    </div>





    <div class="container">
        <br>


        <!-- Modal-->
        <div class="modal fade" id="mi_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Elija el link de la Videoconferencia</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="padding:15px">



                            <ul>
                                <?php
                                foreach ($modelVideoConf as $data) {
                                ?>
                                    <li><a href="#" onclick="copiarVideoConferencia('<?= $data->videoconfecia  ?>')" data-dismiss="modal"><?= $data->videoconfecia ?></a></li>
                                <?php
                                }
                                ?>

                            </ul>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function copiarVideoConferencia(videoConferencia) {
            //alert(videoConferencia);

            $('#scholarisactividad-videoconfecia').val(videoConferencia);


        }
    </script>