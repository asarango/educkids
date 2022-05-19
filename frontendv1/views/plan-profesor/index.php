<?php

use yii\helpers\Html;
use backend\models\PlanPduCabecera; 

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificaciones de unidad por cursos y paralelos';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
        <?php echo Html::a('Inicio', ['profesor-inicio/index']); ?>
        </li>
        <!-- <li class="breadcrumb-item">
            <?php //echo Html::a('Regresar', ['create', "claseId" => $modelClase->id, 'bloqueId' => $bloque]); ?>
        </li> -->
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>
<div class="portal-inicio-index">

    <div class="container tamano10">

        <div class="row">

            <div class="col-md-2">ASIGNATURA</div>
            <div class="col-md-1">CURSO</div>
            <div class="col-md-1">PARALELO</div>

            <?php
                foreach($modelBloque as $bloque){
                    echo '<div class="col-md-1">'.$bloque->abreviatura.'</div>';
                }
            ?>
        </div>
        <hr>
        

        <?php
            foreach($modelClases as $clase){
                echo '<div class="row">';
                
                 echo '<div class="col-md-2 cabFondo">'.$clase->materia->name.'('.$clase->id.')'.'</div>';
                 echo '<div class="col-md-1 cabFondo">'.$clase->curso->name.'</div>';
                 echo '<div class="col-md-1 cabFondo">'.$clase->paralelo->name.'</div>';

                 foreach($modelBloque as $bloque){

                    $modelPlan = PlanPduCabecera::find()
                                ->where(['clase_id' => $clase->id, 'bloque_id' => $bloque->id])
                                ->one();

                    if($modelPlan){
                        echo '<div class="col-md-1">';
                        echo Html::a($modelPlan->estado,['plan-pdu-cabecera/index1','id' => $modelPlan->id]);
                        echo '</div>';
                    }else{
                        echo '<div class="col-md-1">';
                        echo Html::a('sin planificar',['plan-pdu-cabecera/create','clase_id' => $clase->id, 'bloque_id' => $bloque->id],['class' => 'text-danger']);
                        echo '</div>';
                    }
                    
                 }

                echo '</div>';
                echo '<hr>';
            }
        ?>


    </div>
</div>
