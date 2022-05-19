<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revisión de archivos enviados';
//$this->params['breadcrumbs'][] = $this->title;


$fecha = date('Y-m-d H:i:s');

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <!--<li class="breadcrumb-item"><a href="#">Home</a></li>-->
        <li class="breadcrumb-item">
            <?php echo Html::a('Acciones de calificaciones', ['anularcalificaciones', "id" => $modelActividad->id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="scholaris-actividad-verarchivos">

    <div class="alert alert-info">
        <strong><?= $modelActividad->title ?> / </strong>
        <strong><?= $modelAlumno->last_name. ' '. $modelAlumno->first_name. ' '. $modelAlumno->middle_name ?></strong>
    </div>
    
    <div class="container">
        <div class="table table-responsive">
            <table class="table table-hover table-condensed table-striped table-borderless">
                <thead>
                    <tr>
                        <th>FECHA SUBIDA</th>
                        <th>ARCHIVO</th>
                        <th>OBSERVACIÓN</th>
                        <th>ACCIÓN</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    
                    foreach ($modelEntregados as $entregados){
                        echo '<tr>';
                        echo '<td>'.$entregados->creado_fecha.'</td>';
                        echo '<td>'.$entregados->actividad->title.'</td>';
                        
                        if($entregados->creado_fecha <= $entregados->actividad->inicio){
                            echo '<td bgcolor="#66d189">Entregado a tiempo</td>';
                        }else{
                            echo '<td bgcolor="#FF0000">----</td>';
                        }
                        
                        
                        echo '<td>';
                        echo Html::a('Descargar', ['descargar', "ruta" => $entregados->archivo], ['class' => 'card-link text-link']);
                        echo '</td>';
                        
                        echo '</tr>';
                    }
                    
                    ?>
                </tbody>
                
            </table>
        </div>
    </div>
    
    
</div>