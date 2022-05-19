<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$sentencias = new backend\models\SentenciasFinAno();

$usuario = Yii::$app->user->identity->usuario;

$this->title = 'Cerrando año lectivo - Proceso covid 19:  ' . $modelParalelo->course->name . ' - ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de cierre', 'url' => ['detallecerrar', 'id' => $modelParalelo->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-ano-cerrarconcovid">

    <div class="container">
        
        <div class="alert alert-info">
            Revisar si no existen alumnos pendientes de ingreso de notas.
            SI esta seguro de cerrar su año lectivo, por favor pesione el botón cerrar año.
            <br>
            <?= 
                Html::a('<span class="glyphicon glyphicon-folder-close"></span> Cerrar año lectivo', ['cerrarconcovid',
                        'accion' => 'cerrar',
                        'paralelo' => $modelParalelo->id
                    ],['class' => 'btn btn-success'])
            ?>
            
        </div>
        

        <div class="table table-responsive">
            <table class="table table-condensed table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <td align="center"><strong>#</strong></td>
                        <td align="center"><strong>ESTUDIANTES</strong></td>

                        <?php
                        
                        $sentencias = new \backend\models\SentenciasRepLibreta2();
                        if (count($modelTipoQuimestre) == 2) {
//                            echo '<td align="center"><strong>QUIMESTRE 1<br>COVID19</strong></td>';
//                            echo '<td align="center"><strong>QUIMESTRE 2<br>COVID19</strong></td>';
                            echo '<td align="center"><strong>FINAL</strong></td>';
                        } else {
//                            foreach ($modelTipoQuimestre as $tipo) {
//                                if ($tipo->quimestre->orden == 1) {
//                                    echo '<td align="center"></strong>' . $tipo->quimestre->nombre . '<br>COVID19</strong></td>';
//                                    echo '<td align="center"><strong>QUIMESTRE 2<br>NORMAL</strong></td>';
//                                } else {
//                                    echo '<td align="center"><strong>QUIMESTRE 1<br>NORMAL</strong></td>';
//                                    echo '<td align="center"><strong>' . $tipo->quimestre->nombre . '<br>COVID19</strong></td>';
//                                }
//                            }
                        }
                        echo '<td align="center"><strong>FINAL</strong></td>';
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sentencias = new backend\models\Notas();
                    $i=0;
                    
                    foreach ($modelAlumnos as $alumno){
                        
                        $sentenciasNxx = new backend\models\SentenciasNotasDefinitivasAlumno($alumno['id'], $periodoId, $modelParalelo->id);
                        
                        $i++;
                        echo '<tr>';
                        echo '<td>'.$i.'</td>';
                        echo '<td>'.$alumno['last_name'].' '.$alumno['first_name'].' '.$alumno['middle_name'].'</td>';
                        
                        //$nota = $sentencias->get_notas_finales($alumno['id'], $usuario, $mallaId);
                        $nota = $sentenciasNxx->notaFinalAprovechamiento;
//                        echo '<td>'.$nota['final_total'].'</td>';
                        echo '<td>'.$nota.'</td>';
                        
                        
                        echo '</tr>';
                    }
                    
                    
//                    foreach ($resultados as $res) {
//                        
//                        $q1 = $res['q1'];
//                        $q2 = $res['q2'];
//                        $q1covid = $res['covidq1'];
//                        $q2covid = $res['covidq2'];
//                        
//                        $i++;
//                        echo '<tr>';
//                        
//                        echo '<td>'.$i.'</td>';
//                        echo '<td>'.$res['nombre'].'</td>';
//
//                        if (count($modelTipoQuimestre) == 2) {
//                            $final = $sentencias->truncarNota(($q2covid+$q2covid)/2,2);
//                            echo '<td align="center">'.$q1covid.'</td>';
//                            echo '<td align="center">'.$q2covid.'</td>';
//                            echo '<td align="center">'.$final.'</td>';
//                        } else {
//                            foreach ($modelTipoQuimestre as $tipo) {
//                                if ($tipo->quimestre->orden == 1) {
//                                    $final = $sentencias->truncarNota(($q1covid+$q2)/2,2);
//                                    echo '<td align="center">' . $q1covid . '</td>';
//                                    echo '<td align="center">'.$q2.'</td>';
//                                    echo '<td align="center">'.$final.'</td>';
//                                } else {
//                                    $final = $sentencias->truncarNota(($q1+$q2covid)/2,2);
//                                    echo '<td align="center">'.$q1.'</td>';
//                                    echo '<td align="center">'.$q2covid.'</td>';
//                                    echo '<td align="center">'.$final.'</td>';
//                                }
//                            }
//                        }
//                        echo '</tr>';
//                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>