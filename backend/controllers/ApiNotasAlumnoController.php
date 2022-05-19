<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use backend\models\ApiHelpers;

/**
 * RolController implements the CRUD actions for Rol model.
 */
class ApiNotasAlumnoController extends Controller {

    private $periodCode;
    private $periodScholarisId;
    private $studentId;
    private $parallelId;
    private $tipoCalificacion;
    private $average = array();
    private $proyectos;
    private $comportamiento;

    public function actionIndex() {

        //recoge datos get
        $headers = Yii::$app->request->headers;
        $token = $headers->get('Authorization');        

        $apiHelpers = new ApiHelpers();
        $validateToken = $apiHelpers->validateTokenAuthorization($token);

        if ($validateToken == true) {

            $this->studentId = $_GET['student_id'];
            /////////////////////////////////////////////////////////////////////////////
            //asigna periodo scholaris id
            $modelPeriodo = \backend\models\ScholarisPeriodo::find()->orderBy(['id' => SORT_DESC])->one();
            $this->periodScholarisId = $modelPeriodo->id;
            $this->periodCode = $modelPeriodo->codigo;
            /////////////////////////////////////////////////////////////////////////////////////////////////////
            //toma el tipo de calificacion 
            $calificacion = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $this->periodScholarisId])->one();
            $this->tipoCalificacion = $calificacion->codigo;
            ///////////////////////////////////////////////////////////////////////////////////////
            //toma datos de alumno
            $studentData = $this->student_data();

            if ($studentData == false) {
                $resp = array(
                    'status' => 'error',
                    'message' => 'El estudiante no existe para este periodo!!!'
                );
            } else {

                //asigna paralelo al atributo
                $this->parallelId = $studentData['parallel_id'];

                //generea actualizacion de notas en tabla clase libreta
                new \backend\models\ProcesaNotasNormales($this->parallelId, $this->studentId);

                //toma informacion de las asignatutas
                $dataAsignaturas = $this->asignaturas();

                //toma promedios, para tomar los resulatados de:
                //promedios, proyectos escolares y comportamiento
                $this->get_average();

                $resp = array(
                    'status' => 'success',
                    'message' => 'Datos de estudiante consultados correctamente!!!',
                    'general_data' => $studentData,
                    'asignaturas_data' => $dataAsignaturas,
                    'average' => $this->average,
                    'proyectos' => $this->proyectos,
                    'comportamiento' => $this->comportamiento
                );
            }
            ////FIN DE DATOS DE ALUMNO
        }else{
            $resp = array(
                'status'    => 'error',
                'message'   => 'La Autorizacion por cabecera es incorrecto, por favor comunicar al Administrador para que se provea un token'
            );
        }

        return json_encode($resp);
    }

///FIN DE METODO INDEX

    private function student_data() {
        $con = Yii::$app->db;
        $query = "select 	concat(s.first_name,' ',s.middle_name,' ',s.last_name) as name
                                ,c.name as course		
                                ,p.name as parallel
                                ,p.id as parallel_id
                from 	op_student s
                                inner join op_student_inscription i on i.student_id = s.id 
                                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
                                inner join op_course c on c.id = i.course_id
                                inner join op_course_paralelo p on p.id = i.parallel_id 
                where	s.id = $this->studentId
                                and sop.scholaris_id = $this->periodScholarisId;";

        $res = $con->createCommand($query)->queryOne();

        if (!is_null($res)) {
            return $res;
        } else {
            return false;
        }
    }

    private function asignaturas() {
        $areas = $this->sql_areas();

        $arrayAreas = array();

        foreach ($areas as $area) {
            $areaId = $area['id'];

            if ($area['se_imprime'] == true) {
                if ($this->tipoCalificacion == 0) {
                    $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasNormales();
                } elseif ($this->tipoCalificacion == 2) {
                    $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasDisciplinar();
                } elseif ($this->tipoCalificacion == 3) {
                    $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasInterdisciplinar();
                } else {
                    echo 'No tiene creado un tipo de calificación para esta institutción!!!';
                    die();
                }

                $notasArea = $sentenciasNotasAlumnos->get_nota_area($areaId, $this->studentId, $this->parallelId, 'admin');
            } else {
                $notasArea = 'No puede mostrar notas para el area';
            }

            $materias = $this->sql_materias($areaId);

            $arrayMaterias = array();

            foreach ($materias as $materia) {
                $grupoId = $materia['grupo_id'];
                $notas = $this->get_calification_materia($grupoId);

                $materia['notas'] = $notas;

                $arrayMaterias = $materia;
            }

            $area['notas'] = $notasArea;
            $area['materias'] = $arrayMaterias;
            array_push($arrayAreas, $area);
        }

        return $arrayAreas;
    }

//FIN DE TRATAMIENTO DE ASIGNATURAS

    private function sql_areas() {
        $con = Yii::$app->db;
        $query = "select 	a.id 
                                ,a.name as area
                                ,ma.orden 
                                ,ma.promedia 
                                ,ma.se_imprime
                from	scholaris_grupo_alumno_clase g
                                inner join scholaris_clase c on c.id = g.clase_id 
                                inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                                inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
                                inner join scholaris_area a on a.id = ma.area_id 
                where	g.estudiante_id  = $this->studentId
                                and c.periodo_scholaris = '$this->periodCode'
                                and ma.tipo in ('NORMAL', 'OPTATIVAS')
                group by a.id, a.name, ma.orden, ma.promedia,ma.se_imprime
                order by ma.orden ;";

        $res = $con->createCommand($query)->queryAll();

        if (!is_null($res)) {
            return $res;
        } else {
            return false;
        }
    }

    private function sql_materias($areaId) {
        $con = Yii::$app->db;
        $query = "select 	g.id as grupo_id
		,m.name as materia
		,mm.promedia 
		,mm.se_imprime 
from	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
		inner join scholaris_malla_area ma on ma.id = mm.malla_area_id 
		inner join scholaris_materia m on m.id = mm.materia_id 
where	g.estudiante_id  = $this->studentId
		and c.periodo_scholaris = '$this->periodCode'
		and ma.tipo in ('NORMAL', 'OPTATIVAS')
		and ma.area_id = $areaId
order by ma.orden ;";

        $res = $con->createCommand($query)->queryAll();

        if (!is_null($res)) {
            return $res;
        } else {
            return false;
        }
    }

    private function get_calification_materia($grupoId) {
        //$sentencias = new \backend\models\SentenciasRepLibreta2();        

        if ($this->tipoCalificacion == 0) {
            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasNormales();
        } elseif ($this->tipoCalificacion == 2) {
            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasDisciplinar();
        } elseif ($this->tipoCalificacion == 3) {
            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasInterdisciplinar();
        } else {
            echo 'No tiene creado un tipo de calificación para esta institutción!!!';
            die();
        }

        $notasM = $sentenciasNotasAlumnos->get_nota_materia($grupoId);

        return $notasM;
    }

    /// metodo que toma promedios, proyectos escolares y comportamiento
    private function get_average() {

        $parallel = \backend\models\OpCourseParalelo::findOne($this->parallelId);
        $courseConf = \backend\models\ScholarisCursoImprimeLibreta::find()->where(['curso_id' => $parallel->course_id])->one();

//        busca la clase de encuetra notas
        if ($this->tipoCalificacion == 0) {
            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasNormales();
        } elseif ($this->tipoCalificacion == 2) {
            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasDisciplinar();
        } elseif ($this->tipoCalificacion == 3) {
            $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasInterdisciplinar();
        } else {
            echo 'No tiene creado un tipo de calificación para esta institutción!!!';
            die();
        }//fin de busca la clase de notas
        //toma datos de promedios
        $this->average = $sentenciasNotasAlumnos->get_promedio_alumno($this->studentId, $this->parallelId, 'admin');

        ///encuentra notas de proyectos escolares
        if (is_null($courseConf->tipo_proyectos)) {
            $this->proyectos = 'Este curso no tiene configurado proyectos escolares';
        } else {
            $proyectos = new \backend\models\MecProcesaMaterias();
            $notas = $proyectos->get_proyectos($this->studentId, $this->parallelId, 'q1');
        }//fin de notas de proyectos escolares
        ////encuentra notas de comportamiento
        $notas = new \backend\models\ComportamientoProyectos($this->studentId, $this->parallelId);
        $this->comportamiento = $notas->arrayNotasComp;
    }

}
