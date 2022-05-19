<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisAsistenciaProfesor */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Plan semanal - planificacion de destrezas: ' . $modelFaculty->x_first_name . ' '
        . $modelFaculty->last_name . ' | '
        . $modelObservacion->semana->bloque->name . ' | '
        . $modelObservacion->semana->nombre_semana . ' | '
        . $modelComparte->nombre . ' | '
;
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Plan Semanal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="plan-semanal-destrezas">

    <div class="container">
        <div class="table table-responsive">
            <table class="table table-condensed table-hover table-striped table-bordered">
                <tr>
                    <td align="center"><strong>CURSO</strong></td>
                    <td align="center"><strong>PARALELO</strong></td>
                    <td align="center"><strong>DESTREZAS</strong></td>
                    <td align="center"><strong>ACCIONES</strong></td>
                </tr>

                <?php
                $cursos = get_cursos($modelFaculty->id, $uso);
                foreach ($cursos as $cur) {
                    echo '<tr>';
                    echo '<td valign="middle">' . $cur['id'] . $cur['curso'] . '</td>';

                    $paralelos = get_paralelos($modelFaculty->id, $cur['id']);
                    echo '<td align="center">';
                    foreach ($paralelos as $par) {
                        echo $par['paralelo'] . ' / ';
                    }
                    echo '</td>';
                    echo '<td>';

                    $modelDes = backend\models\ScholarisPlanSemanalDestrezas::find()
                            ->where([
                                'curso_id' => $cur['id'],
                                'faculty_id' => $modelFaculty->id,
                                'semana_id' => $modelObservacion->semana_id,
                                'comparte_valor' => $uso
                            ])
                            ->one();
                    if ($modelDes) {
                        echo '<strong>CONCEPTOS: </strong>' . $modelDes->concepto . '<br>';
                        echo '<strong>CONTEXTOS: </strong>' . $modelDes->contexto . '<br>';
                        echo '<strong>PREGUNTAS DE INDAGACIÓN: </strong>' . $modelDes->pregunta_indagacion . '<br>';
                        echo '<strong>ENFOQUES DE HABILIDADES: </strong>' . $modelDes->enfoque;
                    }else{
                        echo '<p class="text text-danger">No planificó destrezas</p>';
                    }


                    echo '</td>';

                    echo '<td align="center">';
                    echo Html::a('Destrezas'
                            , ['creardestrezas',
                        'curso' => $cur['id'],
                        'faculty_id' => $modelFaculty->id,
                        'semana_id' => $modelObservacion->semana_id,
                        'uso' => $uso,
                        'observacionId' => $modelObservacion->id
                            ]
                            , ['class' => 'btn btn-warning']);
                    echo '</td>';
                    echo '</tr>';
                }
                ?>

            </table>
        </div>
    </div>

</div>


<?php

function get_cursos($facultyId, $uso) {

    $periodo = Yii::$app->user->identity->periodo_id;
    $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);

    $con = Yii::$app->db;
    $query = "select 	cur.id
		,cur.name as curso
                from 	scholaris_clase c
                                inner join op_course cur on cur.id = c.idcurso
                where	c.idprofesor = $facultyId
                                and c.periodo_scholaris = '$modelPeriodo->codigo'
                                and c.tipo_usu_bloque = '$uso'
                group by cur.id
                order by cur.name;";
    $res = $con->createCommand($query)->queryAll();
    return $res;
}

function get_paralelos($facultyId, $cursoId) {
    $con = Yii::$app->db;
    $query = "select 	par.id
		,par.name as paralelo
            from 	scholaris_clase c
                            inner join op_course_paralelo par on par.id = c.paralelo_id
            where	c.idprofesor = $facultyId
                            and c.idcurso = $cursoId
            group by par.id
            order by par.name;";
    $res = $con->createCommand($query)->queryAll();
    return $res;
}
?>