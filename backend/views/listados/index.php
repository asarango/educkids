<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'LISTADO DEL: '.$modelParalelo->course->name.' '.$modelParalelo->name;
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listados-index" style="padding-left: 40px; padding-right: 40px">
    

        
        <div class="well">
            <?php echo Html::beginForm(['reporte', 'post']); ?>
            
            <div class="row">
                <input type="checkbox" name="email_student"> Correo del Estudiante
                <input type="checkbox" name="grupo_sangre"> Grupo Sangre
                <input type="checkbox" name="cont_emergencia"> Contacto de emergencia
                <input type="checkbox" name="cumpleanios"> Cumpleaños
                <input type="checkbox" name="nacionalidad"> Nacionalidad
                <input type="checkbox" name="genero"> Genero
                <input type="checkbox" name="numero_identificacion"> Numero de identificacion
                <input type="checkbox" name="estado"> Estado
                <input type="checkbox" name="calle_secundaria"> Calle Secundaria
                <input type="checkbox" name="numero"> Numero
                <input type="checkbox" name="calle_principal"> Calle Principal
                <input type="checkbox" name="quien_respresenta"> Quien Representa
                <input type="checkbox" name="representante"> Representante
                <input type="checkbox" name="rep_cedula"> Cedula Representante
                <input type="checkbox" name="rep_telefono"> Telefono Representante
                <input type="checkbox" name="rep_celular">Celular Representante
                <input type="checkbox" name="rep_direccion">Direccion Representante
                <input type="checkbox" name="rep_correo">Correo Representante
                <input type="checkbox" name="padre">Padre
                <input type="checkbox" name="madre">Madre
            </div>
            <hr>
            <div class="row">
                
                <strong><h6><u>Añadir campos vacíos:</u></h6></strong>
                
                <input type="checkbox" name="firma"> Firma
                <input type="checkbox" name="obs"> Observación
                <input type="checkbox" name="obs1"> Observación2
            </div>
            
            
            <div class="row">
                
                
                <div class="col-md-3">
                    <select name="repo" class="form-control">
                        <option value="pdf">Exportar PDF</option>
                        <option value="excel">Exportar Excel</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="orientacion" class="form-control">
                        <option value="A4-P">Vertical</option>
                        <option value="A4-L">Horizontal</option>
                    </select>
                </div>
                <input type="hidden" name="paralelo" value="<?= $modelParalelo->id ?>">
                <div class="col-md-3"><?php echo Html::submitButton('Aceptar', ['class' => 'btn btn-primary']); ?></div>
                
            </div>
            
            
            <?php echo Html::endForm(); ?>
        </div>
        
        
        
    <p>
    <?php // echo Html::a('Exportar PDF', ['pdf','paralelo' => $modelParalelo->id], ['class' => 'btn btn-danger']) ?>
    <?php // echo Html::a('Exportar Excel', ['excel','paralelo' => $modelParalelo->id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <hr>
    
    <div class="table table-responsive">
        <table class="table table-striped table-condensed table-hover tamano10P">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ESTUDIANTE</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i=0;
                foreach ($modelAlumnos as $alumno){
                    
                    echo '<tr>';
                    
                    
                    if($alumno['inscription_state'] == 'M'){
                        $i++;
                        echo '<td>'.$i.'</td>';
                        echo '<td>'.$alumno['last_name'].' '.$alumno['first_name'].' '.$alumno['middle_name'].'</td>';
                        echo '<td>MATRICULADO</td>';
                    } elseif($alumno['inscription_state'] == 'R'){
                        $i++;
                        echo '<td>'.$i.'</td>';
                        echo '<td>'.$alumno['last_name'].' '.$alumno['first_name'].' '.$alumno['middle_name'].'</td>';
                        echo '<td>RETIRADO</td>';
                    }
                    
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    
    

    
</div>


