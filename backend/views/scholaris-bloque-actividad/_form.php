<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisQuimestre;
use backend\models\ResUsers;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueActividad */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$periodo = Yii::$app->user->identity->periodo_id;
$modelUser = ResUsers::find()->where(['login' => $usuario])->one();

$modelPerido = \backend\models\ScholarisPeriodo::findOne($periodo);

$fecha = date("Y-m-d H:i:s");
?>

<div class="scholaris-bloque-actividad-form">

   

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'create_uid')->hiddenInput(['value' => $modelUser->id])->label(false);
        } else {
            echo $form->field($model, 'create_uid')->hiddenInput()->label(false);
        }
        ?>

        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'create_date')->hiddenInput(['value' => $fecha])->label(false);
        } else {
            echo $form->field($model, 'create_date')->hiddenInput()->label(false)->label(false);
        }
        ?>

        <?= $form->field($model, 'write_uid')->hiddenInput(['value' => $modelUser->id])->label(false) ?>

        <?= $form->field($model, 'write_date')->hiddenInput(['value' => $fecha])->label(false) ?>

        <?php
        $lista = ScholarisQuimestre::find()->where(['tipo_quimestre' => 'normal'])->all();
        $listData = ArrayHelper::map($lista, 'codigo', 'nombre');

        echo $form->field($model, 'quimestre')->widget(Select2::className(), [
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione quimestre...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?= $form->field($model, 'tipo')->hiddenInput(['value' => 'ACADEMICO'])->label(false) ?>

        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'desde')->widget(DatePicker::className(), [
                'name' => 'desde',
                'value' => date('d-M-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Desde ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]);
        }else{
            echo $form->field($model, 'desde')->textInput();
        }
        ?>

        <?php
        if($model->isNewRecord){
            echo $form->field($model, 'hasta')->widget(DatePicker::className(), [
            'name' => 'hasta',
            'value' => date('d-M-Y', strtotime('+2 days')),
            'options' => ['placeholder' => 'Hasta ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ]);
        }else{
            echo $form->field($model, 'hasta')->textInput();
        }
        
        ?>

        <?= $form->field($model, 'orden')->textInput() ?>

        <?php
        echo $form->field($model, 'scholaris_periodo_codigo')->hiddenInput(['value' => $modelPerido->codigo])->label(false);
        ?>

        <?=
        $form->field($model, 'tipo_bloque')->dropDownList([
            'PARCIAL' => 'PARCIAL',
            'EXAMEN' => 'EXAMEN'
        ])
        ?>

        <?= $form->field($model, 'dias_laborados')->textInput() ?>

        
        <?= $form->field($model, 'estado')->dropDownList([
            'activo' => 'ACTIVO',
            'inactivo' => 'INACTIVO'
        ]) ?>

        <?= $form->field($model, 'abreviatura')->textInput(['maxlength' => true]) ?>

        <?php
        $modelComparte = \backend\models\ScholarisBloqueComparte::find()->all();
        $data = ArrayHelper::map($modelComparte, 'valor', 'nombre');

        echo $form->field($model, 'tipo_uso')->widget(Select2::className(), [
            'data' => $data,
            'options' => ['placeholder' => 'Seleccione Tipo Bloque...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
        ?>

        <?=
        $form->field($model, 'bloque_inicia')->widget(DatePicker::className(), [
            'name' => 'bloque_inicia',
            'value' => date('d-M-Y', strtotime('+2 days')),
            'options' => ['placeholder' => 'Bloque Inicia ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ])
        ?>

        <?=
        $form->field($model, 'bloque_finaliza')->widget(DatePicker::className(), [
            'name' => 'bloque_finaliza',
            'value' => date('d-M-Y', strtotime('+2 days')),
            'options' => ['placeholder' => 'Bloque Finaliza ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ])
        ?>
    
        <?php
        
        $data = ArrayHelper::map($modelComoCalifica, 'codigo', 'descripcion_calificacion');

        echo $form->field($model, 'codigo_tipo_calificacion')->widget(Select2::className(), [
            'data' => $data,
            'options' => ['placeholder' => '¿Cómo califica el parcial?...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
        ?>
    
    

<?= $form->field($model, 'instituto_id')->hiddenInput(['value' => $instituto])->label(false) ?>

        <div class="form-group">
<?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
        </div>

<?php ActiveForm::end(); ?>

</div>