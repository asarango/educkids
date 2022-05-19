<?php

use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación Vertical Diploma';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($seccion);
// die();
?>
<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small><b>BLOQUE Nº: </b> 
                        <?= $planUnidad->curriculoBloque->last_name ?><b> | 
                        <?= $planUnidad->unit_title  ?></b> | 
                        <?= $planUnidad->planCabecera->scholarisMateria->name  ?></b>
                    </small>
                </div>
            </div>
            <!-- FIN DE CABECERA -->


            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificación</span>',
                            ['planificacion-bloques-unidad/index1', 'id' => $planUnidad->plan_cabecera_id],
                            ['class' => 'link']
                    );
                    ?>
                    |
                    
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Actualizar detalles</span>',
                            ['update', 'id' => $planVerticalDiploma->id],
                            ['class' => 'link']
                    );
                    ?>
                    |
                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->


            <hr>

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 10px; margin-left:1px;margin-right:1px; margin-bottom:5px">
                    <div class="table table-responsive">
                        <table class="table table-hover table-condensed table-striped table-bordered my-text-medium">
                            <thead>
                                <tr style="background-color: #ab0a3d; color: #eee">
                                    <th class="text-center">OBJETIVOS DE LA UNIDAD</th>
                                    <th class="text-center">CONCEPTO CLAVE</th>
                                    <th class="text-center">CONTENIDOS</th>
                                    <th class="text-center">RELACIÓN CON TDC</th>
                                    <th class="text-center">HABILIDADES DEL ENFOQUE DEL APRENDIZAJE</th>
                                    <th class="text-center">OBJETIVOS DE LA EVALUACIÓN</th>
                                    <th class="text-center">INSTRUMENTOS DE LA EVALUACIÓN</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td><?= verifica_vacio($planVerticalDiploma->objetivo_asignatura); ?></td>
                                    <td><?= verifica_vacio($planVerticalDiploma->concepto_clave); ?></td>
                                    <td>
                                        <ul>
                                        <?php

                                        // echo '<pre>';
                                        // print_r($planVerticalDiplContenidos);
                                            
                                            foreach($planVerticalDiplContenidos as $contenido){
                                                echo '<li>';   
                                                echo '<u><b>'.$contenido['subtitulo'].'</b></u>';   
                                                echo '<ul>';
                                                foreach($contenido['subtitulos'] as $subtitulo){
                                                    echo '<li>*'.$subtitulo['contenido'].'</li>';
                                                }
                                                echo '</ul>';
                                                echo '</li>';   
                                            }
                                        ?>
                                        </ul>
                                    </td>
                                    <td><?= verifica_relacion_tdc($planVerticalDiplRelacionTDC); ?></td>
                                    <td><?= verifica_habilidades($planVerticalDiplHabilidades); ?></td>
                                    <td><?= verifica_vacio($planVerticalDiploma->objetivo_evaluacion); ?></td>                                   
                                    <td><?= verifica_vacio($planVerticalDiploma->intrumentos); ?></td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>


</div>


<?php 
    function verifica_vacio($contenido){
        $html = '';
        if($contenido == 'Sin contenido'){
            $html .= '<i class="fas fa-exclamation-triangle" style="color: #ab0a3d">'.$contenido.'</i>';         
        }else{
            $html .= '<p>'.$contenido.'</p>';
        }

        return $html;
    }


    function verifica_relacion_tdc($objeto){        
        $html = '';
        if(count($objeto) <= 0){
            $html .= '<i class="fas fa-exclamation-triangle" style="color: #ab0a3d">Sin opciones elejidas</i><br>';         
        }else{
            foreach($objeto as $obj){
                $html .= '<i class="fas fa-check" style="color: green"> '.$obj->relacionTdc->opcion.'</i><br>';
            }
        }

        return $html;
    }

    function verifica_habilidades($objeto){

        //$prueba = PlanificacionVerticalDiplomaHabilidades::find();

        $html = '';
        if(count($objeto) <= 0){
            $html .= '<i class="fas fa-exclamation-triangle" style="color: #ab0a3d">Sin opciones elejidas</i><br>';         
        }else{
            foreach($objeto as $obj){
                $html .= '<i class="fas fa-check" style="color: green"> '.$obj['es_titulo2'].'</i><br>';
            }
        }

        return $html;
    }

?>