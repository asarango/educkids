<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Informes de Aprendizaje y Comportamiento: ' . $modelParalelo->course->name . ' - ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = ['label' => 'Listado de cursos y paralelos', 'url' => ['informes-aprendizaje/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="informes-aprendizaje-informes">

    <h4><?php //echo Html::encode($this->title)     ?></h4>

    <div class="container">

        <div class="panel panel-warning">
            <div class="panel-heading">CUADROS PARA JUNTAS DE GRADO / CURSO</div>
            <div class="panel-body">
                <?php
//                echo $modelParalelo->course->section0->code;
                if ($modelParalelo->course->section0->code == 'PREPARATORIA' || $modelParalelo->course->section0->code == 'PRES' || $modelParalelo->course->section0->code == 'INICIAL') {
                    echo '<p>No hay reportes</p>';
                }else{
                    ?>
                 <div class="row">

                        <div class="col-md-6">

                            <ul>
                                <li><?= Html::a('Informe de Primer Quimestre', ['informedireccion', 'paralelo' => $modelParalelo->id, 
                                                                                'quimestre' => 'q1', 'reporte' => 'sabanaquimestral']
                                        , ['class' => 'btn btn-link']); ?>
                                </li>
                                
                                <li><?= Html::a('Informe Final', ['informedireccion', 'paralelo' => $modelParalelo->id, 
                                                                                'quimestre' => 'final', 'reporte' => 'sabanaquimestral']
                                        , ['class' => 'btn btn-link']); ?>
                                </li>
                                
                                <li><?= Html::a('Sabana Primer Quimestre', ['informedireccion', 'paralelo' => $modelParalelo->id, 
                                                                                'quimestre' => 'q1', 'reporte' => 'sabanaquimestralexcel']
                                        , ['class' => 'btn btn-link']); ?>
                                </li>
                                
                                <li><?= Html::a('Sabana Segundo Quimestre', ['informedireccion', 'paralelo' => $modelParalelo->id, 
                                                                                'quimestre' => 'q2', 'reporte' => 'sabanaquimestralexcel']
                                        , ['class' => 'btn btn-link']); ?>
                                </li>
                            </ul>
                            
                        </div>
                 </div>
                
                <?php
                }
                ?>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">INFORMES DE APRENDIZAJE</div>
            <div class="panel-body">

                <?php
//                echo $modelParalelo->course->section0->code;
                if ($modelParalelo->course->section0->code == 'PREPARATORIA' || $modelParalelo->course->section0->code == 'PRES' || $modelParalelo->course->section0->code == 'INICIAL') {

                    echo Html::a('Primer Quimestre - Iniciales - Primeros', [
                        'informedireccion', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'QUIMESTRE I',
                        'reporte' => 'q1inicial'
                            ], ['class' => 'btn btn-link']);
                    echo '<br>';

                    echo Html::a('Segundo Quimestre - Iniciales - Primeros', [
                        'informedireccion', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'QUIMESTRE II',
                        'reporte' => 'q1inicial'
                            ], ['class' => 'btn btn-link']);
                } else {
                    ?>
                    <div class="row">

                        <div class="col-md-6">

                            <ul>


                                <li><?= Html::a('Parcial 1', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'p1', 'reporte' => 'libquimestral'], ['class' => 'btn btn-link']); ?></li>
                                <li><?= Html::a('Parcial 2', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'p2', 'reporte' => 'libquimestral'], ['class' => 'btn btn-link']); ?></li>
                                <li><?= Html::a('Parcial 3', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'p3', 'reporte' => 'libquimestral'], ['class' => 'btn btn-link']); ?></li>
                                <li><?= Html::a('QUIMESTRE 1', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'q1', 'reporte' => 'libquimestral'], ['class' => 'btn btn-link']); ?></li>


                                <li><?= Html::a('Parcial 4', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'p4', 'reporte' => 'libquimestral'], ['class' => 'btn btn-link']); ?></li>
                                <li><?= Html::a('Parcial 5', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'p5', 'reporte' => 'libquimestral'], ['class' => 'btn btn-link']); ?></li>
                                <li><?= Html::a('Parcial 6', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'p6', 'reporte' => 'libquimestral'], ['class' => 'btn btn-link']); ?></li>
                                <li><?= Html::a('QUIMESTRE 2', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'q2', 'reporte' => 'libquimestral'], ['class' => 'btn btn-link']); ?></li>
                            </ul>

                        </div>

                        <div class="col-md-6">
                            <ul>
                                <li><?php echo Html::a('Informe dos quimestres', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'total', 'reporte' => 'total'], ['class' => 'btn btn-link']); ?></li>
                                <li><?php echo Html::a('Informe Total', ['informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'total', 'reporte' => 'total2'], ['class' => 'btn btn-link']); ?></li>
                            </ul>
                        </div>


                    </div>

                    <?php
                }
                ?>

                <?php //Html::a('Primer Quimestre - INICIAL', ['index1','id' => $modelParalelo->id,'parcial' => $blo->orden], ['class' => 'btn btn-link']); ?>
            </div>
        </div>


    </div>


</div>
