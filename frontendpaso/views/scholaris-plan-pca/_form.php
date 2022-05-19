<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPca */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");
?>

<div class="scholaris-plan-pca-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-md-3">
            <?php
            $lista = \backend\models\GenAsignaturas::find()
                    ->select(["m.id", "concat(s.nombre,' - ',gen_asignaturas.nombre) as nombre"])
                    ->innerJoin("gen_malla_materia m", "gen_asignaturas.id = m.materia_id")
                    ->innerJoin("gen_malla_area a", "a.id = m.malla_area_id")
                    ->innerJoin("gen_curso c", "c.subnivel_id = a.subnivel_id")
                    ->innerJoin("gen_subnivel s", "s.id = c.subnivel_id")
                    ->orderBy("c.orden")
                    ->all();

            $listData = ArrayHelper::map($lista, 'id', 'nombre');

            echo $form->field($model, 'malla_materia_curriculo_id')->widget(Select2::className(), [
                'data' => $listData,
                'options' => ['placeholder' => 'Seleccione materia de currículo...'],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>


        <div class="col-md-3">
            <?php
            $lista = backend\models\ScholarisMateria::find()
                    ->select(["m.id", "concat(mal.nombre_malla,' - ',scholaris_materia.name) as name"])
                    ->innerJoin("scholaris_malla_materia m", "scholaris_materia.id = m.materia_id")
                    ->innerJoin("scholaris_malla_area a", "a.id = m.malla_area_id")
                    ->innerJoin("scholaris_malla_curso c", "c.malla_id = a.malla_id")
                    ->innerJoin("scholaris_malla mal", "mal.id = a.malla_id")
                    ->innerJoin("op_course cur", "cur.id = c.curso_id")
                    ->all();

            $listData = ArrayHelper::map($lista, "id", "name");

            echo $form->field($model, 'malla_materia_institucion_id')->widget(Select2::className(), [
                'data' => $listData,
                'options' => ['placeholder' => 'Seleccione materia de institución...'],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php
            $lista = \backend\models\GenCurso::find()
                    ->orderBy("orden")
                    ->all();
            $listData = ArrayHelper::map($lista, "id", "nombre");
            echo $form->field($model, 'curso_curriculo_id')->widget(Select2::className(), [
                'data' => $listData,
                'options' => ['placeholder' => 'Seleccione curso de currículo...'],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php
            $lista = \backend\models\OpCourse::find()
                    ->innerJoin("op_section s", "s.id = op_course.section")
                    ->innerJoin("scholaris_op_period_periodo_scholaris sop", "sop.op_id = s.period_id")
                    ->where(['sop.scholaris_id' => $periodoId])
                    ->all();
            $listData = ArrayHelper::map($lista, "id", "name");
            echo $form->field($model, 'curso_institucion_id')->widget(Select2::className(), [
                'data' => $listData,
                'options' => ['placeholder' => 'Seleccione curso de institución...'],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'paralelos')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'nivel_educativo')->textInput()->label('Subnivel #') ?></div>
        <div class="col-md-3"><?= $form->field($model, 'carga_horaria_semanal')->textInput() ?></div>
        <div class="col-md-3"><?= $form->field($model, 'semanas_trabajo')->textInput() ?></div>
    </div>

    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'aprendizaje_imprevistos')->textInput()->label('Número de periodos por imprevistos') ?></div>
        <div class="col-md-3"><?= $form->field($model, 'total_semanas_clase')->textInput() ?></div>
        <div class="col-md-3"><?= $form->field($model, 'total_periodos')->textInput() ?></div>
        <div class="col-md-3">
            <?php
            $lista = backend\models\OpFaculty::find()
                    ->select(["id","concat(last_name,' ',x_first_name) as last_name"])
                    ->all();
            
            $listData = ArrayHelper::map($lista, 'id', 'last_name');
            
            echo $form->field($model, 'revisado_por')->widget(Select2::className(), [
                'data' => $listData,
                'options' => ['placeholder' => 'Revisado por...'],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <?php
            $lista = backend\models\OpFaculty::find()
                    ->select(["id","concat(last_name,' ',x_first_name) as last_name"])
                    ->all();
            
            $listData = ArrayHelper::map($lista, 'id', 'last_name');
            echo $form->field($model, 'aprobado_por')->widget(Select2::className(), [
                'data' => $listData,
                'options' => ['placeholder' => 'Aprobado por...'],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
        ?>
        </div>
        <div class="col-md-6"><?= $form->field($model, 'docentes')->textarea(['rows' => 6]) ?></div>
    </div>


    <?php
    if ($model->isNewRecord) {
        echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false);
    } else {
        echo $form->field($model, 'creado_por')->hiddenInput(['maxlength' => true])->label(false);
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

    <?php
    if ($model->isNewRecord) {
        echo $form->field($model, 'estado')->hiddenInput(['value' => 'CONSTRUYENDOSE'])->label(false);
    } else {
        echo $form->field($model, 'estado')->hiddenInput(['maxlength' => true])->label(false);
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Grabar  ', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
