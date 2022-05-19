<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisComportamientoInicialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Calificación de comportamiento de inciales y preparatoria';
$this->params['breadcrumbs'][] = ['label' => 'Paralelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-comportamiento-inicial-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

      
    
    <div class="container">
        <div class="table table-responsive">
            <table class="table table-hover table-striped table-condensed">
                <tr>
                    <td align="center"><strong>ORD</strong></td>
                    <td align="center"><strong>ESTUDIANTE</strong></td>
                    <td align="center"><strong>Q1</strong></td>
                    <td align="center"><strong>Q2</strong></td>
                    <td align="center"><strong>ACCIONES</strong></td>
                </tr>
                
                <?php
                $i = 0;
                foreach ($modelAlumnos as $alumno){
                    $i++;
                    ?>
                <tr>
                    <td align="center"><?= $i ?></td>
                    <td align=""><?= $alumno['last_name'].' '.$alumno['first_name'].' '.$alumno['middle_name'] ?></td>
                    <td align="center"><?= $alumno['q1'] ?></td>
                    <td align="center"><?= $alumno['q2'] ?></td>
                    <td align="center"> <?= Html::a('Cambiar Nota', ['update','id' => $alumno['id']], ['class' => 'btn btn-primary']) ?></td>
                </tr>
                <?php
                }
                ?>                                
            </table>
        </div>
    </div>


</div>
