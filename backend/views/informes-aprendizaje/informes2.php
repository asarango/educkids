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




<style>
    .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('images/pageLoader.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
    }
</style>
<div class="informes-aprendizaje-informes">

    <h4><?php //echo Html::encode($this->title)         ?></h4>


    <div class="table table-responsive" style="padding-left: 40px; padding-right: 40px">
        <table class="table table-hover table-condensed table-striped">
            <tr>
                <td><strong>REPORTE</strong></td>
                <td><strong>TIPO</strong></td>
                <td><strong>ACCIÓN</strong></td>
            </tr>

            <tr>
                <td>INFORME DE APRENDIZAJE QUIMESTRE 1</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'LIBRETAQ1']
                            , ['class' => 'btn btn-primary']);
                    ?>

            </tr>

            <tr>
                <td>INFORME DE APRENDIZAJE QUIMESTRE 1(VERSION 1)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'LIBRETAQ1V1']
                            , ['class' => 'btn btn-default']);
                    ?>

            </tr>

            <tr>
                <td>INFORME DE APRENDIZAJE QUIMESTRE 2(VERSION 1)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q2', 'reporte' => 'LIBRETAQ1V1']
                            , ['class' => 'btn btn-default']);
                    ?>

            </tr>

            <tr>
                <td>LIBRETA DE CALIFICACIONES Q1 V1-AQIA(VERSION ANTERIOR)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'LIBRETAQ1ISM']
                            , ['class' => 'btn btn-warning']);
                    ?>

            </tr>

            <tr>
                <td>LIBRETA DE CALIFICACIONES Q2 V1-AQIA(VERSION ANTERIOR)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'LIBRETAQ2ISM']
                            , ['class' => 'btn btn-success']);
                    ?>

            </tr>

            <tr>
                <td>SABANA QUIMESTRE 1 (PDF)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'PRUEBA']
                            , ['class' => 'btn btn-danger boton']);
                    ?>
                </td>
            </tr>

            <tr>
                <td>SABANA QUIMESTRE 2 (PDF)</td>
                <td>Por paralelo</td>

                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q2', 'reporte' => 'PRUEBA']
                            , ['class' => 'btn btn-danger boton']);
                    ?>
                </td>

            </tr>

            <tr>
                <td>SABANA FINAL (PDF)</td>
                <td>Por paralelo</td>

                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'final_ano_normal', 'reporte' => 'PRUEBA']
                            , ['class' => 'btn btn-danger boton']);
                    ?>
                </td>

            </tr>
            
            <tr>
                <td>SABANA FINAL CON SUPLETORIOS(PDF)</td>
                <td>Por paralelo</td>

                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'final_total', 'reporte' => 'FINALCONSUPLETORIOS']
                            , ['class' => 'btn btn-warning boton']);
                    ?>
                </td>

            </tr>
            
            <tr>
                <td>SABANA FINAL (Hoja Electrónica)</td>
                <td>Por paralelo</td>

                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'final_ano_normal', 'reporte' => 'PRUEBAEXCEL']
                            , ['class' => 'btn btn-primary boton']);
                    ?>
                </td>

            </tr>


            <tr>
                <td>SABANA QUIMESTRE 1(Sólo calificación normal - Formato Hoja Electrónica)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'SABANAQ1EXCEL']
                            , ['class' => 'btn btn-info']);
                    ?>
                </td>
            </tr>

            <tr>
                <td>SABANA QUIMESTRE 2(Sólo calificación normal - Formato Hoja Electrónica)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q2', 'reporte' => 'SABANAQ1EXCEL']
                            , ['class' => 'btn btn-success']);
                    ?>
                </td>
            </tr>


        </table>




    </div>

    <hr>

    <h4><strong>Sabanas Parciales</strong></h4>

    
    
    
    
    <div class="table table-responsive" style="padding-left: 40px; padding-right: 40px">
        <table class="table table-hover table-condensed table-striped">
            
            
            <?php 
                 
                if(count($modelBloques)>4){  //para colegios que tienen 3 parciales       
                    
            ?>
            
            <tr>
                <td><strong>REPORTE</strong></td>
                <td><strong>TIPO</strong></td>
                <td><strong>ACCIÓN</strong></td>
            </tr>

            <tr>
                <td>SABANA PARCIAL 1</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p1', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-warning']);
                    ?>

                </td>
            </tr>
            <tr>
                <td>SABANA PARCIAL 2</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p2', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-warning']);
                    ?>

                </td>
            </tr>
            
            
            <tr>
                <td>SABANA PARCIAL 3</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p3', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-warning']);
                    ?>

                </td>
            </tr>

            <tr>
                <td>SABANA EXAMEN 1</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'ex1', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-info']);
                    ?>

                </td>
            </tr>
            
            <tr>
                <td>SABANA PARCIAL 4</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p4', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-warning']);
                    ?>

                </td>
            </tr>
            
            <tr>
                <td>SABANA PARCIAL 5</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p5', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-warning']);
                    ?>

                </td>
            </tr>
            
            <tr>
                <td>SABANA PARCIAL 6</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p6', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-warning']);
                    ?>

                </td>
            </tr>
            
            <tr>
                <td>SABANA EXAMEN 2</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'ex2', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-info']);
                    ?>

                </td>
            </tr>
            
            <?php   }else{  //para colegios que tienen 2 parciales por quimestre       ?>
            <tr>
                <td><strong>REPORTE</strong></td>
                <td><strong>TIPO</strong></td>
                <td><strong>ACCIÓN</strong></td>
            </tr>

            <tr>
                <td>SABANA PARCIAL 1</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p1', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-primary']);
                    ?>

                </td>
            </tr>
            <tr>
                <td>SABANA PARCIAL 2</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p2', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-warning']);
                    ?>

                </td>
            </tr>
            
            <tr>
                <td>SABANA EXAMEN 1</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'ex1', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-info']);
                    ?>

                </td>
            </tr>
            
            
            
            <tr>
                <td>SABANA PARCIAL 3</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p4', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-warning']);
                    ?>

                </td>
            </tr>
            
            <tr>
                <td>SABANA PARCIAL 4</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p5', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-warning']);
                    ?>

                </td>
            </tr>

            <tr>
                <td>SABANA EXAMEN 2</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'ex2', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-info']);
                    ?>

                </td>
            </tr>
            
            
            
            <?php   }         ?>
        </table>
    </div>
    
    <h4><strong>Promedios Generales (PDF - Aplica a calificaciones normal)</strong></h4>
    <div class="table table-responsive" style="padding-left: 40px; padding-right: 40px">
        <table cellspacing="3px">
            <tr>
                
                <?php
                    if(count($modelBloques) > 4){
                        ?>
                        <td><?= Html::a('Parcial 1 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p1']); ?></td>
                        <td><?= Html::a('Parcial 2 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p2']); ?></td>
                        <td><?= Html::a('Parcial 3 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p3']); ?></td>                        
                        <td><?= Html::a('Parcial 4 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p4']); ?></td>                        
                        <td><?= Html::a('Parcial 5 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p5']); ?></td>                        
                        <td><?= Html::a('Parcial 6 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p6']); ?></td>                        
                        <td><?= Html::a('Quimestre 1 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'q1']); ?></td>
                        <td><?= Html::a('Quimestre 2 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'q2']); ?></td>
                <?php
                    }else{
                        ?>
                        <td><?= Html::a('Parcial 1 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p1']); ?></td>
                        <td><?= Html::a('Parcial 2 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p2']); ?></td>
                        <td><?= Html::a('Parcial 3 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p4']); ?></td>                        
                        <td><?= Html::a('Parcial 4 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'p5']); ?></td>
                        <td><?= Html::a('Quimestre 1 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'q1']); ?></td>
                        <td><?= Html::a('Quimestre 2 | ', ['informegeneralpdf','paraleloId' => $modelParalelo->id, 'quimestre' => 'q2']); ?></td>                        
                        
                        <?php
                    }
                ?>
                
                
            </tr>
        </table>
    </div>
</div>

<div class="loader" style="display: none"></div>

