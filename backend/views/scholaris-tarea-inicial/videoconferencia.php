<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTareaInicial */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Actualizar datos de videoconferencia: ' . $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de archivos',
    'url' => ['index1',
        'clase' => $model->clase_id,
        'quimestre' => $model->quimestre_codigo
    ]
];
//$this->params['breadcrumbs'][] = ['label' => $model->titulo, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';



$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");
?>

<div class="scholaris-tarea-inicial-videoconferencia">

    <div class="container">

        <?php
        $form = ActiveForm::begin([
                    'options' => ['enctype' => 'multipart/form-data']
        ]);
        ?>

        <?= $form->field($model, 'clase_id')->hiddenInput(['value' => $model->clase_id])->label(false) ?>

        <?= $form->field($model, 'quimestre_codigo')->hiddenInput(['value' => $model->quimestre_codigo])->label(false) ?>

        <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

        <?=
        $form->field($model, 'fecha_inicio')->widget(DatePicker::className(), [
            'name' => 'fecha_inicio',
            'value' => date('d-M-Y', strtotime('+2 days')),
            'options' => ['placeholder' => 'Seleccione feha de inicio ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ])
        ?>

        <?=
        $form->field($model, 'fecha_entrega')->widget(DatePicker::className(), [
            'name' => 'fecha_inicio',
            'value' => date('d-M-Y', strtotime('+2 days')),
            'options' => ['placeholder' => 'Seleccione feha de inicio ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ])
        ?>

        <?php // $form->field($model, 'nombre_archivo')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'nombre_archivo')->textInput() ?>
        <?= $form->field($model, 'link_videoconferencia')->textInput() ?>
        <?= $form->field($model, 'respaldo_videoconferencia')->textInput() ?>

        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false);
        } else {
            echo $form->field($model, 'creado_por')->hiddenInput()->label(false);
        }
        ?>

        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false);
        } else {
            echo $form->field($model, 'creado_fecha')->hiddenInput()->label(false);
        }
        ?>

<?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

        <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>


        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
<?php } ?>

<?php ActiveForm::end(); ?>

    </div>
</div>