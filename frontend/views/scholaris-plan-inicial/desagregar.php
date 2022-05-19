<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'DESAGREGACION DE DESTREZA: ' . $model->clase->curso->name . ' - '
        . $model->clase->paralelo->name
        . ' / ' . $model->clase->materia->name
        . ' / ' . $model->clase->profesor->last_name
        . ' ' . $model->clase->profesor->x_first_name
;
$this->params['breadcrumbs'][] = ['label' => 'Planificacion',
    'url' => ['planificar',
        'id' => $ambitoId,
        'quimestre' => $model->quimestre_codigo,
        'clase' => $model->clase_id
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pud-planificar">

    <div class="alert alert-info">
        <strong><?= $model->codigo_destreza ?></strong>
        <?= $model->destreza_original ?>
    </div>

    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'destreza_desagregada')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'estado')->dropDownList([
                    'APROBADO' => 'APROBADO',
                    'CONSTRUYENDO' => 'CONSTRUYENDO'
                ]); 
        ?>

        
        <div class="form-group">
            <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
