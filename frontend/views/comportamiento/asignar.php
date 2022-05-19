<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = 'Registro de comportamiento estudiantil: '.$modelGrupo->alumno->last_name.' '.$modelGrupo->alumno->first_name;
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['/profesor-inicio/index']); ?>
        </li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Novedades alumno', ['detalle','asistenciaId' => $modelAsistencia->id, 'alumnoId' => $modelGrupo->estudiante_id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="comportamiento-asignar">

    <div class="container">
<h5><?=     $modelGrupo->clase->materia->name.' / '.
            $modelGrupo->clase->course->name.' "'.
            $modelGrupo->clase->paralelo->name.'" / '. 
            $modelGrupo->clase->profesor->last_name." ".$modelAsistencia->clase->profesor->x_first_name.' / '.
            $modelAsistencia->fecha." / ".
            $modelAsistencia->hora->sigla." HORA"
            
        ?>
    </h5>


        
    
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'asistencia_profesor_id')->hiddenInput(['value' => $modelAsistencia->id])->label(FALSE) ?>

    <?= $form->field($model, 'comportamiento_detalle_id')->hiddenInput(['value' => $detalleId])->label(FALSE) ?>

    <?= $form->field($model, 'observacion')->textarea(array('rows'=>5,'cols'=>5)) ?>
    
    <?= $form->field($model, 'grupo_id')->hiddenInput(['value' => $modelGrupo->id])->label(FALSE) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

        </div>
        
</div>