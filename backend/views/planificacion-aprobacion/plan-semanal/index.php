<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Plan Semanal';
//$this->params['breadcrumbs'][] = $this->title;
//$this->params['breadcrumbs'][] = ['label' => 'Detalle de destrez pud', 'url' => ['editardestreza', 'destreza' => $modelDestreza->id]];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="plan-semanal-index" style="padding-left: 40px; padding-right: 40px">



    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



        <?php echo Html::beginForm(['index', 'post']); ?>


        <div class="row">

            <div class="col-md-10">
                <div class="form-group has-feedback">

                    <input type="text" class="form-control" placeholder="Buscar por semana" name="semana" />
                    <i class="glyphicon glyphicon-search form-control-feedback"></i>
                </div>


            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <?php
                    echo Html::submitButton(
                            'Buscar',
                            ['class' => 'btn btn-warning btn-block']
                    );
                    ?>
                </div>
            </div>
        </div>


        <?php echo Html::endForm(); ?>

        <div class="table table-responsive">
            <table class="table table-hover table-bordered table-condensed">
                <tr>
                    <td><strong>SEMANA</strong></td>
                    <td><strong>DESDE</strong></td>
                    <td><strong>HASTA</strong></td>
                    <td><strong>BLOQUE</strong></td>
                    <td><strong>BLOQUE - COMPARTE</strong></td>
                    <td><strong>OBSERVACION</strong></td>
                    <td colspan="3" align="center"><strong>ACCIONES</strong></td>
                </tr>

                <?php
                foreach ($model as $sem) {
                    ?> 
                    <tr>
                        <td><?= $sem['nombre_semana'] ?></td>
                        <td><?= $sem['fecha_inicio'] ?></td>
                        <td><?= $sem['fecha_finaliza'] ?></td>
                        <td><?= $sem['bloque'] ?></td>
                        <td><?= $sem['nombre'] ?></td>
                        <td><?= $sem['observacion'] ?></td>
                        <td><?= Html::a('', ['observacion', 'id' => $sem['id']], ['class' => 'btn btn-link glyphicon glyphicon-eye-close']) ?></td>
                        <td><?= Html::a('', ['destrezas', 'id' => $sem['id'], 'facultyId' => $modelFaculty->id], ['class' => 'btn btn-link glyphicon glyphicon-compressed']) ?></td>
                        <td><?= Html::a('', ['pdf', 'id' => $sem['id'], 'facultyId' => $modelFaculty->id],
                        ['class' => 'btn btn-link glyphicon glyphicon-book'])
                    ?></td>
                    </tr>
                    <?php
                }
                ?>

            </table>
        </div>

</div>

