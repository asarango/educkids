<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listado de estudiantes a examenes extras';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['/profesor-inicio/index']); ?>
        </li>
<!--        <li class="breadcrumb-item">
            <?php echo Html::a('Mis clases', ['/profesor-inicio/clases']); ?>
        </li>-->
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>
<div class="examenes-extras-index" style="padding-left: 40px; padding-right: 40px;">

    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            
            
            <h3><strong><?= $this->title ?></strong></h3>
            
            <?php echo Html::beginForm(['reporte', 'post']); ?>

            <?php
            $listData = ArrayHelper::map($modelCursos, 'id', 'curso');

            echo '<label class="control-label">Curso:</label>';
            echo Select2::widget([
                'name' => 'curso',
                'value' => 0,
                'data' => $listData,
                'size' => Select2::SMALL,
                'options' => [
                    'placeholder' => 'Seleccione curso',
//                    'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                ],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);

            echo '<br>';
            echo '<br>';
            
            echo '<label class="control-label">Imprimir solo cédulas:</label>';
            echo '<select name="concedulas" class="form-control">';
            echo '<option value="NO">NO</option>';
            echo '<option value="SI">SI</option>';
            echo '</select>';
            
            echo '<br>';
            echo '<br>';
            echo Html::submitButton(
                    'Aceptar',
                    ['class' => 'btn btn-primary']
            );
            
            
            echo Html::endForm(); 
            ?>  
        </div>
        <div class="col-lg-3"></div>
    </div>

</div>

