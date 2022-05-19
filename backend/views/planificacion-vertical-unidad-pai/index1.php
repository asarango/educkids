<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación Vertical Pai - '. $bloqueUnidad->unit_title.' - '. $bloqueUnidad->curriculoBloque->last_name ;



?>
<div class="planificacion-desagregacion-cabecera-index1">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2"><!-- INICIO DE CABECERA -->
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        (
                        <?= $bloqueUnidad->planCabecera->scholarisMateria->name ?>
                        |
                        <?= $bloqueUnidad->planCabecera->curriculoMecNivel->name ?>
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
                        ['planificacion-desagregacion-cabecera/desagregacion', 'unidad_id' => $bloqueUnidad->id],
                        ['class' => 'link']
                    );
                    ?>
                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    
                    
                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 15px;">
                
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
            beforeSend: function() {},
            success: function(response) {
                $("#table-body").html(response);
                //console.log(response);
            }
        });
    }
</script>