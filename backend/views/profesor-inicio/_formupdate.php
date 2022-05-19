<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\PlanPduEjes */
/* @var $form yii\widgets\ActiveForm */

if (isset($model->paralelo->name)) {
    $paralelo = $model->paralelo->name;
} else {
    $paralelo = 'PARALELO SIN ASIGNAR';
}

$this->title = 'Configurando mi clase: ';
$this->params['breadcrumbs'][] = ['label' => 'Clases', 'url' => ['clases']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profesor-inicio-form" style="padding-left: 40px; padding-right: 40px">

    <h6>
        <div class="alert alert-info">
            <?php
            echo $model->curso->name . ' - ' . $paralelo
            . ' / ' . $model->profesor->last_name . ' ' . $model->profesor->x_first_name
            . ' / ' . $model->materia->name;
            ?>
        </div>
    </h6>


    <div class="row">
        <div class="col-md-6">            
            <div class="col-row">
                <strong><u>DATOS DE CLASE</u></strong>
                
                <br>
                Configuraciones de la clase se realizan por parte del Administrador del Sistema
                
                <?php // $form = ActiveForm::begin(); ?>

            
            
            <?php
            
//            $listData = ArrayHelper::map($modelParalelos, 'id', 'name');
//            echo $form->field($model, 'paralelo_id')->widget(Select2::className(), [
//                'data' => $listData,
//                'options' => ['placeholder' => 'Seleccione Paralelo...'],
//                'pluginLoading' => false,
//                'pluginOptions' => [
//                    'allowClear' => false
//                ],
//            ]);
            ?>


            <?php
//            $listData = ArrayHelper::map($modelHorario, 'id', 'descripcion');
//            echo $form->field($model, 'asignado_horario')->widget(Select2::className(), [
//                'data' => $listData,
//                'options' => ['placeholder' => 'Seleccione Horario...'],
//                'pluginLoading' => false,
//                'pluginOptions' => [
//                    'allowClear' => false
//                ],
//            ]);
            ?>

            <?php
//            $listData = ArrayHelper::map($modelComparte, 'valor', 'nombre');
//            echo $form->field($model, 'tipo_usu_bloque')->widget(Select2::className(), [
//                'data' => $listData,
//                'options' => ['placeholder' => 'Seleccione Bloques que comparte...'],
//                'pluginLoading' => false,
//                'pluginOptions' => [
//                    'allowClear' => false
//                ],
//            ]);
            ?>

            <?php
//            echo $form->field($model, 'todos_alumnos')->dropDownList([
//                1 => 'SI',
//                2 => 'NO',
//            ]);
            ?>

            <?php
//            $listData = ArrayHelper::map($modelMaterias, 'id', 'materia');
//            echo $form->field($model, 'malla_materia')->widget(Select2::className(), [
//                'data' => $listData,
//                'options' => ['placeholder' => 'Seleccione materia de la institucion...'],
//                'pluginLoading' => false,
//                'pluginOptions' => [
//                    'allowClear' => false
//                ],
//            ]);
            ?>

            <?php
//            $listData = ArrayHelper::map($modelMateriasCurriculo, 'codigo', 'materia');
//            echo $form->field($model, 'materia_curriculo_codigo')->widget(Select2::className(), [
//                'data' => $listData,
//                'options' => ['placeholder' => 'Seleccione materia del curriculo...'],
//                'pluginLoading' => false,
//                'pluginOptions' => [
//                    'allowClear' => false
//                ],
//            ]);
            ?>


            <?php
//            $listData = ArrayHelper::map($modelCursosCurriculo, 'codigo', 'nombre');
//            echo $form->field($model, 'codigo_curso_curriculo')->widget(Select2::className(), [
//                'data' => $listData,
//                'options' => ['placeholder' => 'Seleccione curso del curriculo...'],
//                'pluginLoading' => false,
//                'pluginOptions' => [
//                    'allowClear' => false
//                ],
//            ]);
            ?>


            <div class="form-group">
                <?php // echo Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php // ActiveForm::end(); ?>
            </div>
            
            
            
            
            <div class="col-row">
                <div class="panel panel-warning">
                    <div class="panel-heading">Horario de clase</div>
                    <div class="panel-body">
                        <?php
                        if (isset($model->paralelo->name)) {
                            horarios($modelDias, $modelHoras, $model->paralelo_id, $model->id, $model->asignado_horario);
                        } 
                        
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Listado de alumnos</div>
                <div class="panel-body">

                    <?php
                    if ($model->todos_alumnos == 1) {
                        echo Html::a('Ingresar Alumnos', ['todos', 'id' => $model->id], ['class' => 'btn btn-success']);
                    } else {
                        echo Html::a('Ingresar Alumnos', ['unitario', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    }
                    ?>
                    <hr>
                    <div class="table table-responsive">
                        <table class="table table-condensed table-striped table-hover tamano10">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Estudiante</th>
                                    <th>Paralelo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($modelGrupo as $grupo) {
                                    $i++;
                                    echo '<tr>';
                                    echo '<td>' . $i . '</td>';
                                    echo '<td>' . $grupo['last_name'] . ' ' . $grupo['first_name'] . ' ' . $grupo['middle_name'] . '</td>';
                                    echo '<td>' . $grupo['curso'] . '</td>';
                                    echo '<td>' . $grupo['paralelo'] . '</td>';
                                    echo '<td>' . $grupo['inscription_state'] . '</td>';
                                    echo '<td>';
                                    echo Html::a('<p class="tamano10">Retirar</p>', ['retirar', 'grupoId' => $grupo['grupo_id']], ['class' => 'btn btn-link']);
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>







<?php

function horarios($modelDias, $modelHoras, $paralelo, $clase, $cabecera) {

    echo '<div class="table table-responsive">';
    echo '<table class="table table-condensed table-striped table-hover table-bordered tamano10">';
    echo '<thead>';
    echo '<tr>';
    echo '<td></td>';
    foreach ($modelDias as $dia) {
        echo '<td>' . $dia->nombre . '</td>';
    }
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($modelHoras as $hora) {
        echo '<tr>';
        echo '<td>' . $hora['sigla'] . '</td>';
        foreach ($modelDias as $d) {
            $materia = recuperaMateria($paralelo, $d->id, $hora['id']);


            if (!isset($materia['clase_id'])) {
                echo '<td>' . Html::a('<p class="text-danger">Asignar aquí</p>',
                        ['asignar', 'dia' => $d->id, 'hora' => $hora['id'], 'cabecera' => $cabecera, 'clase' => $clase],
                        ['class' => '']) . '</td>';
            } else if ($materia['clase_id'] == $clase) {
                echo '<td bgcolor="#fcf8e3">' . Html::a($materia['materia'], ['quitar', 'detalle' => $materia['detalle_id'], 'clase' => $clase], ['class' => '']) . '</td>';
            } else {
                echo '<td>' . $materia['materia'] . '</td>';
            }
        }
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function recuperaMateria($paralelo, $dia, $hora) {
    $sentencias = new \backend\models\SentenciasClase();
    $model = $sentencias->get_materia_horario($paralelo, $dia, $hora);
    return $model;
}
?>
