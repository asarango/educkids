<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Detalle del nivel: ' . $model->course->name . ' - ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Volver', 'url' => ['detalle', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

 

?>
<div class="desempeno-detalle">


    <div class="alert alert-success">
        <strong>Elija sus opciones para el reporte</strong>        
    </div>



    <div class="row">
        <div class="col-md-4"></div>


        <div class="col-md-4">

            <?php echo Html::beginForm(['detalle1', 'post']); ?>

            <!--<label class="control-label">Parcial:</label>-->
            <?php
            echo '<div class="form-group">';
//            $dataBloques = ArrayHelper::map($modelBloques, 'id', 'name');
            echo '<label class="control-label">Parcial:</label>';
            echo Select2::widget([
                'name' => 'parcial',
                'value' => 0,
                'data' => $data,
                'size' => Select2::SMALL,
                'options' => [
                    'placeholder' => 'Seleccione Parcial',
                    'required' => true,
//                    'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                ],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            echo '</div>';
            ?>

            <?php
            echo '<div class="form-group">';
            echo '<label class="control-label">Valor para casos bajos:</label>';
            echo '<input type="number" name="bajos" required="" class="form-control" value="'.$bajos.'">';
            echo '</div>';
            ?>
            
            <?php
            echo '<div class="form-group">';
            echo '<label class="control-label">Valor para casos altos:</label>';
            echo '<input type="number" name="altos" required="" class="form-control" value="'.$altos.'">';
            echo '</div>';
            ?>

            <input type="hidden" name="paralelo" value="<?= $model->id ?>">

            <br>

            <div class="form-group">
                <?= Html::submitButton('Consultar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php echo Html::endForm(); ?>

        </div>
    </div>


</div>