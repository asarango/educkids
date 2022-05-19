<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPcaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Desarrollo de unidades de planificación PCA: ' . $modelPca->cursoInstitucion->name
        . ' / ' . $modelPca->mallaMateriaInstitucion->materia->name;

$this->params['breadcrumbs'][] = ['label' => 'Planificación PCA', 'url' => ['index1']];
$this->params['breadcrumbs'][] = ['label' => 'Detalle de PCA', 'url' => ['detalle', 'id' => $modelPca->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pca-detalle">

    <div class="container">
        <div class="row">
            <div class="col-md-2"><strong>Docentes:</strong></div>
            <div class="col-md-10"><?= $modelPca->docentes ?></div>
        </div>

        <hr>

        <div class="row">

            <div class="col-md-6"><strong><u>Unidad de planificación</u></strong></div>
            <div class="col-md-2"><strong><u># Semanas</u></strong></div>
            <div class="col-md-2"><strong><u># Periodos</u></strong></div>
            <div class="col-md-2"><strong><u># Imprevistos</u></strong></div>
        </div>
        <div class="row">

            <?php echo Html::beginForm(['creaunidades', 'post']); ?>


            <div class="col-md-6"><input type="text" name="unidad" required="" class="form-control"></div>
            <div class="col-md-2"><input type="number" name="semanas" required="" class="form-control"></div>
            <div class="col-md-2"><input type="number" name="periodos" required="" class="form-control"></div>
            <div class="col-md-2"><input type="number" name="imprevistos" required="" class="form-control"></div>

            <input type="hidden" name="pca" value="<?= $modelPca->id ?>">

        </div>
        <br>
        <?php
        echo Html::submitButton(
                'Aceptar',
                ['class' => 'btn btn-primary']
        );
        ?> 

        <?php echo Html::endForm(); ?>

        <hr>
    </div>

    <div class="container">


        <div class="table table-responsive">
            <table class="table table-bordered table-hover tamano10">



                <?php
                $i = 0;
                foreach ($modelUnidades as $unidad) {
                    $i++;
                    ?>
                    <tr bgcolor="#CCCCCC">
                        <td colspan="3"> <strong><u><h3><?= $i . '.- ' . $unidad->unidad ?>
                                  <small><?= Html::a('', ['eliminarunidad', 'unidad' => $unidad->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']) ?></small></h3>                                
                                </u></strong></td>
                    </tr>




                    <tr bgcolor="#F9CA81">
                        <td><strong>Objetivos específicos de la unidad de planificación</strong></td>
                        <?php echo Html::beginForm(['creadesarrollo', 'post', 'class' => 'form-inline']); ?>
                        <td>
                            <?php
                            $dataObjG = ArrayHelper::map($modelObjetivos, 'id', 'codigo');

                            echo Select2::widget([
                                'name' => 'objetivo',
                                'value' => 0,
                                'data' => $dataObjG,
                                'size' => Select2::SMALL,
                                'options' => [
                                    'placeholder' => 'Seleccione objetivo',
                                    'required' => true,
//                                    'form-control' => false,
                                //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                                ],
                                'pluginLoading' => false,
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]);
                            ?>
                        </td>
                        <td>
                            <input type="hidden" name="unidad" value="<?= $unidad->id ?>">

                            <?php
                            echo Html::submitButton(
                                    'Aceptar',
                                    ['class' => 'btn btn-success']
                            );
                            ?>     

                        </td>
                        <?php echo Html::endForm(); ?>
                    </tr>

                    <?php
                    objetivos($unidad->id,'objgeneral');
                    ?>




                    <tr bgcolor="#F9CA81">
                        <td><strong>Destrezas</strong></td>
                        <?php echo Html::beginForm(['creadesarrollo', 'post', 'class' => 'form-inline']); ?>
                        <td>
                            <?php
                            $dataObjG = ArrayHelper::map($modelDestrezas, 'id', 'codigo');

                            echo Select2::widget([
                                'name' => 'objetivo',
                                'value' => 0,
                                'data' => $dataObjG,
                                'size' => Select2::SMALL,
                                'options' => [
                                    'placeholder' => 'Seleccione destrezas',
                                    'required' => true,
//                                    'form-control' => false,
                                //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                                ],
                                'pluginLoading' => false,
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]);
                            ?>
                        </td>
                        <td>
                            <input type="hidden" name="unidad" value="<?= $unidad->id ?>">

                            <?php
                            echo Html::submitButton(
                                    'Aceptar',
                                    ['class' => 'btn btn-success']
                            );
                            ?>     

                        </td>
                        <?php echo Html::endForm(); ?>
                    </tr>

                    <?php
                    objetivos($unidad->id, 'destrezas');
                    ?>

                    <tr bgcolor="#F9CA81">
                        <td><strong>Criterios de Evaluación concatenados a las DCD</strong></td>
                        <?php echo Html::beginForm(['creadesarrollo', 'post', 'class' => 'form-inline']); ?>
                        <td>
                            <?php
                            $dataObjG = ArrayHelper::map($modelCriterioIndica, 'id', 'codigo');

                            echo Select2::widget([
                                'name' => 'objetivo',
                                'value' => 0,
                                'data' => $dataObjG,
                                'size' => Select2::SMALL,
                                'options' => [
                                    'placeholder' => 'Seleccione criterio e indicadores',
                                    'required' => true,
//                                    'form-control' => false,
                                //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                                ],
                                'pluginLoading' => false,
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]);
                            ?>
                        </td>
                        <td>
                            <input type="hidden" name="unidad" value="<?= $unidad->id ?>">

                            <?php
                            echo Html::submitButton(
                                    'Aceptar',
                                    ['class' => 'btn btn-success']
                            );
                            ?>     

                        </td>
                        <?php echo Html::endForm(); ?>
                    </tr>
                    <?php
                    criterios_inidcadores($unidad->id);
                    ?>

                    <tr bgcolor="#F9CA81">
                        <td><strong>Orientaciones metodológicas</strong></td>
                        <?php echo Html::beginForm(['creadesarrollo', 'post', 'class' => 'form-inline']); ?>
                        <td>
                            <?php
                            $dataObjG = ArrayHelper::map($modelOrientaciones, 'id', 'codigo');

                            echo Select2::widget([
                                'name' => 'objetivo',
                                'value' => 0,
                                'data' => $dataObjG,
                                'size' => Select2::SMALL,
                                'options' => [
                                    'placeholder' => 'Seleccione orientaciones metodológicas',
                                    'required' => true,
//                                    'form-control' => false,
                                //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                                ],
                                'pluginLoading' => false,
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                            ]);
                            ?>
                        </td>
                        <td>
                            <input type="hidden" name="unidad" value="<?= $unidad->id ?>">

                            <?php
                            echo Html::submitButton(
                                    'Aceptar',
                                    ['class' => 'btn btn-success']
                            );
                            ?>     

                        </td>
                        <?php echo Html::endForm(); ?>
                    </tr>
                    <?php
                    objetivos($unidad->id, 'orientacion');
                    ?>
<!--                    <tr>
                        <td colspan="3"><hr></td>
                    </tr>-->
                <?php } ?>                                        

            </table>
        </div>
    </div>
</div>


<?php

function objetivos($unidad, $referencia) {

    
    $modelUnidad = backend\models\ScholarisPlanPcaUnidadesDetalle::find()
                    ->where([                                
                        'unidad_id' => $unidad,
                        'tipo_referencia' => $referencia
                    ])
                   ->all();
    foreach ($modelUnidad as $uni) {
        echo '<tr>';
        echo '<td colspan="2">' . $uni->codigo . ' ' . $uni->detalle . '</td>';
        echo '<td>' . Html::a('Eliminar', ['eliminar', 'detalle' => $uni->id], ['class' => 'btn btn-link']) . '</td>';
        echo '<tr>';
    }
}


function criterios_inidcadores($unidad) {

    
    $modelUnidad = backend\models\ScholarisPlanPcaUnidadesDetalle::find()
                    ->where([                                
                        'unidad_id' => $unidad,
                    ])
                   ->andWhere(['in','tipo_referencia',['evaluacion','indicador']])
                   ->all();
    foreach ($modelUnidad as $uni) {
        echo '<tr>';
        echo '<td colspan="2">' . $uni->codigo . ' ' . $uni->detalle . '</td>';
        echo '<td>' . Html::a('Eliminar', ['eliminar', 'detalle' => $uni->id], ['class' => 'btn btn-link']) . '</td>';
        echo '<tr>';
    }
}

?>