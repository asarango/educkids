<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registro de Leccionario: ' . $modelToma->paralelo->course->name;
$this->params['breadcrumbs'][] = $this->title;


//$listaCursos = backend\models\OpCourse::find()
//                ->innerJoin("scholaris_clase", "op_course.id = scholaris_clase.idcurso")
//                ->where([
//                    "scholaris_clase.periodo_scholaris" => $modelPeriodo->codigo,
//                    "op_course.x_institute" => $institutoId
//                ])->all();
?>
<style>
    .tamano10{
        font-size: 10px;
    }
</style>
<div class="scholaris-toma-asistecia-leccionario">



    <p>
        <?php //echo Html::a('Create Scholaris Toma Asistecia', ['create'], ['class' => 'btn btn-success'])  ?>
    </p>

    <!--inicio docente-->
    <div class="row">
        <div class="well">
            <p class="text text-info" align=" center">1: DOCENTE</p>
            <?php echo get_docente($modelClases, $modelToma->fecha) ?>
        </div>       
    </div>

    <!--fin docentes-->

    <!--inicio ESTUDIANTES-->
    <div class="row">
        <div class="well">
            <p class="text text-info" align=" center">2: ESTUDIANTES</p>
            <?php echo get_estudiantes($modelEstudiantes, $modelToma) ?>
        </div>       
    </div>

    <!--fin ESTUDIANTES-->


</div>


<?php

function get_estudiantes($modelEstudiantes, $modelToma) {
    $html = '';
    $html .= '<div class="table table-responsive">';
    $html .= '<table class="table table-condensed table-hover tamano10">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>HORA</th>';
    $html .= '<th>ASIGNATURA</th>';
    $html .= '<th>ESTUDIANTE</th>';
    $html .= '<th>COMPORTAMIENTO</th>';
    $html .= '<th>DETALLE</th>';
    $html .= '<th colspan="2">ACCIONES</th>';    
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    foreach ($modelEstudiantes as $est) {
        $html .= '<tr>';
        $html .= '<td>' . $est['sigla'] . '</td>';
        $html .= '<td>' . $est['materia'] . '</td>';
        $html .= '<td>' . $est['est_apellido'] . ' ' . $est['est_nombre1'] . ' ' . $est['est_nombre2'] . '</td>';
        $html .= '<td>' . $est['comportamiento']. '</td>';
        $html .= '<td>' . $est['nombre']. '</td>';
        
        $html .= '<td>'. asistencia_alumno($est['novedad_id'],$modelToma).'</td>';
        
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';

    return $html;
}

function get_docente($modelClase, $fecha) {

    $html = '';
    $html .= '<div class="table table-responsive">';
    $html .= '<table class="table table-condensed table-hover tamano10">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>HORA</th>';
    $html .= '<th>DESDE</th>';
    $html .= '<th>HASTA</th>';
    $html .= '<th>ASIGNATURA</th>';
    $html .= '<th>PROFESOR</th>';
    $html .= '<th>HORA DE INGRESO</th>';
    $html .= '<th>ESTADO</th>';
    $html .= '<th>JUSTIFICACION</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    foreach ($modelClase as $clase) {
        $html .= '<tr>';
        $html .= '<td>' . $clase['sigla'] . '</td>';
        $html .= '<td>' . $clase['desde'] . '</td>';
        $html .= '<td>' . $clase['hasta'] . '</td>';
        $html .= '<td>' . $clase['materia'] . '</td>';
        $html .= '<td>' . $clase['last_name'] . ' ' . $clase['x_first_name'] . '</td>';
        $html .= asistencia_profesor($clase['clase_id'], $fecha, $clase['desde'], $clase['hasta'], $clase['hora_id']);
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';
    return $html;
}

function asistencia_profesor($clase, $fecha, $desde, $hasta, $horaId) {
    $html = '';
    $desde = strtotime($desde, time());
    $hasta = strtotime($hasta, time());
    $modelAsistencia = app\models\ScholarisAsistenciaProfesor::find()
            ->where([
                'fecha' => $fecha,
                'clase_id' => $clase,
                'hora_id' => $horaId
            ])
            ->orderBy("hora_ingresa")
            ->one();
    if ($modelAsistencia) {
        $html .= '<td>';
        $html .= $modelAsistencia->hora_ingresa;
        $ingresa = strtotime($modelAsistencia->hora_ingresa, time());
        $html .= '</td>';

        if ($ingresa >= $desde && $ingresa <= $hasta) {
            $estado = 'OK';
            //$html .= '<td>OK</td>';
        } else {
            $estado = 'FUERA DE TIEMPO';
            //$html .= '<td></td>';
        }
    } else {
        $estado = 'FALTA';
        $html .= '<td></td>';
    }

    $html .= '<td>' . $estado . '</td>';
    if ($estado == 'OK') {
        $html .= '<td bgcolor="#02420C"></td>';
        $html .= '<td bgcolor="#02420C"></td>';
    } else {
        $modelJusti = consulta_justificacion($clase, $fecha, $horaId);
        if (count($modelJusti) > 0) {
            $html .= '<td>';
            $html .= Html::a('Justificado', ['justificadoprofesor', 'clase' => $clase, 'fecha' => $fecha, 'hora' => $horaId], ['class' => 'btn btn-info btn-block btn-sm']);
            $html .= '</td>';
        } else {
            $html .= '<td></td>';
        }
        $html .= '<td>';
        $html .= Html::a('Justificar', ['justificarprofesor', 'clase' => $clase, 'fecha' => $fecha, 'hora' => $horaId],
                        ['class' => 'btn btn-danger btn-block btn-sm']);
        $html .= '</td>';
    }
    return $html;
}

function consulta_justificacion($clase, $fechaRegistro, $horaRegistro) {

    $modelClase = app\models\ScholarisClase::findOne($clase);

    $model = backend\models\ScholarisAsistenciaJustificacionProfesor::find()
            ->where([
                'codigo_persona' => $modelClase->idprofesor,
                'fecha_registro' => $fechaRegistro,
                'hora_registro' => $horaRegistro
            ])
            ->all();
    return $model;
}






function asistencia_alumno($novedadId,$modelToma) {
    $html = '';
//    $desde = strtotime($desde, time());
//    $hasta = strtotime($hasta, time());
    
    $modelNov = backend\models\ScholarisAsistenciaJustificacionAlumno::find()
            ->where(['novedad_id' => $novedadId])
            ->one();
    
    if($modelNov){
        $html .= '<td>';
       $html .= Html::a('Revisar Justicacion', ['updatejustalumno', 'novedadId' => $novedadId, 'tomaId' => $modelToma->id],
                        ['class' => 'btn btn-info btn-block btn-sm']);
       $html .= '</td>';
        
        $html .= '<td></td>';
        
    }else{
       $html .= '<td></td>';
       $html .= '<td>';
       $html .= Html::a('Justificar', ['justificaralumno', 'novedadId' => $novedadId, 'tomaId' => $modelToma->id],
                        ['class' => 'btn btn-danger btn-block btn-sm']);
       $html .= '</td>';
    }
    
    return $html;
}


function consulta_justificacion_alumno($clase, $fechaRegistro, $horaRegistro) {

    $modelClase = app\models\ScholarisClase::findOne($clase);

    $model = backend\models\ScholarisAsistenciaJustificacionProfesor::find()
            ->where([
                'codigo_persona' => $modelClase->idprofesor,
                'fecha_registro' => $fechaRegistro,
                'hora_registro' => $horaRegistro
            ])
            ->all();
    return $model;
}

?>
