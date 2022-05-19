<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\PlanCurriculoDistribucion;
use backend\models\OpCourse;

$usuario = Yii::$app->user->identity->usuario;
$periodoId = Yii::$app->user->identity->periodo_id;

$fecha = date("Y-m-d H:i:s");


/* @var $this yii\web\View */
/* @var $model backend\models\PlanPlanificacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-planificacion-form">

    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <?php
        $lista = PlanCurriculoDistribucion::find()
                ->select(['plan_curriculo_distribucion.id',
                    "concat(plan_area.nombre, ' ' "
                    . ", plan_nivel.nombre, ' ' "
                    . ",plan_curriculo.ano_incia, ' - ', plan_curriculo.ano_finaliza) as nombre"
                ])
                ->innerJoin('plan_area', 'plan_area.id = plan_curriculo_distribucion.area_id')
                ->innerJoin("plan_nivel", "plan_nivel.id = plan_curriculo_distribucion.nivel_id")
                ->innerJoin("plan_curriculo", "plan_curriculo.id = plan_curriculo_distribucion.curriculo_id")
                //->where([''])
                ->asArray()
                ->all();
        $listData = ArrayHelper::map($lista, 'id', 'nombre');

        echo $form->field($model, 'distribucion_id')->widget(Select2::className(), [
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Distribucion...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?php
        $lista = OpCourse::find()
                ->innerJoin("scholaris_op_period_periodo_scholaris","scholaris_op_period_periodo_scholaris.op_id = op_course.x_period_id")
                ->where(["scholaris_op_period_periodo_scholaris.scholaris_id" => $periodoId])
                ->asArray()
                ->all();
        
//        print_r($model);
                
        $listData = ArrayHelper::map($lista, 'id', 'name');

        echo $form->field($model, 'curso_id')->widget(Select2::className(), [
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Curso...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?= $form->field($model, 'periodo_id')->hiddenInput(['value' => $periodoId])->label(false) ?>

        <?= $form->field($model, 'estado')->hiddenInput(['value' => 'Iniciando'])->label(false) ?>

        <?php 
        if($model->isNewRecord){
            echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false);
        }else{
            echo $form->field($model, 'creado_por')->hiddenInput(['maxlength' => true])->label(false);
        }
        ?>

        <?php 
            if($model->isNewRecord){
               echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false);
            }else{
                echo $form->field($model, 'creado_fecha')->hiddenInput()->label(false);
            }
        ?>

        <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

        <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
