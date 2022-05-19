<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$modelRutaGraficos = backend\models\ScholarisParametrosOpciones::find()
        ->where(['codigo' => 'graficos'])
        ->one();
$rutaGraficos = $modelRutaGraficos->nombre;



$this->title = 'Detalle del nivel: ' . $modelParalelo->course->name . ' - ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = ['label' => 'Volver', 'url' => ['index','id' => $modelParalelo->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="desempeno-detalle">

    
    <div class="alert alert-success">
        <strong>PARCIAL: </strong><?= $mensaje[0].' '.$mensaje[1].' '.$mensaje[2] ?>        
    </div>
    

    <?php
    $dat = urldecode(serialize($totales));
    $labels = urlencode(serialize($materias));
//print_r($dat);
//print_r($dat);
//die();
    $graphLink = "$rutaGraficos"."total_paralelo.php?datos=$dat&labels=$labels";
    ?>
    <div class="row">
        <div class="col-md-8">

            <img src="<?= $graphLink ?>" class="img-thumbnail">
            <br>
            <br>
            <br>
            <!--<a href="<?= Url::to(['detalle1','id' => $modelParalelo->id]) ?>">Ver mas...</a>-->        
            <a href="<?= Url::to(['form','id' => $modelParalelo->id]) ?>">| Ver mas... |</a>        
            <a href="<?= Url::to(['/planificaciones-coordinador/index1','id' => $modelParalelo->id]) ?>">| Ver planificaciones... |</a>        

        </div>


        <div class="col-md-4">

            <?php echo Html::beginForm(['detalle', 'post']); ?>

            <!--<label class="control-label">Parcial:</label>-->
            <?php
            echo '<label class="control-label">Parcial:</label>';
            echo Select2::widget([
                'name' => 'parcial',
                'value' => 0,
                'data' => [
                    'p1' => 'PARCIAL 1',
                    'p2' => 'PARCIAL 2',
                    'p3' => 'PARCIAL 3',
                    'ex1' => 'EXAMEN 1',
                    'p4' => 'PARCIAL 4',
                    'p5' => 'PARCIAL 5',
                    'p6' => 'PARCIAL 6',
                    'ex2' => 'EXAMEN 2',
                    'final_ano_normal' => 'PROMEDIO FINAL',
                ],
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
            ?>

            <?php
            echo '<label class="control-label">Operador:</label>';
            echo Select2::widget([
                'name' => 'operador',
                'value' => 0,
                'data' => [
                    '=' => 'IGUAL',
                    '<' => 'MENOR',
                    '>' => 'MAYOR',
                ],
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
            ?>

            <label class="control-label">Valor:</label>
            <input type="number" name="valor" class="form-control" required="">
            <input type="hidden" name="id" value="<?= $modelParalelo->id ?>">

            <br>
            
            <div class="form-group">
                <?= Html::submitButton('Consultar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php echo Html::endForm(); ?>

        </div>
    </div>


</div>