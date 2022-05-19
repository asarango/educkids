<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$listaMateria = backend\models\ScholarisMateria::find()
        ->select(["m.id", "concat(mal.nombre_malla,' - ',scholaris_materia.name) as name"])
        ->innerJoin("scholaris_malla_materia m", "scholaris_materia.id = m.materia_id")
        ->innerJoin("scholaris_malla_area a", "a.id = m.malla_area_id")
        ->innerJoin("scholaris_malla_curso c", "c.malla_id = a.malla_id")
        ->innerJoin("scholaris_malla mal", "mal.id = a.malla_id")
        ->innerJoin("op_course cur", "cur.id = c.curso_id")
        ->all();


/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPcaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Detalle de planificación PCA: ' . $modelPca->cursoInstitucion->name
        . ' / ' . $modelPca->mallaMateriaInstitucion->materia->name;

$this->params['breadcrumbs'][] = ['label' => 'Planificación PCA', 'url' => ['index1']];
$this->params['breadcrumbs'][] = ['label' => 'Desarrollo de unidades', 'url' => ['unidades','pca' => $modelPca->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pca-detalle">

    <div class="container">
        <div class="row">
            <div class="col-md-2"><strong>Docentes:</strong></div>
            <div class="col-md-10"><?= $modelPca->docentes ?></div>
        </div>

        <hr>


        OBJETIVOS GENERALES

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5 class="panel-title">OBJETIVOS DEL ÁREA</h5>
            </div>
            <div class="panel-body">
                <!--inici formulario de objetivos generales-->
                <div class="row">
                    <?php echo Html::beginForm(['objetivos', 'post']); ?>

                    <div class="col-md-9">
                        <?php
                        $listaObjG = \backend\models\CurCurriculo::find()
                                ->select(["id", "concat(codigo,' ',detalle) as codigo"])
                                ->where(['tipo_referencia' => 'objgeneral', 'materia_id' => $modelPca->malla_materia_curriculo_id])
                                ->all();

                        $dataObjG = ArrayHelper::map($listaObjG, 'id', 'codigo');

                        echo Select2::widget([
                            'name' => 'objetivo',
                            'value' => 0,
                            'data' => $dataObjG,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => 'Seleccione objetivo',
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);
                        ?>
                    </div>

                    <input type="hidden" name="pca" value="<?= $modelPca->id ?>">

                    <div class="col-md-3">
                        <?php
                        echo Html::submitButton(
                                'Aceptar',
                                ['class' => 'btn btn-primary']
                        );
                        ?> 
                    </div>


                    <?php echo Html::endForm(); ?>
                </div>
                <!--finaliza formulario de objetivos generales-->
                <hr>
                <!-- incia detalle de objetivos generales-->
                <div class="row">
                    <?php
                    foreach ($modelObjetivosGenerales as $obj) {
                        echo '<p><strong>' . $obj->codigo . '</strong>';
                        echo $obj->detalle;
                        echo '</p><hr>';
                    }
                    ?>
                </div>
                <!--finaliza detalle de objetivos generales-->

            </div>
            <!--        <div class="panel-footer panel-primary">footer</div>-->
        </div>


        <div class="panel panel-info">
            <div class="panel-heading">
                <h5 class="panel-title">OBJETIVOS DEL GRADO / CURSO(SUBNIVEL)</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php echo Html::beginForm(['objetivos', 'post']); ?>

                    <div class="col-md-9">
                        <?php
                        $listaObjG = \backend\models\CurCurriculo::find()
                                ->select(["id", "concat(codigo,' ',detalle) as codigo"])
                                ->where(['tipo_referencia' => 'objarea', 'materia_id' => $modelPca->malla_materia_curriculo_id])
                                ->all();

                        $dataObjG = ArrayHelper::map($listaObjG, 'id', 'codigo');

                        echo Select2::widget([
                            'name' => 'objetivo',
                            'value' => 0,
                            'data' => $dataObjG,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => 'Seleccione objetivo',
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);
                        ?>
                    </div>

                    <input type="hidden" name="pca" value="<?= $modelPca->id ?>">

                    <div class="col-md-3">
                        <?php
                        echo Html::submitButton(
                                'Aceptar',
                                ['class' => 'btn btn-info']
                        );
                        ?> 
                    </div>


                    <?php echo Html::endForm(); ?>
                </div>
                <!--finaliza formulario de objetivos area-->
                <hr>
                <!-- incia detalle de objetivos area-->
                <div class="row">
                    <?php
                    foreach ($modelObjetivosArea as $obj) {
                        echo '<p><strong>' . $obj->codigo . '</strong>';
                        echo $obj->detalle;
                        echo '</p><hr>';
                    }
                    ?>
                </div>
                <!--finaliza detalle de objetivos area-->

            </div>
            <!--        <div class="panel-footer panel-primary">footer</div>-->
        </div>


        <!--inicia EJES TRANSVERSALES y actitudes-->
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h5 class="panel-title">EJES TRANSVERSALES</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?php echo Html::beginForm(['objetivos', 'post']); ?>

                            <div class="col-md-9">
                                <?php
                                $listaObjG = \backend\models\CurCurriculo::find()
                                        ->select(["id", "concat(codigo,' ',detalle) as codigo"])
                                        ->where(['tipo_referencia' => 'ejes'])
                                        ->all();

                                $dataObjG = ArrayHelper::map($listaObjG, 'id', 'codigo');

                                echo Select2::widget([
                                    'name' => 'objetivo',
                                    'value' => 0,
                                    'data' => $dataObjG,
                                    'size' => Select2::SMALL,
                                    'options' => [
                                        'placeholder' => 'Seleccione ejes transversales',
                                    //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                                    ],
                                    'pluginLoading' => false,
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                ]);
                                ?>
                            </div>

                            <input type="hidden" name="pca" value="<?= $modelPca->id ?>">

                            <div class="col-md-3">
                                <?php
                                echo Html::submitButton(
                                        'Aceptar',
                                        ['class' => 'btn btn-warning']
                                );
                                ?> 
                            </div>


                            <?php echo Html::endForm(); ?>
                        </div>
                        <!--finaliza formulario de objetivos area-->
                        <hr>
                        <!-- incia detalle de objetivos area-->
                        <div class="row">
                            <ul>
                                <?php
                                foreach ($modelEjes as $obj) {
                                    echo '<li>' . $obj->detalle . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <!--finaliza detalle de objetivos area-->

                    </div>
                    <!--        <div class="panel-footer panel-primary">footer</div>-->
                </div>
            </div>
            
            
            
            
            
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h5 class="panel-title">VALORES</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?php echo Html::beginForm(['objetivos', 'post']); ?>

                            <div class="col-md-9">
                                <?php
                                $listaObjG = \backend\models\CurCurriculo::find()
                                        ->select(["id", "concat(codigo,' ',detalle) as codigo"])
                                        ->where(['tipo_referencia' => 'actitud'])
                                        ->all();

                                $dataObjG = ArrayHelper::map($listaObjG, 'id', 'codigo');

                                echo Select2::widget([
                                    'name' => 'objetivo',
                                    'value' => 0,
                                    'data' => $dataObjG,
                                    'size' => Select2::SMALL,
                                    'options' => [
                                        'placeholder' => 'Seleccione actitudes',
                                        'required' => true
                                    //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                                    ],
                                    'pluginLoading' => false,
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                ]);
                                ?>
                            </div>

                            <input type="hidden" name="pca" value="<?= $modelPca->id ?>">

                            <div class="col-md-3">
                                <?php
                                echo Html::submitButton(
                                        'Aceptar',
                                        ['class' => 'btn btn-success']
                                );
                                ?> 
                            </div>


                            <?php echo Html::endForm(); ?>
                        </div>
                        <!--finaliza formulario de objetivos area-->
                        <hr>
                        <!-- incia detalle de objetivos area-->
                        <div class="row">
                            <ul>
                                <?php
                                foreach ($modelActitud as $obj) {
                                    echo '<li>' . $obj->detalle . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <!--finaliza detalle de objetivos area-->

                    </div>
                    <!--        <div class="panel-footer panel-primary">footer</div>-->
                </div>
            </div>
        </div>
        <!--finaliza EJES TRANSVERSALES y actitudes-->


    </div>
</div>
