<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CurCurriculoKidsCriterioEvaluacion */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cur Curriculo Kids Criterio Evaluacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="cur-curriculo-kids-criterio-evaluacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'codigo',
            'nombre:ntext',
            'estado:boolean',
        ],
    ]) ?>

</div>


<?= Html::beginForm(['insert-class', 'id' => ''], 'post') ?>
          
                <!--type, input name, input value, options-->
                <?= Html::input('hidden', 'criterio_evaluacion_id', $model->id, ['class' => 'form-control']) ?>

                <select  name="clase_id" class="select2 select2-hidden-accessible" style="width: 60%;" tabindex="-1" aria-hidden="true">
                    <option selected="selected" value="" >Destreza</option>
                    <?php
                    foreach ($destrezasSeleccionadas as $disponible) {
                        echo '<option value="' . $disponible['codigo'] . '">' . $disponible['nombre'] . '</option>';
                    }
                    ?>
                </select>
         
            
         
                <?= Html::submitButton('Ingresar', ['class' => 'btn btn-success submit my-text-medium']) ?>
           
            <?= Html::endForm() ?>


<!--SCRIPTS Y JQUERYS PARA SELECT 2-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
