<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Novedades de comportamiento ';
//$this->params['breadcrumbs'][] = $this->title;

?>

<div class="comportamiento-detalle">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-9 col-md-9">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <b><?= ' (' . $modelGrupo->alumno->last_name . ' ' . $modelGrupo->alumno->first_name . ')'; ?></b>
                    <p>
                        <?=
                        $modelAsistencia->clase->materia->name . ' / ' .
                            $modelAsistencia->clase->course->name . ' "' .
                            $modelAsistencia->clase->paralelo->name . '" / ' .
                            $modelAsistencia->clase->profesor->last_name . " " . $modelAsistencia->clase->profesor->x_first_name . ' / ' .
                            $modelAsistencia->fecha . " / " .
                            $modelAsistencia->hora->sigla . " HORA"
                        ?>
                    </p>
                </div>
            </div>
            <hr>

            <p>
                |
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>
                |
                <?=
                Html::a(
                    '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="far fa-file"></i> Listado</span>',
                    ['index', 'id' => $modelAsistencia->id],
                    ['class' => 'link']
                );
                ?>
                |
                <!--?=
                Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Mis asignaturas</span>',
                        ['profesor-inicio/index'], ['class' => 'link']);
                ?-->

            </p>


            <div class="row" style="margin-bottom: 40px">

                <div class="col-lg-4 col-md-4 my-text-small">

                    <div class="mimenu">
                        <ul class="uno">

                            <?php
                            foreach ($modelComportamientos as $comp) {
                            ?>
                                <br>
                                <li><a href="#" style="color:#ab0a3d"><?= $comp->nombre ?></a>
                                    <ul class="dos">
                                        <?php
                                        foreach ($modelCompDetalle as $det) {
                                            //<li><a href="#">Historia</a></li>
                                            if ($comp->id == $det->comportamiento_id) {
                                                echo '<li>';                                               
                                                /*echo Html::a($det->codigo . ' - ' . $det->nombre, [
                                                    'asignar',
                                                    "asistenciaId" => $modelAsistencia->id,
                                                    "detalleId" => $det->id,
                                                    "grupoId" => $modelGrupo->id,
                                                ], ['class' => '']);*/
                                                ?>
                                                <a href="#" style="font-size:15px;" onclick="asignaComportamiento(<?php echo $det->id ?>,'<?php echo ( $det->nombre)?>')"><?php echo ($det->codigo . ' - ' . $det->nombre)?></a>
                                                <?php
                                                echo '</li>';
                                            }
                                        }
                                        ?>

                                    </ul>
                                </li>

                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="row" style="margin-bottom: 50px">  
                            <table clase="table table-condensed table-striped table-hover my-text-small">
                                <tr>
                                    <td><b>Comportamiento Seleccionado:</b></td>
                                </tr>
                                <tr>
                                    <td><label id="txt_seleccion_comp">N/A</label></td>
                                </tr>
                            </table>                   
                            <?php $form = ActiveForm::begin(); ?>
                                <?= $form->field($model, 'asistencia_profesor_id')->hiddenInput(['value' => $modelAsistencia->id])->label(false) ?>
                                <?= $form->field($model, 'comportamiento_detalle_id')->hiddenInput()->label(false) ?>                                
                                <?= $form->field($model, 'observacion')->textarea(array('rows' => 3, 'cols' => 4))->label('Ingrese Observación') ?>
                                <?= $form->field($model, 'grupo_id')->hiddenInput(['value' => $modelGrupo->id])->label(FALSE) ?>                                

                                <div class="form-group m-2">
                                    <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
                                </div>
                            <?php ActiveForm::end(); ?>
                    </div><!-- fin de formulario -->
                    <div class="row" style="margin-bottom: 30px">
                        <div class="table table-responsive shadow" style="height: 190px; overflow-y: scroll">
                            <table class="table table-condensed table-striped table-hover my-text-small">
                                <thead>
                                    <tr style="background-color: #898b8d; font-size:15px">
                                        <th style="color:#ab0a3d">Código</th>
                                        <th style="color:#ab0a3d">Detalle</th>
                                        <th style="color:#ab0a3d">Observación</th>
                                        <th style="color:#ab0a3d">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($modelNovedades as $novedad) {

                                        echo '<tr style="font-size:15px;">';
                                            echo '<td>' . $novedad->comportamientoDetalle->codigo . '</td>';
                                            echo '<td>' . $novedad->comportamientoDetalle->nombre . '</td>';
                                            echo '<td>' . $novedad->observacion . '</td>';

                                            echo '<td class="text-center">';
                                            echo Html::a('<i class="fas fa-ban"></i>', ['quitar', "novedadId" => $novedad->id], ['class' => 'link', 'title' => 'Eliminar']);
                                            echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function asignaComportamiento(id,descri)
    {
        //alert(id);
        $('#scholarisasistenciaalumnosnovedades-comportamiento_detalle_id').val(id);
        $('#txt_seleccion_comp').html('<h5 style="background-color: white;color:#ab0a3d">'+descri+'</h5>');
    }

</script>