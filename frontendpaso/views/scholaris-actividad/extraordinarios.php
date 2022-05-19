<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\ResUsers;
use kartik\select2\Select2;
use backend\models\ScholarisTipoActividad;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisActividad */
/* @var $form yii\widgets\ActiveForm */


$fecha = date("Y-m-d H:i:s");



$this->title = 'Calificaciones Extraordinarias Alumno: ' . $modelLibreta->grupo->alumno->last_name . ' ' . $modelLibreta->grupo->alumno->first_name . ' / ' .
        'Calse:' . $modelLibreta->grupo->clase_id . ' / ' .
        'Materia:' . $modelLibreta->grupo->clase->materia->name . ' / ' .
        'Promedio Normal:' . $modelLibreta->final_ano_normal . ' / '
;
?>

<div class="scholaris-actividad-extraordinarios">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item">
                <?php echo Html::a('Sábana Profesor', ['reporte-sabana-profesor/index1', "id" => $modelLibreta->grupo->clase_id]); ?>
            </li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
        </ol>
    </nav>

    <div class="container">

        <?php
        if ($modelLibreta->final_ano_normal >= $minima) {
            
            echo '<h3><p class="text-success">Calificación de mejoras quimestrales</p></h3>';
            
            echo Html::beginForm(['extraordinarios', 'post']);
            
            echo '<label class="control-label">Mejora quimestre 1:</label>';
            echo '<input type="text" name="mejora_q1" class="form-control" value="'.$modelLibreta->mejora_q1.'">';
            
            echo '<label class="control-label">Mejora quimestre 2:</label>';
            echo '<input type="text" name="mejora_q2" class="form-control" value="'.$modelLibreta->mejora_q2.'">';      
            
            echo '<input type="hidden" name="supletorio" class="form-control" value="'.$modelLibreta->supletorio.'">';
            echo '<input type="hidden" name="remedial" class="form-control" value="'.$modelLibreta->remedial.'">';
            echo '<input type="hidden" name="gracia" class="form-control" value="'.$modelLibreta->gracia.'">';
            
            echo '<input type="hidden" name="grupo" class="form-control" value="'.$modelLibreta->grupo->id.'">';

            echo Html::submitButton('Aceptar',['class' => 'btn btn-primary']);
            
            echo Html::endForm();
        }else{
            echo '<h3><p class="text-warning">Calificación de examenes extraordinarios</p></h3>';
            echo Html::beginForm(['extraordinarios', 'post']);
            
            echo '<label class="control-label">Supletorio:</label>';
            if($modelSupletorio->estado == 'activo' && $modelRemedial->estado == 'inactivo' && $modelGracia->estado == 'inactivo'){
                echo '<input type="text" name="supletorio" class="form-control" value="'.$modelLibreta->supletorio.'">';
            }else{
                echo '<input type="hidden" name="supletorio" class="form-control" value="'.$modelLibreta->supletorio.'">';
                echo $modelLibreta->supletorio.'(Examen inactivo)<br>';
            }
            
                        
            echo '<label class="control-label">Remedial:</label>';
            if($modelRemedial->estado == 'activo' && $modelGracia->estado == 'inactivo' && $modelSupletorio->estado == 'inactivo'){
                echo '<input type="text" name="remedial" class="form-control" value="'.$modelLibreta->remedial.'">';
            }else{
                echo '<input type="hidden" name="remedial" class="form-control" value="'.$modelLibreta->remedial.'">';
                echo $modelLibreta->remedial.'(Examen inactivo)<br>';
            }
            
            
            echo '<label class="control-label">Gracia:</label>';
            if($modelGracia->estado == 'activo' && $modelRemedial->estado == 'inactivo' && $modelSupletorio->estado == 'inactivo'){
                echo '<input type="text" name="gracia" class="form-control" value="'.$modelLibreta->gracia.'">';
            }else{
                echo '<input type="hidden" name="gracia" class="form-control" value="'.$modelLibreta->gracia.'">';
                echo $modelLibreta->gracia.'(Examen inactivo)<br>';
            }
            
            echo '<input type="hidden" name="mejora_q1" class="form-control" value="'.$modelLibreta->mejora_q1.'">';
            echo '<input type="hidden" name="mejora_q2" class="form-control" value="'.$modelLibreta->mejora_q2.'">';
            
            echo '<input type="hidden" name="grupo" class="form-control" value="'.$modelLibreta->grupo->id.'">';
            
            echo '<br>';

            echo Html::submitButton('Aceptar',['class' => 'btn btn-warning']);
            
            echo Html::endForm();
        }
        ?>
        
    </div>
</div>
