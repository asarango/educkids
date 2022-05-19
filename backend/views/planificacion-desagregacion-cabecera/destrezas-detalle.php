<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Criterio de Evaluación';
$this->params['breadcrumbs'][] = $this->title;
$condicionClass = new backend\models\helpers\Condiciones;

//echo '<pre>';
//print_r($criterioEvaluacion->bloqueUnidad->planCabecera);
//die();
$estado = $criterioEvaluacion->bloqueUnidad->planCabecera->estado;
$isOpen = $criterioEvaluacion->bloqueUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado,$isOpen,$criterioEvaluacion->bloqueUnidad->settings_status);
//echo $condicion;
//die();
?>
<div class="planificacion-desagregacion-cabecera-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <?= $criterioEvaluacion->criterioEvaluacion->code . ' ' . $criterioEvaluacion->criterioEvaluacion->description ?>
                        |
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> Asignaturas por nivel
                        </span>',
                            ['index'],
                            ['class' => 'link']
                    );
                    ?>

                    |

                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18">
                            <i class="fa fa-briefcase" aria-hidden="true"></i> Criterio de evaluaciòn
                        </span>',
                            ['desagregacion', 'unidad_id' => $criterioEvaluacion->bloqueUnidad->id],
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
                <div class="col-lg-12 col-md-12">

                    <?php
                    if ($condicion == false) {
                        ?>
                    <?php
                        if (isset($destrezasGroup)) {

                            foreach ($destrezasGroup as $group) {
                                ?>
                                <div class="card" style="margin-top: 15px; padding: 15px;margin-bottom: 10px">
                                    <p>
                                        <b><strong><?= $group['code'] . ' ' . $group['description'] ?></strong></b>
                                    </p>

                                    <div class="table table-responsive">
                                        <table class="table table-hover table-condensed table-bordered my-text-medium" style="">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><?= $course->name ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php
                                                    $destrezasCursos = recupera_destrezas($criterioEvaluacion->id, $group['curriculo_destreza_id']);
                                                    foreach ($destrezasCursos as $des) {
                                                        if ($des['is_essential']) {
                                                            $fondo = '#d9e8fd';
                                                        } else {
                                                            $fondo = '#ffffff';
                                                        }
                                                        ?>

                                                        <td style="background-color: <?= $fondo ?>; width: 33.33%;">
                                                            <p>
                                                                <?= $des['opcion_desagregacion'] ?>
                                                            </p>
                                                            <hr>
                                                            <?php
                                                            if ($des['is_active']) {
                                                                echo $des['content'];
                                                            }
                                                            ?>
                                                        </td>

                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <?php
                            }
                        } else {
                            echo '<h1>NO EXISTE DESTREZAS ESCOGIDAS PARA ESTE CRITERIO DE EVALUACIÓN</h1>';
                        }
                        ?>
                    
                    
                    
                    
                        <?php
                    } else {
                        ?>
                        <!--INICIO DE ROW PARA SELECT-->
                        <div class="row" style="margin: 15px;">
                            <div class="form-group">
                                <?php print_r($estado) ?>
                                <select name="destrezas" id="select-destrezas" class="form-control" onchange="ingresaDestrezas()">
                                    <option value="">Seleccione destreza...</option>
                                    <?php
                                    foreach ($destrezas as $destreza) {
                                        echo '<option value="' . $destreza['id'] . '">' . $destreza['code'] . ' ' . $destreza['description'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div> 
                        <!-- FIN DE ROW PARA SELECT -->

                        <div class="row" style="margin: 5px;">
                            <p>DETALLE DE DESTREZAS</p>
                        </div>

                        <?php
                        if (isset($destrezasGroup)) {

                            foreach ($destrezasGroup as $group) {
                                ?>
                                <div class="card" style="margin-top: 15px; padding: 15px;">
                                    <p>
                                        <?=
                                        Html::a(
                                                '<i class="fas fa-trash-alt" style="color: red;"> Eliminar destreza</i>',
                                                [
                                                    'delete-plan-destreza',
                                                    'id' => $group['curriculo_destreza_id'],
                                                    'criterioEvaluacionId' => $criterioEvaluacion->id
                                                ]
                                        )
                                        ?>
                                    </p>
                                    <p>
                                        <b><?= $group['code'] . ' ' . $group['description'] ?></b>
                                    </p>

                                    <div class="table table-responsive">
                                        <table class="table table-hover table-condensed table-bordered my-text-medium" style="">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><?= $course->name ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <?php
                                                    $destrezasCursos = recupera_destrezas($criterioEvaluacion->id, $group['curriculo_destreza_id']);
                                                    foreach ($destrezasCursos as $des) {
                                                        if ($des['is_essential']) {
                                                            $fondo = '#d9e8fd';
                                                        } else {
                                                            $fondo = '#ffffff';
                                                        }
                                                        ?>

                                                        <td style="background-color: <?= $fondo ?>; width: 33.33%;">
                                                            <p>
                                                                <?= $des['opcion_desagregacion'] ?>
                                                                |
                                                                <?=
                                                                Html::a('<i class="fas fa-exchange-alt" style="color: #ab0a3d;"> GRADAR / DESAGREGAR</i>', [
                                                                    'change',
                                                                    'id' => $des['id']
                                                                ]);
                                                                ?>
                                                            </p>
                                                            <hr>
                                                            <?php
                                                            if ($des['is_active']) {
                                                                echo $des['content'];
                                                            }
                                                            ?>
                                                        </td>

                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <?php
                            }
                        } else {
                            echo '<h1>NO EXISTE DESTREZAS ESCOGIDAS PARA ESTE CRITERIO DE EVALUACIÓN</h1>';
                        }
                        ?>



                        <?php
                    }
                    ?>








                </div>
            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>


<script>
    function ingresaDestrezas() {
        var destrezaId = $('#select-destrezas').val();
        var criterioId = '<?= $criterioEvaluacion->id ?>';

        var url = '<?= Url::to(['ingresa-destreza']) ?>';
        var params = {
            destreza_id: destrezaId,
            criterio_id: criterioId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                //$("#table-body").html(response);
                //console.log(response);
                location.reload();
            }
        });
    }
</script>

<?php

function recupera_destrezas($criterioEvaluacionId, $planDestrezaId) {
    $con = Yii::$app->db;
    $query = "select 	pd.id 
                            ,pd.opcion_desagregacion 
                            ,pd.content
                            ,des.code 
                            ,des.is_essential
                            ,pd.is_active
                    from 	planificacion_desagregacion_criterios_destreza pd
                            left join op_course_template cur on cur.id = pd.course_template_id 
                            inner join curriculo_mec des on des.id = pd.curriculo_destreza_id 
                    where	pd.desagregacion_evaluacion_id = $criterioEvaluacionId
                            and pd.curriculo_destreza_id = $planDestrezaId
                    order by cur.order_curriculo;";
    $res = $con->createCommand($query)->queryAll();
    return $res;
}
?>