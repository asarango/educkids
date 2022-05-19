<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\ResUsers;
use kartik\select2\Select2;
use backend\models\ScholarisTipoActividad;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisActividad */
/* @var $form yii\widgets\ActiveForm */


$fecha = date("Y-m-d H:i:s");
$usuarioLogueado = Yii::$app->user->identity->usuario;
$modelUsuarios = ResUsers::find()->where(['login' => $usuarioLogueado])->one();

$usuario = $modelUsuarios->id;
?>

<div class="scholaris-actividad-form">

    <div class="container">



        <?php $form = ActiveForm::begin(); ?>


        <!--ocultos--> 

        <?= $form->field($model, 'create_date')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'write_date')->hiddenInput(['value' => $fecha])->label(FALSE) ?>

        <?= $form->field($model, 'create_uid')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'write_uid')->hiddenInput(['value' => $usuario])->label(FALSE) ?>

        <?= $form->field($model, 'color')->hiddenInput(['maxlength' => true])->label(FALSE) ?>



        <?= $form->field($model, 'fin')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'bloque_actividad_id')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'a_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

        <?= $form->field($model, 'b_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

        <?= $form->field($model, 'c_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

        <?= $form->field($model, 'd_peso')->hiddenInput(['maxlength' => true])->label(FALSE) ?>

        <?= $form->field($model, 'paralelo_id')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'materia_id')->hiddenInput()->label(FALSE) ?>

        <?php //  ?>



        <?php // $form->field($model, 'hora_id')->hiddenInput()->label(FALSE) ?>


        <?= $form->field($model, 'actividad_original')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'semana_id')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'archivo')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'descripcion_archivo')->hiddenInput()->label(FALSE) ?>

        <?= $form->field($model, 'tipo_calificacion')->hiddenInput(['maxlength' => true])->label(FALSE) ?>    

        <!--fin ocultos-->

        <?= $form->field($model, 'inicio')->textInput()->label('Fecha de entrega') ?>


        <?php
        $listData = ArrayHelper::map($horas, 'id', 'sigla');
        echo $form->field($model, 'hora_id')->dropDownList($listData, ['prompt' => 'Seleccione Hora...'])
        ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


        <?php
        if ($model->tipo_calificacion == 'N') {
            $lista = ScholarisTipoActividad::find()
                    ->andFilterWhere(['not in', 'nombre_pai', ['SUMATIVA', 'FORMATIVA']])
                    ->andWhere(['activo' => true])
                    ->orderBy("orden")
                    ->all();
            $listData = ArrayHelper::map($lista, 'id', 'nombre_nacional');

            echo $form->field($model, 'tipo_actividad_id')->widget(Select2::className(), [
                'data' => $listData,
                'options' => ['placeholder' => 'Seleccione Insumo...'],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
        } else {
            echo $form->field($model, 'tipo_actividad_id')->hiddenInput()->label(FALSE);
        }
        ?>


        <?= $form->field($model, 'videoconfecia')->textarea()->label('Link de videoconferencia') ?>

        <?= $form->field($model, 'respaldo_videoconferencia')->textarea()->label('Link de respaldo de videoconferencia') ?>

        <?= $form->field($model, 'link_aula_virtual')->textarea()->label('Link de aula virtual') ?>


        <?= $form->field($model, 'descripcion')->textarea(['rows' => '6']) ?>

        <?= $form->field($model, 'tareas')->textarea(['rows' => '6']) ?>

        <?php
        if (count($modelCalificaciones) > 0) {
            echo $form->field($model, 'calificado')->hiddenInput(['maxlength' => true])->label(FALSE);
        } else {
            echo $form->field($model, 'calificado')->dropDownList([
                'SI' => 'SI',
                'NO' => 'NO'
            ]);
        }
        ?>



        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>
</div>
