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




$modelMallaCurso = ScholarisMallaCurso::find()->where(['curso_id' => $model->idcurso])->one();

echo $modelMallaCurso->malla_id;


//$modelMallaMateria = ScholarisMallaMateria::find()
//        ->select(['scholaris_malla_materia.id',"concat(scholaris_materia.name) as name"])
//        ->innerJoin("scholaris_malla_area", "scholaris_malla_area.id = scholaris_malla_materia.malla_area_id")
//        ->innerJoin("scholaris_materia","scholaris_materia.id = scholaris_malla_materia.materia_id")
//        ->where(['scholaris_malla_area.malla_id' => $modelMallaCurso->malla_id])
//        ->all();

$modelMallaMateria = ScholarisMateria::find()
        ->select(['scholaris_malla_materia.id',"concat(scholaris_materia.name) as name"])
        ->innerJoin("scholaris_malla_materia","scholaris_materia.id = scholaris_malla_materia.materia_id")
        ->innerJoin("scholaris_malla_area", "scholaris_malla_area.id = scholaris_malla_materia.malla_area_id")        
        ->where(['scholaris_malla_area.malla_id' => $modelMallaCurso->malla_id])
        ->all();
?>

<div class="scholaris-clase-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'idmateria')->textInput() ?>
    
    <?php 
    $lista = ScholarisMateria::find()
            ->innerJoin("scholaris_area", "scholaris_area.id = scholaris_materia.area_id")
            ->where(['scholaris_area.period_id' => $model->periodo_scholaris])
            ->all();
    
    $listData = ArrayHelper::map($lista, 'id', 'name');
    
    echo $form->field($model, 'idmateria')->widget(Select2::className(),[
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccion Materia'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ]
    ]);
    ?>
    
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

    <?= $form->field($model, 'idcurso')->textInput() ?>

    <?= $form->field($model, 'paralelo_id')->textInput() ?>

    <?= $form->field($model, 'peso')->textInput() ?>

    <?= $form->field($model, 'periodo_scholaris')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'promedia')->textInput() ?>

    <?= $form->field($model, 'asignado_horario')->textInput() ?>

    <?= $form->field($model, 'tipo_usu_bloque')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'todos_alumnos')->textInput() ?>

    

    <div class="form-group">
<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
