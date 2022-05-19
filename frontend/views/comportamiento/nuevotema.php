<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Nuevo tema de: '. $modelAsistencia->clase->materia->name.' / '.
            $modelAsistencia->clase->course->name.' "'.
            $modelAsistencia->clase->paralelo->name.'" / '. 
            $modelAsistencia->clase->profesor->last_name." ".$modelAsistencia->clase->profesor->x_first_name.' / '.
            $modelAsistencia->fecha." / ".
            $modelAsistencia->hora->sigla." HORA";
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['/profesor-inicio/index']); ?>
        </li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Novedades alumno', ['detalle','asistenciaId' => $modelAsistencia->id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>



<div class="comportamiento-nuevotema">

    <div class="container">
        
    
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'clase_id')->hiddenInput(['value' => $modelAsistencia->clase_id])->label(false) ?>

    <?= $form->field($model, 'hora_id')->hiddenInput(['value' => $modelAsistencia->hora_id])->label(false) ?>

    <?= $form->field($model, 'asistencia_profesor_id')->hiddenInput(['value' => $modelAsistencia->id])->label(false) ?>
    
    <?= $form->field($model, 'tema')->textInput() ?>
        
    <?= $form->field($model, 'observacion')->textarea(['rows' => '6']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

        </div>
        
</div>