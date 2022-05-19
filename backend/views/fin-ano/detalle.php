<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$sentencias = new backend\models\SentenciasFinAno();



$this->title = 'Cierre de Fin de Año del Curso: ' . $modelParalelo->course->name . ' - ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-detalle">

    <div class="container">

        <div class="row">
            <div class="col-md-3">
                <div class="row well">
                    <?php
                    if (isset($modelTipoRetportes->valor) == 1) {
                        ?>
                        <div class="row">
                            <?= Html::a('QUIMESTRALES (L)', ['quito-reportes-mec-normal-quimestre/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>

                        <div class="row">
                            <?= Html::a('QUIMESTRALES (P)', ['quito-reportes-mec-normal-quimestrep/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>
                        <div class="row">
                            <?= Html::a('FINALES', ['quito-reportes-mec-normal-final/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>
                    
                        <div class="row">
                            <?= Html::a('FINALES UNA HOJA', ['quito-reportes-mec-normal-final-una/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>
                    
                    <!--                        <div class="row">
                        <?php //Html::a('FINALES (1H)', ['quito-reportes-mec-normal-final-una/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                                                </div>-->
                        <div class="row">
                            <?php echo Html::a('REMEDIALES', ['quito-reportes-mec-normal-remedial/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>

                        <div class="row">
                            <?php echo Html::a('GRACIA', ['quito-reportes-mec-normal-gracia/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>

                        <div class="row">
                            <?=
                            Html::a('PROMOCIONES', ['quito-reportes-mec-normal-promocion/index',
                                'paralelo' => $modelParalelo->id, 'conareas' => 'si'], ['class' => 'btn btn-link']);
                            ?>
                        </div>
                        
                        <div class="row">
                            <?=
                            Html::a('PROMOCIONES 2019', ['quito-reportes-mec-normal-promocion-rede/index',
                                'paralelo' => $modelParalelo->id, 'conareas' => 'si'], ['class' => 'btn btn-link']);
                            ?>
                        </div>

                        <div class="row">
                            <?=
                            Html::a('APTITUD', ['quito-reportes-mec-normal-aptitud/index',
                                'paralelo' => $modelParalelo->id, 'conareas' => 'si'], ['class' => 'btn btn-link']);
                            ?>
                        </div>

                        <div class="row">
                            <?=
                            Html::a('NOMINA MATRICULADOS', ['quito-reportes-mec-nomina-matriculados/index',
                                'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']);
                            ?>
                        </div>

                        <?php
                    } else {
                        ?>
                        <div class="row">
                            <?= Html::a('Quimestrales', ['reportes-mec-normal-quimestre/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>
                        <div class="row">
                            <?= Html::a('Finales', ['reportes-mec-normal-final/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>
                        <div class="row">
                            <?= Html::a('Remediales', ['reportes-mec-normal-remedial/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>
                        <div class="row">
                            <?= Html::a('Gracia', ['reportes-mec-normal-gracia/index', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']); ?>
                        </div>

                        <div class="row">
                            <?=
                            Html::a('Promoción con Áreas', ['reportes-mec-normal-promocion/index',
                                'paralelo' => $modelParalelo->id, 'conareas' => 'si'], ['class' => 'btn btn-link']);
                            ?>
                        </div>

                        <div class="row">
                            <?=
                            Html::a('Promoción sin Áreas', ['reportes-mec-normal-promocion/index',
                                'paralelo' => $modelParalelo->id, 'conareas' => 'no'], ['class' => 'btn btn-link']);
                            ?>
                        </div>

                        <div class="row">
                            <?=
                            Html::a('Nómina matriculados', ['reportes-mec-nomina-matriculados/index',
                                'paralelo' => $modelParalelo->id], ['class' => 'btn btn-link']);
                            ?>
                        </div>
                        <?php
                    }
                    ?>

                </div>
            </div>


            <div class="col-md-6">

                <?php if (count($modelTipoEmergencia) > 0) { ?>

                    <div class="well">
                        <h3><p class="text text-success">Usted tiene configurado la opción Covid 19</p></h3>

                        <?php
                        $totalClases = count($modelClases);
                        echo 'Total de clases: '.count($modelClases) . '<br>';
                        $contcerr =0;
                        foreach ($modelClases as $clase){
                            
                            echo '<ul>';
                            echo '<li>';
                           if($clase->estado_cierre == true){
                                $contcerr++;
                                echo $clase->materia->name.' ( CERRADO )';
                            }else{
                                echo '<p class="text-danger">'.$clase->materia->name.' ( SIN CERRAR )</p>';
                            }
                            echo '</li>';

                            echo '</ul>';
                            
                            
                        }
                        $totalSInCerrar = $totalClases-$contcerr;
                        
                         echo 'Total de clases cerradas: '.$contcerr . '<br>';
                         echo 'Total de clases no cerradas: '.$totalSInCerrar . '<br>';
                         echo '<hr>';
                         
                         
                         
                        if($totalSInCerrar == 0){
                            echo Html::a('Cerrar con covid', ['cerrarconcovid', 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-success']);
                        }else{
                            echo '<p class="text-danger"><strong>Solicite a sus profesores que cierren el proceso</strong></p>';
                        }

                        
                            
                       
                        ?>

                    </div>


                <?php } else { ?>
                    <div class="table table-responsive">
                        <table class="table table-condensed table-striped table-hover tamano10">
                            <thead>
                                <tr>
                                    <th>CLASE #</th>
                                    <th>MATERIA</th>
                                    <th>PROFESOR</th>
                                    <th>TOTAL NO CERRADOS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $suma = 0;
                                foreach ($modelClases as $clase) {
                                    echo '<tr>';
                                    echo '<td>' . $clase->id . '</td>';
                                    echo '<td>' . $clase->materia->name . '</td>';
                                    echo '<td>' . $clase->profesor->last_name . ' ' . $clase->profesor->x_first_name . '</td>';

                                    $total = $sentencias->total_alumnos_no_cerrados($clase->id);

                                    $suma = $suma + $total;
                                    echo '<td>' . $total . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                <?php } ?>
            </div>

            <div class="col-md-3">
                <div class="row">


                    <?php
                    if (count($modelTipoEmergencia) > 0) {
                        
                    } else {
                        if ($suma == 0) {
                            echo '<div class="alert alert-success">';
                            echo '<p><strong>Total de casos sin resolver: </strong>' . $suma . '</p>';
                            echo '<p>Usted debe realizar el cierre de fin de año.</p>';
                            echo Html::a('Cerrar Año', ['cerrar', 'paralelo' => $modelParalelo->id, 'mallaid' => $malla],
                                    ['class' => 'btn btn-success']);
                            echo '</div>';
                        } else {
                            echo '<div class="alert alert-danger">';
                            echo '<p><strong>Total de casos sin resolver: </strong>' . $suma . '</p>';
                            echo '<p>¡Usted no puede cerrar el proceso de fin de año para este paralelo, '
                            . 'por favor solicite a sus docentes que revisen las notas '
                            . 'finales y sienten las notas de fin de año!</p>';
                            echo '</div>';
                        }
                    }
                    ?>

                </div>






            </div>

            <div class="col-md-3">
                <div class="well">
                    <ul>
                        <li><?= Html::a('Informe Final', ['/informes-aprendizaje/informedireccion', 'paralelo' => $modelParalelo->id, 'quimestre' => 'final', 'reporte' => 'libfinal'], ['class' => 'btn btn-link']); ?></li>
                    </ul>
                </div>

            </div>

        </div>
    </div>



    <div class="row">

    </div>   




</div>

<div class="table table-responsive">
    <table class="table table-striped tamano10 table-bordered table-hover">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Estudiante</th>
                <?php
                foreach ($mallaArea as $area) {
                    $modelMarteria = \backend\models\ScholarisMallaMateria::find()->where(['malla_area_id' => $area->id])->all();
                    $totalMateria = count($modelMarteria) + 1;
                    if ($area->promedia == 1) {
                        echo '<th colspan="' . $totalMateria . '"><center>' . $area->area->name . '</center></th>';
                    } else {
                        echo '<th colspan="' . $totalMateria . '"><center>* ' . $area->area->name . '</center></th>';
                    }
                }
                ?>
                <th rowspan="2">PROM. FINAL</th>

                <th rowspan="2">OBSERVACIÓN</th>

                <th rowspan="2">PROM. SENTADO</th>
                <th rowspan="2">COMP. SENTADO</th>
            </tr>
            <tr>
                <?php
                foreach ($mallaArea as $area) {
                    $modelMarteria = \backend\models\ScholarisMallaMateria::find()->where(['malla_area_id' => $area->id])->all();
                    foreach ($modelMarteria as $materia) {

                        if ($materia->promedia == 1) {
                            echo '<th>' . $materia->materia->name . '</th>';
                        } else {
                            echo '<th>* ' . $materia->materia->name . '</th>';
                        }
                    }
                    echo '<th bgcolor="#D6D4D4">PROM</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;


            foreach ($modelAlumnos as $alumno) {
                
                $sentenciasNxx = new backend\models\SentenciasNotasDefinitivasAlumno($alumno->id, $periodoId, $modelParalelo->id);
                
                $i++;
                echo '<tr>';
                echo '<td>' . $i . '</td>';
                echo '<td>' . $alumno->last_name . ' ' . $alumno->first_name . ' ' . $alumno->middle_name . '</td>';

                foreach ($mallaArea as $area) {
                    //echo '<td>' . $area->id.$area->area->name . '</td>';       


                    $modelMarteria = \backend\models\ScholarisMallaMateria::find()->where(['malla_area_id' => $area->id])->all();
                    foreach ($modelMarteria as $materia) {
                        $notamat = $sentencias->nota_final_clase($alumno->id, $materia->id);
                        echo '<td>' . $notamat . '</td>';
                    }


                    if ($area->promedia == 1) {
                        $notaArea = $sentencias->nota_final_por_area($area->id, $alumno->id);
                        echo '<th bgcolor="#D6D4D4"><center>' . $notaArea . '</center></th>';
                    } else {
                        echo '<th bgcolor="#D6D4D4"><center>-</center></th>';
                    }
                }

                //$notaFinal = $sentencias->nota_final_alumno($mallaArea, $alumno->id, $modelParalelo->id);
                $notaFinal = $sentenciasNxx->notaFinalAprovechamiento;
                
                
                $estado = $sentencias->estado_final($notaFinal, $modelParalelo->course_id, $mallaArea, $alumno->id);
                echo '<td><center><strong>' . $notaFinal . '</strong></center></td>';

                echo $estado == true ? '<td bgcolor="#EAF7C5"><center><strong>APROBADO</strong></td>' : '<td bgcolor="#FFB2AE"><center><strong>PIERDE EL AÑO</strong></td>';


                $modelInscrip = backend\models\OpStudentInscription::find()
                        ->where(['student_id' => $alumno->id, 'parallel_id' => $modelParalelo->id])
                        ->one();

                $notaSentada = $sentencias->tomaNotasSentadas($modelInscrip->id);
                
                

                if ($notaSentada) {
                    echo '<td><center><strong>' . $notaSentada->nota_aprovechamiento . '</strong></center></td>';
                    echo '<td><center><strong>' . $notaSentada->nota_comportamiento . '</strong></center></td>';
                } else {
                    echo '<td><center><strong>--</strong></center></td>';
                    echo '<td><center><strong>--</strong></center></td>';
                }




                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

</div>
