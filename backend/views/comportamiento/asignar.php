<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Registro de comportamiento estudiantil';
//$this->params['breadcrumbs'][] = $this->title;
?>


<div class="comportamiento-asignar">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 col-md-8">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1"><h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" style="" class="img-thumbnail"></h4></div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>                   
                    <p><b><?= $modelGrupo->alumno->last_name . ' ' . $modelGrupo->alumno->first_name; ?></b></p>
                </div>
            </div>
            <hr>

            <p>
                |                                
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>                
                |
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="far fa-file"></i> Novedades</span>',
                        ['detalle', 'asistenciaId' => $modelAsistencia->id, 'alumnoId' => $modelGrupo->estudiante_id], ['class' => 'link']);
                ?>                
                |
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Mis asignaturas</span>',
                        ['profesor-inicio/index'], ['class' => 'link']);
                ?>                
                |
            </p>

            <div class="row" style="margin: 20px">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'asistencia_profesor_id')->hiddenInput(['value' => $modelAsistencia->id])->label(FALSE) ?>

                    <?= $form->field($model, 'comportamiento_detalle_id')->hiddenInput(['value' => $detalleId])->label(FALSE) ?>

                    <?= $form->field($model, 'observacion')->textarea(array('rows' => 5, 'cols' => 5)) ?>

                    <?= $form->field($model, 'grupo_id')->hiddenInput(['value' => $modelGrupo->id])->label(FALSE) ?>

                    <div class="form-group m-2">
                        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div><!-- fin de formulario -->


        </div>

    </div>

</div>