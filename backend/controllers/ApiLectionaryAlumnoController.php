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
class ApiLectionaryAlumnoController extends Controller {

    private $periodCode;
    private $periodScholarisId;
    private $studentId;
    private $parallelId;
    private $comportamiento;

    public function actionIndex() {

        //recoge datos get
        $headers = Yii::$app->request->headers;
        $token = $headers->get('Authorization');

        $apiHelpers = new ApiHelpers();
        $validateToken = $apiHelpers->validateTokenAuthorization($token);

        if ($validateToken == true) {
            ///si es verdadero se realiza el proceso de conulta            
            $this->studentId = $_GET['student_id'];
            /////////////////////////////////////////////////////////////////////////////
            //asigna periodo scholaris id
            $modelPeriodo = \backend\models\ScholarisPeriodo::find()->orderBy(['id' => SORT_DESC])->one();
            $this->periodScholarisId = $modelPeriodo->id;
            $this->periodCode = $modelPeriodo->codigo;
            /////////////////////////////////////////////////////////////////////////////////////////////////////            
            
            //toma datos de alumno
            $studentData = $this->student_data();

            if ($studentData == false) {
                $resp = array(
                    'status' => 'error',
                    'message' => 'El estudiante no existe para este periodo!!!'
                );
            } else {

                //asigna paralelo al atributo y se realiza la consulta del proceso de leccionario
                $this->parallelId = $studentData['parallel_id'];

                //proceso de leccionario
                $lectionary = $this->lectionary();
                
                $resp = array(
                    'status' => 'success',
                    'message' => 'Datos de estudiante consultados correctamente!!!',
                    'student' => $studentData,
                    'lectionary' => $lectionary
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
    
    
    private function lectionary(){
        $con    = Yii::$app->db;
        $query  = "select 	a.fecha as fecha
                                    ,m.name as asignatura
                                    ,concat(f.x_first_name,' ',f.last_name) as docente
                                    ,d.codigo
                                    ,d.nombre as falta
                                    ,case 
                                            when (select count(id) from scholaris_asistencia_justificacion_alumno where novedad_id = n.id) > 0 then true
                                            else false
                                    end as justificado
                    from 	scholaris_asistencia_alumnos_novedades n
                                    inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id 
                                    inner join scholaris_clase c on c.id = g.clase_id 
                                    inner join scholaris_asistencia_comportamiento_detalle d on d.id = n.comportamiento_detalle_id 
                                    inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id 
                                    inner join scholaris_materia m on m.id = c.idmateria 
                                    inner join op_faculty f on f.id = c.idprofesor 
                    where	g.estudiante_id = $this->studentId
                                    and c.periodo_scholaris = '$this->periodCode';";
        
        $resp = $con->createCommand($query)->queryAll();
        return $resp;
    }


}
