<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClase */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Ingresar Alumno a la clase: ' .
        $model->id . ' / ' .
        $model->materia->name . ' / ' .
        $model->profesor->last_name . ' ' . $model->profesor->x_first_name . ' / ' .
        $model->curso->name . ' - ' . $model->paralelo->name . ' / '
//        'Malla: ' . $modelMalla->malla->nombre_malla
//        'Malla: ' . $mallaNombre
;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Clases', 'url' => ['scholaris-clase/index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="scholaris-clase-unitario">

    <div class="container">

        <?php echo Html::beginForm(['unitario', 'post']); ?>

        <?php //$form->field($model, 'idmateria')->textInput() ?>

        <?php
        $listData = ArrayHelper::map($modelAlumnos, 'id', 'nombre');

        echo '<label class="control-label">Estudiante:</label>';
        echo Select2::widget([
            'name' => 'alumno',
//                        'value' => $model->tipo_usu_bloque,
            'data' => $listData,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione Estudiante',
            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);

        echo '<input type="hidden" name="id" class="form-control" value="' . $model->id . '">';
        ?>

        <br><br>
        <div class="form-group">
            <?= Html::submitButton('Agregar', ['class' => 'btn btn-success']) ?>
        </div>
        
        

        <?php echo Html::endForm(); ?>

    </div>
</div>