<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisAsistenciaComportamiento */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Actualizar Criterios de comportamiento: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Comportamientos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizando comportamiento de ' . $model->nombre;
?>

<div class="scholaris-asistencia-comportamiento-form">

    <div class="container">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <h3><u><?= $this->title ?></u></h3>

    <p>
        <?= Html::a('Nuevo Detalle de Comportamiento ', ['scholaris-asistencia-comportamiento-detalle/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            'codigo',
            'nombre',
            'limite',
            'activo',
            /** INICIO BOTONES DE ACCION * */
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                'width' => '150px',
                'vAlign' => 'middle',
                'template' => '{update}',
                'buttons' => [
                ],
                'urlCreator' => function($action, $model, $key) {
                    if ($action === 'update') {
                        return \yii\helpers\Url::to(['scholaris-asistencia-comportamiento-detalle/update', 'id' => $key]);
                    }
                }
            ],
        /** FIN BOTONES DE ACCION * */
        ],
    ]);
    ?>


</div>
