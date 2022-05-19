<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisMateria;
use backend\models\ScholarisMalla;
use backend\models\ScholarisMallaCurso;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClase */
/* @var $form yii\widgets\ActiveForm */

$periodoId = Yii::$app->user->identity->periodo_id;
$institutoId = Yii::$app->user->identity->instituto_defecto;

$modelPerido = backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();



?>

<div class="scholaris-clase-form" style="padding-left: 50px; padding-right: 50px">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'idmateria')->textInput() ?>
    
    
    
    <?php
    $listData = ArrayHelper::map($modelMallaMateria, 'id', 'name');

    echo $form->field($model, 'malla_materia')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione malla...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>

    <?php 
        $lista = \backend\models\OpFaculty::find()
                ->select(["id","concat(last_name,' ',x_first_name) as last_name"])
                ->orderBy("last_name")
                ->all();        
        $listData = ArrayHelper::map($lista, 'id', 'last_name');
                
        echo $form->field($model, 'idprofesor')->widget(Select2::className(),[
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Docente...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
    ?>
    
    <?php 
        $lista = backend\models\OpCourse::find()
                ->innerJoin("op_section s","s.id = op_course.section")
                ->innerJoin("scholaris_op_period_periodo_scholaris sop","sop.op_id = s.period_id")
                ->innerJoin("scholaris_periodo p","p.id = sop.scholaris_id")
                ->innerJoin("op_period op","op.id = s.period_id")
                ->where(["p.id" => $periodoId, "op.institute" => $institutoId])
                ->all();
        $listData = ArrayHelper::map($lista, 'id', 'name');
                
        echo $form->field($model, 'idcurso')->widget(Select2::className(),[
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Curso...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
    ?>
    
    
    <?php 
        $lista = backend\models\ScholarisHorariov2Cabecera::find()
                ->where(['periodo_id' => $periodoId])
                ->all();
        $listData = ArrayHelper::map($lista, 'id', 'descripcion');
                
        echo $form->field($model, 'asignado_horario')->widget(Select2::className(),[
            'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Horario...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ]
        ]);
    ?>
    
    <?= $form->field($model,'periodo_scholaris')->hiddenInput(['value' => $modelPerido->codigo])->label(false) ?>

    

    

    <div class="form-group">
<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
