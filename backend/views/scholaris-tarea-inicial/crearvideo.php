<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTareaInicial */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Crear Videoconferencia';
$this->params['breadcrumbs'][] = ['label' => 'Principal', 'url' => ['scholaris-tarea-inicial/index1', 'clase' => $clase, 'quimestre' => $quimestre]];
$this->params['breadcrumbs'][] = $this->title;

$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");
?>

<div class="scholaris-tarea-inicial-crearvideo">

    <div class="row">
        <div class="col-lg-4 col-md-4"></div>
        <div class="col-lg-4 col-md-4">
            <?php
            $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data']
            ]);
            ?>

            <?= $form->field($model, 'clase_id')->hiddenInput(['value' => $clase])->label(false) ?>

            <?= $form->field($model, 'quimestre_codigo')->hiddenInput(['value' => $quimestre])->label(false) ?>

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

            <?= $form->field($model, 'link_videoconferencia')->textInput() ?>

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
        <div class="col-lg-4 col-md-4"></div>
    </div>



</div>
