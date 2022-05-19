<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$condicionClass = new backend\models\helpers\Condiciones;
$this->title = 'Criterios de Evaluación - ' . $bloqueUnidad->unit_title . ' - ' . $bloqueUnidad->curriculoBloque->last_name;
$this->params['breadcrumbs'][] = $this->title;

//echo '<pre>';
//print_r($bloqueUnidad->planCabecera->estado);
//die();
$estado = $bloqueUnidad->planCabecera->estado;
$isOpen = $bloqueUnidad->is_open;

$condicion = $condicionClass->aprobacion_planificacion($estado,$isOpen,$bloqueUnidad->settings_status);
//echo $condicion;
//die();

?>
<div class="planificacion-desagregacion-cabecera-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        (
                        
                        <?= $materia->nombre ?>
                        |
                        <?= $bloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name ?>
                        )
                    </small>
                </div>
            </div><!-- FIN DE CABECERA -->


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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fas fa-info-circle"></i> Detalle de temas</span>',
                            ['planificacion-bloques-unidad/index1', 'id' => $bloqueUnidad->plan_cabecera_id],
                            ['class' => 'link']
                    );
                    ?>
                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    <!-- |
                     <?ph
                    
                     if ($materia->curriculoNivel->plan_especial == 'SI') {
                         echo Html::a(
                             '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="fas fa-ruler-vertical"></i> Planificación Vertical - PAI</span>',
                             ['planificacion-vertical-unidad-pai/index1', 'unidad_id' => $bloqueUnidad->id],
                             ['class' => 'link']
                         );
                     }
                     
                     |-->
                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->

            <div class="row" style="margin-top: 15px;">
                <?php
                if ($condicion == false) {
                    ?>
                <div class="col-lg-5 col-md-5" style="padding: 20px;">
                    <h6>Esta planificación está <?=$estado ?></h6>

                </div>
                <div class="col-lg-7 col-md-7" style="border-left: solid 1px #ccc; padding: 20px;">
                    <div class="table table-responsive">
                        <table class="table table-condensed table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>CRITERIO DE EVALUACIÓN (USADAS PARA EL INSTITUTO)</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($criteriosUsados as $usado) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?=$usado['code'] . ' ' . $usado['description']?>
                                            <hr>
                                            <p>
                                                <?php
                                                echo Html::a(
                                                        '<span class="badge rounded-pill" style="background-color: #65b2e8">' . $usado['total_destrezas'] . ' Destrezas</span>',
                                                        ['destrezas-detalle', 'criterio_evaluacion_id' => $usado['id']],
                                                        ['class' => 'link']
                                                );

                                                // echo '<b> | </b>';
                                                // if ($materia->curriculoNivel->plan_especial == 'SI') {
                                                //     echo Html::a(
                                                //         '<span class="badge rounded-pill" style="background-color: #ff9e18">Desagregación PAI</span>',
                                                //         ['planificacion-destrezas-pai/index1', 'criterio_evaluacion_id' => $usado['id']],
                                                //         ['class' => 'link']
                                                //     );
                                                // }
                                                ?>
                                            </p>

                                        </td>
                                    </tr>
    <?php
}
?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                } else {
                   ?>
                <div class="col-lg-5 col-md-5" style="padding: 20px;">
                    <div class="table table-responsive" style="box-shadow: 20px 20px 20px -20px #ccc;">
                        <table class="table table-condensed table-striped table-hover text-small">
                            <thead>
                                <tr>
                                    <th>CRITERIO DE EVALUACIÓN (MEC - DISPONIBLES)</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($criteriosNoUsados as $noUsado) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?=
                                            Html::a($noUsado['code'] . ' ' . $noUsado['description'], [
                                                'asignar',
                                                'unidad_id' => $bloqueUnidad->id,
                                                'criterio_id' => $noUsado['id']
                                                    ], ['class' => 'text-color-p']);
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="col-lg-7 col-md-7" style="border-left: solid 1px #ccc; padding: 20px;">
                    <div class="table table-responsive">
                        <table class="table table-condensed table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>CRITERIO DE EVALUACIÓN (USADAS PARA EL INSTITUTO)</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                foreach ($criteriosUsados as $usado) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?=
                                            Html::a($usado['code'] . ' ' . $usado['description'], [
                                                'quitar',
                                                'id' => $usado['id']
                                                    ], ['class' => 'text-color-s']);
                                            ?>
                                            <hr>
                                            <p>
                                                <?php
                                                echo Html::a(
                                                        '<span class="badge rounded-pill" style="background-color: #65b2e8">' . $usado['total_destrezas'] . ' Destrezas</span>',
                                                        ['destrezas-detalle', 'criterio_evaluacion_id' => $usado['id']],
                                                        ['class' => 'link']
                                                );

                                                // echo '<b> | </b>';
                                                // if ($materia->curriculoNivel->plan_especial == 'SI') {
                                                //     echo Html::a(
                                                //         '<span class="badge rounded-pill" style="background-color: #ff9e18">Desagregación PAI</span>',
                                                //         ['planificacion-destrezas-pai/index1', 'criterio_evaluacion_id' => $usado['id']],
                                                //         ['class' => 'link']
                                                //     );
                                                // }
                                                ?>
                                            </p>

                                        </td>
                                    </tr>
    <?php
}
?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                }
                ?>
                
            </div>

            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>


<script>
    function showAsignaturas() {
        var nivel = $('#nivel').val();
        var url = '<?= Url::to(['list-materias']) ?>';
        var params = {
            nivel_id: nivel
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                $("#table-body").html(response);
                //console.log(response);
            }
        });
    }
</script>