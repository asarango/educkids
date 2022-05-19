<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudDetalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $modelActividad->title;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de PUD',
    'url' => ['scholaris-plan-pud-detalle/editardestreza',
        'destreza' => $modelActividad->destreza_id],
];
$this->params['breadcrumbs'][] = $this->title;

//$data = ArrayHelper::map($modelDestrezas, 'destreza_codigo', 'destreza_detalle');
?>
<div class="scholaris-plan-pud-detalle-borrar">

    <div class="container">
        <div class="alert alert-danger">
            <strong>Existen calificaciones realizadas para esta actividad </strong>
            (<?= count($modelActividad) ?> - Calificaciones ) 

            <br>
            <br>
            Esta seguro de eliminar la actividad?
        </div>

        <div class="col-row">
            <div class="col-md-3">
                <?php echo Html::beginForm(['borrar', 'post']); ?>

                <input type="hidden" name="id" value="<?= $modelActividad->id ?>">
                <?php echo Html::submitButton('SI', ['class' => 'btn btn-danger']); ?>

                <?php echo Html::endForm(); ?>
            </div>

            <div class="col-md-3">
                
                <?php echo Html::a('NO', ['editardestreza', 'destreza' => $modelActividad->destreza_id], ['class' => 'btn btn-primary']); ?>
                
            </div>
        </div>



    </div>


</div>
