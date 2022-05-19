<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanCurriculoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$listData = ArrayHelper::map($modelSubniveles, 'id', 'nombre');


$this->title = 'PCI';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-pci-index">

    <div class="container">
        <div class="row">
            <?php echo Html::beginForm(['index', 'post']); ?>

            <div class="col-md-3"><?php echo '<label class="control-label">Subnivel :</label>' ?></div>
            <div class="col-md-3">
                <?php
                echo Select2::widget([
                    'name' => 'subnivel',
                    'value' => 0,
                    'data' => $listData,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione Subnivel',
                    //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>

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

    </div>

    <hr>



    <?php if ($modelSub == null) { ?>

        <div class="alert alert-danger">
            No tiene elejido ningún subnivel, por favor seleccione el nivel que desea configurar su PCI.
        </div>


    <?php } else { ?>

        <div class="container">
            <div class="row">
                <h3> <?= $modelSub->nombre ?></h3>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <?= Html::a('Exportar_PDF', ['pdf', 'subnivel' => $modelSub->id], ['class' => 'btn btn-danger glyphicon glyphicon-file']) ?>
                </div>

                

                    <?php
                    $optaData = ArrayHelper::map($modelOptativas, 'id', 'nombre');
                    echo Html::beginForm(['optativa', 'post']);

                    echo '<div class="col-md-8">';
                    echo Select2::widget([
                        'name' => 'materia',
                        'value' => 0,
                        'data' => $optaData,
                        'size' => Select2::SMALL,
                        'options' => [
                            'placeholder' => 'Seleccione Optativa...',
                        //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                        ],
                        'pluginLoading' => false,
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]);
                    echo '</div>';
                    echo '<div class="col-md-2">';
                    echo '<input type="hidden" name="subnivel" value="'.$modelSub->id.'">';
                    echo Html::submitButton('Aceptar', ['class' => 'btn btn-primary']);
                    echo '</div>';
                    echo Html::endForm();
                    ?>

                </div>
            </div>

    <hr>
            <div class="table table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ASIGNATURA</th>
                            <th>COLOR</th>
                            <th>TIPO DE MATERIA</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($modelPci as $pci) {
                            echo '<tr>';
                            echo '<td>' . $pci->id . '</td>';
                            echo '<td>' . $pci->materia_curriculo_nombre . '</td>';
                            echo '<td bgcolor="' . $pci->materia_curriculo_color . '">' . $pci->materia_curriculo_color . '</td>';
                            echo '<td bgcolor="' . $pci->materia_curriculo_color . '">' . $pci->tipo_materia . '</td>';
                            echo '<td>' . Html::a('Detalle', ['detalle', 'pci' => $pci->id], ['class' => 'btn btn-link']) . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>





</div>