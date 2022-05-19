<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Creando un tema para este día';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="comportamiento-nuevotema">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 col-md-8">

            <div class=" row align-items-center p-2">
                <div class="col-lg-1"><h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" style="" class="img-thumbnail"></h4></div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <p>
                        <?=
                        $modelAsistencia->clase->materia->name . ' / ' .
                        $modelAsistencia->clase->course->name . ' "' .
                        $modelAsistencia->clase->paralelo->name . '" / ' .
                        $modelAsistencia->clase->profesor->last_name . " " . $modelAsistencia->clase->profesor->x_first_name . ' / ' .
                        $modelAsistencia->fecha . " / " .
                        $modelAsistencia->hora->sigla . " HORA";
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
                Html::a('<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="far fa-file"></i> Listado</span>',
                        ['index', 'id' => $modelAsistencia->id], ['class' => 'link']);
                ?>                
                |
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Mis asignaturas</span>',
                        ['profesor-inicio/index'], ['class' => 'link']);
                ?>                
                |
            </p>

            <div class="row align-items-center p-5">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'clase_id')->hiddenInput(['value' => $modelAsistencia->clase_id])->label(false) ?>

                <?= $form->field($model, 'hora_id')->hiddenInput(['value' => $modelAsistencia->hora_id])->label(false) ?>

                <?= $form->field($model, 'asistencia_profesor_id')->hiddenInput(['value' => $modelAsistencia->id])->label(false) ?>

                <?= $form->field($model, 'tema')->textInput() ?>

                <?= $form->field($model, 'observacion')->textarea(['rows' => '6']) ?>

                <div class="form-group m-2">
                    <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div><!-- fin de formulario -->

        </div>

    </div>

</div>



<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['/profesor-inicio/index']); ?>
        </li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Novedades alumno', ['detalle', 'asistenciaId' => $modelAsistencia->id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>



<div class="comportamiento-nuevotema">

    <div class="container">





    </div>

</div>