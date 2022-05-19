<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPudDetalle */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Creando momento didáctico: ' . $modelDestreza->pud->clase->materia->name . '(clase: ' . $modelDestreza->pud->clase->id . ')'
        . ' / ' . $modelDestreza->pud->clase->curso->name
        . ' / ' . $modelDestreza->pud->clase->paralelo->name
        . ' / ' . $modelDestreza->pud->clase->profesor->last_name
        . '  ' . $modelDestreza->pud->clase->profesor->x_first_name
        . ' / ' . $modelDestreza->pud->bloque->name
;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de destrez pud', 'url' => ['editardestreza', 'destreza' => $modelDestreza->id]];
$this->params['breadcrumbs'][] = $this->title;




$fecha = date('Y-m-d H:i:s');
?>

<div class="scholaris-plan-pud-detalle-crear-actividad">

    <div class="container">
        <div class="alert alert-success">
            <strong><?= $modelDestreza->codigo ?></strong>
            <?= $modelDestreza->contenido ?>
        </div>



        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'destreza_id')->hiddenInput(['value' => $modelDestreza->id])->label(false) ?>        
        <?= $form->field($model, 'bloque_actividad_id')->hiddenInput(['value' => $modelDestreza->pud->bloque_id])->label(false) ?>
        <?= $form->field($model, 'paralelo_id')->hiddenInput(['value' => $modelDestreza->pud->clase_id])->label(false) ?>
        <?= $form->field($model, 'materia_id')->hiddenInput(['value' => $modelDestreza->pud->clase->idmateria])->label(false) ?>
        <?= $form->field($model, 'actividad_original')->hiddenInput(['value' => 0])->label(false) ?>        
        <?= $form->field($model, 'create_date')->hiddenInput(['value' => $fecha])->label(false) ?>
        <?= $form->field($model, 'write_date')->hiddenInput(['value' => $fecha])->label(false) ?>
        
        <?php
        $lista = \backend\models\ScholarisMomentosAcademicos::find()->all();
        $data = ArrayHelper::map($lista, 'id', 'nombre');
        echo $form->field($model, 'momento_id')->widget(Select2::className(), [
            'data' => $data,
            'options' => ['placeholder' => 'Seleccione momento...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>                

        <?= $form->field($model, 'momento_detalle')->textarea() ?>

        <?php
        echo $form->field($model, 'formativa_sumativa')->widget(Select2::className(), [
            'data' => ['F' => 'FORMATIVA', 'S' => 'SUMATIVA'],
            'options' => ['placeholder' => 'Seleccione ...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?php
        $lista = \backend\models\ScholarisTipoActividad::find()->where(['activo' => true])->all();
        $data = ArrayHelper::map($lista, 'id', 'nombre_nacional');
        echo $form->field($model, 'tipo_actividad_id')->widget(Select2::className(), [
            'data' => $data,
            'options' => ['placeholder' => 'Seleccione insumo...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?= $form->field($model, 'title')->textarea() ?>

        <?php
        $data = ArrayHelper::map($modelHorarios, 'fecha', 'fecha');
        if ($model->isNewRecord) {

            
            echo $form->field($model, 'inicio')->widget(Select2::className(), [
                'data' => $data,
                'options' => ['placeholder' => 'Seleccione fecha disponible...'],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
        }else{
            echo $form->field($model, 'inicio')->textInput();
        }
        ?>

        <?php
        echo $form->field($model, 'calificado')->widget(Select2::className(), [
            'data' => ['SI' => 'SI', 'NO' => 'NO'],
            'options' => ['placeholder' => 'Seleccione ...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?php
        echo $form->field($model, 'tipo_calificacion')->widget(Select2::className(), [
            'data' => ['N' => 'NACIONAL'],
            'options' => ['placeholder' => 'Seleccione tipo calificacion...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>
        
        <?= $form->field($model, 'con_nee')->checkbox() ?>
        
        <?= $form->field($model, 'grado_nee')->textInput() ?>
        
        <?= $form->field($model, 'observacion_nee')->textarea() ?>

        

        <div class="form-group">
<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

            <?php ActiveForm::end(); ?>

    </div>
</div>
