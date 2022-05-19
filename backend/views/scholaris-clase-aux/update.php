<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisClase */

if ($modelMalla) {
    $mallaNombre = $modelMalla->malla->nombre_malla;
} else {
    $mallaNombre = 'SIN MALLA - REVISAR';
}

if (isset($model->paralelo->name)) {
    $paralelo = $model->paralelo->name;
} else {
    $paralelo = 'No Asiganado';
}

$this->title = 'Actualizar Clase: ' .
        $model->id . ' / ' .
        $model->ismAreaMateria->materia->nombre . ' / ' .
        $model->profesor->last_name . ' ' . $model->profesor->x_first_name . ' / ' .
        $model->paralelo->course->name . ' - ' .
        $paralelo . ' / ' .
        'Malla: ' . $mallaNombre
;
$this->params['breadcrumbs'][] = ['label' => 'Clases', 'url' => ['scholaris-clase/index']];
$this->params['breadcrumbs'][] = $this->title;

echo $model->periodo_scholaris;

?>
<div class="scholaris-clase-update">
    <div class="row">
        <div class="col-md-6">

            <div class="row">

                <div class="panel panel-primary">
                    <div class="panel-heading">Formulario de modificación de datos de la clase</div>
                    <div class="panel-body">

                        <?php
                        echo Html::beginForm(['update', 'post']);

                        echo '<div class="row">';

                        echo '<div class="col-md-4">';
                        echo '<input type="hidden" name="materiaClase" value="'.$model->idmateria.'">';
                        echo $model->ismAreaMateria->materia->nombre;

                        echo '</div>';
                        echo '<div class="col-md-4">';

                        $listaProf = \backend\models\OpFaculty::find()
                                ->select(["id", "concat(last_name,' ',x_first_name) as last_name"])
                                ->all();
                        $dataProf = ArrayHelper::map($listaProf, 'id', 'last_name');

                        echo '<label class="control-label">Docente:</label>';
                        echo Select2::widget([
                            'name' => 'profesor',
                            'value' => $model->idprofesor,
                            'data' => $dataProf,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => 'Seleccione profesor',
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);

                        echo '</div>';
                        
                        
                        echo '<div class="col-md-4">';

                        $listaParal = backend\models\OpCourseParalelo::find()->where(['course_id' => $model->idcurso])
                                //->select(["id", "name"])
                                ->all();
                        $dataParal = ArrayHelper::map($listaParal, 'id', 'name');

                        echo '<label class="control-label">Paralelo:</label>';
                        echo Select2::widget([
                            'name' => 'paralelo',
                            'value' => $model->paralelo_id,
                            'data' => $dataParal,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => 'Seleccione paralelo',
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);

                        echo '</div>';
                        
                        echo '</div>';

                        echo '<div class="row">';
                        echo '<div class="col-md-4">';
                        echo '<label class="control-label">Peso:</label>';
                        echo '<input type="text" name="peso" class="form-control" value="' . $model->peso . '">';
                        echo '</div>';

                        echo '<div class="col-md-4">';
                        echo '<label class="control-label">Periodo:</label>';
                        echo '<input type="text" name="periodo" class="form-control" value="' . $model->periodo_scholaris . '">';
                        echo '</div>';

                        echo '<div class="col-md-4">';
                        echo '<label class="control-label">Promedia:</label>';
                        echo '<select name="promedia" class="form-control">';
                        if($model->promedia == 1){
                            $opcion = 'SI';
                        }else{
                            $opcion = 'NO';
                        }
                        echo '<option value="'.$model->promedia.'">'.$opcion.'</option>';
                        echo '<option value="1">SI</option>';
                        echo '<option value="0">NO</option>';
                        echo '</select>';
                        //echo '<input type="text" name="promedia" class="form-control" value="' . $model->promedia . '">';
                        echo '</div>';
                        echo '</div>';


                        echo '<div class="row">';
                        echo '<div class="col-md-4">';
                        $listaHor = backend\models\ScholarisHorariov2Cabecera::find()->all();
                        $dataHor = ArrayHelper::map($listaHor, 'id', 'descripcion');

                        echo '<label class="control-label">Horario:</label>';
                        echo Select2::widget([
                            'name' => 'horario',
                            'value' => $model->asignado_horario,
                            'data' => $dataHor,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => 'Seleccione Horario',
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);
                        echo '</div>';

                        echo '<div class="col-md-4">';
                        $listaComp = backend\models\ScholarisBloqueComparte::find()->all();
                        $dataComp = ArrayHelper::map($listaComp, 'valor', 'nombre');

                        echo '<label class="control-label">Bloque que comparte:</label>';
                        echo Select2::widget([
                            'name' => 'comparte',
                            'value' => $model->tipo_usu_bloque,
                            'data' => $dataComp,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => 'Seleccione Horario',
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);

                        echo '</div>';

                        echo '<div class="col-md-4">';
                        $dataTodos = [
                            0 => 'NO',
                            1 => 'SI'
                        ];
                        echo '<label class="control-label">Todos los alumnos?:</label>';
//    echo '<input type="text" name="todos" class="form-control" value="' . $model->todos_alumnos . '">';
                        echo Select2::widget([
                            'name' => 'todos',
                            'value' => $model->todos_alumnos,
                            'data' => $dataTodos,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => '¿Todos los alumnos?',
                                'required' => true,
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);
                        echo '</div>';
                        echo '</div>';


                        if ($modelMalla) {
                            $listaMall = backend\models\ScholarisMateria::find()
                                    ->select(['scholaris_malla_materia.id',
                                        "concat(scholaris_materia.name,'(',scholaris_materia.id,')') as name",
                                        "scholaris_materia.id as tipo_materia_id"
                                    ])
                                    ->innerJoin("scholaris_malla_materia", "scholaris_materia.id = scholaris_malla_materia.materia_id")
                                    ->innerJoin("scholaris_malla_area", "scholaris_malla_area.id = scholaris_malla_materia.malla_area_id")
                                    ->where(["scholaris_malla_area.malla_id" => $modelMalla->malla_id])
                                    ->all();
                        } else {
                            $listaMall = backend\models\ScholarisMateria::find()
                                    ->select(['scholaris_malla_materia.id',
                                        "concat(scholaris_materia.name,'(',scholaris_materia.id,')') as name",
                                        "scholaris_materia.id as tipo_materia_id"
                                    ])
                                    ->innerJoin("scholaris_malla_materia", "scholaris_materia.id = scholaris_malla_materia.materia_id")
                                    ->innerJoin("scholaris_malla_area", "scholaris_malla_area.id = scholaris_malla_materia.malla_area_id")
//                            ->where(["scholaris_malla_area.malla_id" => $modelMalla->malla_id])
                                    ->all();
                        }

                        echo '<div class="row">';
                        echo '<div class="col-md-4">';
                        $dataMall = ArrayHelper::map($listaMall, 'id', 'name');
                        echo '<label class="control-label">Materia Malla:</label>';
                        echo Select2::widget([
                            'name' => 'matMalla',
                            'value' => $model->malla_materia,
                            'data' => $dataMall,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => 'Seleccione Materia de la Malla',
                                'required' => true,
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);
                        
                        echo '</div>';
                        
                        
                        echo '<div class="col-md-4">';
                        
                        $listaCurr = backend\models\GenAsignaturas::find()->where(['tipo' => 'MATERIA'])->all();
                        $dataMatCur = ArrayHelper::map($listaCurr, 'codigo', 'nombre');
                        echo '<label class="control-label">Materia del Currículo:</label>';
                        echo Select2::widget([
                            'name' => 'matCurriculo',
                            'value' => $model->materia_curriculo_codigo,
                            'data' => $dataMatCur,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => 'Seleccione Materia del Currículo:',
                                'required' => false,
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);
                        
                        echo '</div>';
                        
                        echo '<div class="col-md-4">';
                        
                        $listaCurCurr = backend\models\GenCurso::find()->all();
                        $dataCursoCur = ArrayHelper::map($listaCurCurr, 'codigo', 'nombre');
                        echo '<label class="control-label">Curso del Currículo:</label>';
                        echo Select2::widget([
                            'name' => 'curCurriculo',
                            'value' => $model->codigo_curso_curriculo,
                            'data' => $dataCursoCur,
                            'size' => Select2::SMALL,
                            'options' => [
                                'placeholder' => 'Seleccione Curso del Currículo:',
                                'required' => false,
                            //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                            ],
                            'pluginLoading' => false,
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ]);
                        
                        echo '</div>';
                        
                        
                        echo '</div>';



                        echo '<input type="hidden" name="id" class="form-control" value="' . $model->id . '">';

                        echo'<br>';

                        echo Html::submitButton(
                                'Aceptar',
                                ['class' => 'btn btn-primary']
                        );


                        echo Html::endForm()
                        ?>
                    </div>
                    <div class="panel-footer">
                        <?php
                        if (isset($model->mallaMateria->materia->id)) {
                            $mallaMateria = $model->mallaMateria->materia->id;
                        } else {
                            $mallaMateria = 0;
                        }


                        if ($modelMalla) {
                            if ($model->idmateria == $mallaMateria) {
                                
                            } else {
                                echo '<div class="alert alert-danger">Las materias no corresponde la una de la otra. Por favor revisar y concatenar correctamente</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger">No existe una materia asignada a la malla</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!--hasta aqui la configuracion de la clase-->
            
            
            <div class="row">
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


        <!--LISTA DE LALUMNOS SCHOLARIS_GRUPO-->

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Listado de alumnos</div>
                <div class="panel-body">

                    <?php
                    if ($model->todos_alumnos == 1) {
                        echo Html::a('Ingresar Alumnos', ['scholaris-clase/todos', 'id' => $model->id], ['class' => 'btn btn-success']);
                    } else {
                        echo Html::a('Ingresar Alumnos', ['scholaris-clase/unitario', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    }
                    ?>
                    <hr>
                    <div class="table table-responsive">
                        <table class="table table-condensed table-striped table-hover tamano10">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Estudiante</th>
                                    <th>Curso</th>
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
                                    echo Html::a('<p class="tamano10">Retirar</p>', ['scholaris-clase/retirar', 'grupoId' => $grupo['grupo_id']], ['class' => 'btn btn-link']);
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

<script>
    prueba();

    function muestraMateriaClase() {
        console.log('ola k ase');
    }
</script>

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