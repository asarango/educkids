<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * RolController implements the CRUD actions for Rol model.
 */
class FinAnoController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (Yii::$app->user->identity) {

            //OBTENGO LA OPERACION ACTUAL
            list($controlador, $action) = explode("/", Yii::$app->controller->route);
            $operacion_actual = $controlador . "-" . $action;
            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
                    'name' => 'Acceso denegado!!',
                ]);
            }
        } else {
            header("Location:" . \yii\helpers\Url::to(['site/login']));
            exit();
        }
        return true;
    }

    /**
     * Lists all Rol models.
     * @return mixed
     */
    public function actionIndex1() {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $institutoId = \Yii::$app->user->identity->instituto_defecto;

        $searchModel = new \backend\models\OpCourseParaleloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $periodoId, $institutoId);

        $listaCursos = \backend\models\OpCourse::find()
                ->innerJoin("op_section s", "s.id = op_course.section")
                ->innerJoin("scholaris_op_period_periodo_scholaris sop", "sop.op_id = s.period_id")
                ->innerJoin("scholaris_periodo p", "p.id = sop.scholaris_id")
                ->innerJoin("op_period op", "op.id = s.period_id")
                ->where([
                    'op.institute' => $institutoId,
                    'p.id' => $periodoId
                ])
                ->all();

        /*
         * Para tomar los datos de los cierres
         */

        $fechaMaxima = $this->toma_maxima_fecha_cierre();

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'listaCursos' => $listaCursos,
                    'fechaMaxima' => $fechaMaxima
        ]);
    }

    private function toma_maxima_fecha_cierre() {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPerido = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $con = \Yii::$app->db;
        $query = "select 	count(fecha_cierre) as total, fecha_cierre 
                    from 	scholaris_clase
                    where 	periodo_scholaris = '$modelPerido->codigo'
                    group by fecha_cierre 
                    order by count(fecha_cierre) desc
                    limit 1;";
        $res = $con->createCommand($query)->queryOne();
        return $res['fecha_cierre'];
    }

    public function actionDetallecerrar() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        $sentencias = new \backend\models\SentenciasRepLibreta2();

        $paralelo = $_GET['id'];

        $modelParalelo = \backend\models\OpCourseParalelo::find()->where(['id' => $paralelo])->one();

        $sentencias->procesarAreas($modelParalelo->course_id, $paralelo);

        $modelClases = \backend\models\ScholarisClase::find()
                ->where(['paralelo_id' => $paralelo])
                ->all();

        $mallaCurso = \backend\models\ScholarisMallaCurso::find()->where(['curso_id' => $modelParalelo->course_id])->one();

        $mallaArea = \backend\models\ScholarisMallaArea::find()
                        ->where(['malla_id' => $mallaCurso->malla_id])
                        ->andWhere(["<>", "tipo", 'COMPORTAMIENTO'])
                        ->orderBy("orden")->all();

        $modelAlumnos = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription i", "i.student_id = op_student.id")
                ->where(["i.parallel_id" => $paralelo])
                ->orderBy("op_student.last_name", "op_student.first_name", "op_student.middle_name")
                ->all();


        $modelTipoRetportes = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'repmecper'])
                ->one();


        $modelTipoEmergencia = \backend\models\ScholarisQuimestreTipoCalificacion::find()->where([
                    'codigo' => 'covid19'
                ])->all();

        $modelCalificaciones = \backend\models\ScholarisCalificacionCovid19::find()
                ->innerJoin("op_student_inscription i", "i.id = scholaris_calificacion_covid19.inscription_id")
                ->where([
                    'i.parallel_id' => $paralelo,
                    'i.inscription_state' => 'M'
                ])
                ->all();


        $modelPromediosAnuales = \backend\models\ScholarisPromediosAnuales::find()
                ->innerJoin("op_student_inscription i", "i.id = scholaris_promedios_anuales.alumno_inscription_id")
                ->where([
                    'i.parallel_id' => $paralelo,
                    'i.inscription_state' => 'M'
                ])
                ->all();


        return $this->render('detalle', [
                    'modelClases' => $modelClases,
                    'modelParalelo' => $modelParalelo,
                    'modelAlumnos' => $modelAlumnos,
                    'mallaArea' => $mallaArea,
                    'malla' => $mallaCurso->malla_id,
                    'modelTipoRetportes' => $modelTipoRetportes,
                    'modelTipoEmergencia' => $modelTipoEmergencia,
                    'modelCalificaciones' => $modelCalificaciones,
                    'modelPromediosAnuales' => $modelPromediosAnuales,
                    'periodoId' => $periodoId
        ]);
    }

    public function actionCerrar() {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $sentencias = new \backend\models\SentenciasFinAno();

        $paralelo = $_GET['paralelo'];
        $malla = $_GET['mallaid'];


        $periodoScholaris = Yii::$app->user->identity->periodo_id;

        $modelAlumnos = \backend\models\OpStudent::find()
                ->innerJoin("op_student_inscription i", "i.student_id = op_student.id")
                ->where(["i.parallel_id" => $paralelo])
                ->orderBy("op_student.last_name", "op_student.first_name", "op_student.middle_name")
                ->all();

        $modelMallaArea = \backend\models\ScholarisMallaArea::find()
                ->where(['malla_id' => $malla])
                ->all();

        foreach ($modelAlumnos as $alumno) {
            $sentenciasNxx = new \backend\models\SentenciasNotasDefinitivasAlumno($alumno->id, $periodoId, $paralelo);            
            //$notaFinal = $sentencias->nota_final_alumno($modelMallaArea, $alumno->id, $paralelo);           
            
            $notaFinal = $sentenciasNxx->notaFinalAprovechamiento;
            $this->sentar_nota_final($alumno->id, $paralelo, $periodoScholaris, $notaFinal);
        }


        return $this->redirect(['detallecerrar', 'id' => $paralelo]);
    }

    private function sentar_nota_final($alumno, $paralelo, $periodoScholaris, $notaFinal) {

        $usuario = Yii::$app->user->identity->usuario;
        $fecha = date("Y-m-d H:i:s");

        $modelInscription = \backend\models\OpStudentInscription::find()
                ->where(['student_id' => $alumno, 'parallel_id' => $paralelo])
                ->one();

        $modelSentar = \backend\models\ScholarisPromediosAnuales::findOne($modelInscription->id);

        if (!$modelSentar) {
            $model = new \backend\models\ScholarisPromediosAnuales();
            $model->alumno_inscription_id = $modelInscription->id;
            $model->scholaris_periodo_id = $periodoScholaris;
            $model->creado_por = $usuario;
            $model->creado_fecha = $fecha;
            $model->actualizado_por = $usuario;
            $model->actualizado_fecha = $fecha;
            $model->save();

            $this->sentar_ejecucion($modelInscription->id, $periodoScholaris, $notaFinal);
        } else {
            $this->sentar_ejecucion($modelInscription->id, $periodoScholaris, $notaFinal);
        }
    }

    private function sentar_ejecucion($alumnoInscription, $scholarisPeriodo, $nota) {

        $usuario = Yii::$app->user->identity->usuario;
        $fecha = date("Y-m-d H:i:s");

        $model = \backend\models\ScholarisPromediosAnuales::find()
                ->where(['alumno_inscription_id' => $alumnoInscription, 'scholaris_periodo_id' => $scholarisPeriodo])
                ->one();

        $seccion = $model->alumnoInscription->parallel->course->section0->code;

        $notaComp = $this->toma_comportamiento($model->alumnoInscription->student_id, $seccion);

        $model->nota_aprovechamiento = $nota;
        $model->nota_comportamiento = $notaComp;
        $model->actualizado_por = $usuario;
        $model->actualizado_fecha = $fecha;

        $model->save();
    }

    private function toma_comportamiento($alumnoId, $seccion) {

        $sentencias = new \backend\models\Notas();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modeloPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $con = Yii::$app->db;
        $query = "select 	l.p6
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia ma on ma.id = c.malla_materia
		inner join scholaris_clase_libreta l on l.grupo_id = g.id
where	g.estudiante_id = $alumnoId
		and c.periodo_scholaris = '$modeloPeriodo->codigo'
		and ma.tipo = 'COMPORTAMIENTO';";
        $res = $con->createCommand($query)->queryOne();

        $nota = $sentencias->homologa_comportamiento($res['p6'], $seccion);
        return $nota;
    }

    public function actionActivaboton() {
        $tipo = $_POST['tipo'];

        switch ($tipo) {
            case 'todos':
                $inicia = $_POST['inicia'];
                $finaliza = $_POST['finaliza'];
                $this->actualiza_todas_clases($inicia, $finaliza);
                break;
        }
    }

    private function actualiza_todas_clases($inicia, $finaliza) {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        $periodoCodigo = $modelPeriodo->codigo;

        $con = \Yii::$app->db;
        $query = "update scholaris_clase "
                . "set fecha_activacion = '$inicia', "
                . "fecha_cierre = '$finaliza' "
                . "where periodo_scholaris = '$periodoCodigo'";

        $con->createCommand($query)->execute();


        return $this->redirect(['index1']);
    }

    public function actionCerrarconcovid() {
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $sentencias = new \backend\models\SentenciasAlumnos();
        $sentenciasCovid = new \backend\models\SentenciasCovid19();
        $usuario = Yii::$app->user->identity->usuario;
        

        $modelTipoQuimestre = \backend\models\ScholarisQuimestreTipoCalificacion::find()->where([
                    'periodo_scholaris_id' => $periodoId,
                    'codigo' => 'covid19'
                ])->all();

        $paralelo = $_GET['paralelo'];

        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        $modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo);

        $modelMalla = \backend\models\ScholarisClase::find()->where([
                    'paralelo_id' => $paralelo
                ])->one();

        $mallaId = $modelMalla->mallaMateria->mallaArea->malla_id;
        
        
//         $modelMallaArea = \backend\models\ScholarisMallaArea::find()
//                ->where(['malla_id' => $mallaId])
//                ->all();

        $resultados = $sentenciasCovid->calcula_notas_paralelo($modelAlumnos, $mallaId, $modelTipoQuimestre);

        if (isset($_GET['accion'])) {
           // $this->sentar_nota_con_covid($paralelo, $modelTipoQuimestre, $resultados);
            $sentenciasFin = new \backend\models\Notas();

            foreach ($modelAlumnos as $alumno) {
                $sentenciasNxx = new \backend\models\SentenciasNotasDefinitivasAlumno($alumno['id'], $periodoId, $paralelo);
                 //$notaFinal = $sentenciasFin->get_notas_finales($alumno['id'], $usuario, $mallaId);
                 $notaFinal = $sentenciasNxx->notaFinalAprovechamiento;

//                $this->sentar_nota_final($alumno['id'], $paralelo, $periodoId, $notaFinal['final_total']);
                $this->sentar_nota_final($alumno['id'], $paralelo, $periodoId, $notaFinal);
            }


            return $this->redirect(['detallecerrar',
                        'id' => $paralelo
            ]);
        }

        return $this->render('cerrarconcovid', [
                    'modelParalelo' => $modelParalelo,
                    'resultados' => $resultados,
                    'modelTipoQuimestre' => $modelTipoQuimestre,
                    'modelAlumnos' => $modelAlumnos,
                    'mallaId' => $mallaId,
                    'periodoId' => $periodoId
        ]);
    }

    private function sentar_nota_con_covid($paralelo, $modelTipoQuimestre, $resultados) {
        $this->ingresar_espacios_promedios_anuales($paralelo);
        $periodo = Yii::$app->user->identity->periodo_id;
        $sentencias = new \backend\models\Notas();
        $sentenciasLib = new \backend\models\SentenciasRepLibreta2();

        foreach ($resultados as $res) {

            $model = \backend\models\ScholarisPromediosAnuales::find()->where([
                        'alumno_inscription_id' => $res['id'],
                        'scholaris_periodo_id' => $periodo
                    ])->one();

            $q1 = $res['q1'];
            $q2 = $res['q2'];
            $q1covid = $res['covidq1'];
            $q2covid = $res['covidq2'];



            if (count($modelTipoQuimestre) == 2) {
                $final = $sentencias->truncarNota(($q2covid + $q2covid) / 2, 2);
            } else {
                foreach ($modelTipoQuimestre as $tipo) {
                    if ($tipo->quimestre->orden == 1) {
                        $final = $sentencias->truncarNota(($q1covid + $q2) / 2, 2);
                    } else {
                        $final = $sentencias->truncarNota(($q1 + $q2covid) / 2, 2);
                    }
                }
            }

            $modelAl = \backend\models\OpStudentInscription::findOne($res['id']);
            $notaComp = $sentenciasLib->get_notas_finales_comportamiento($modelAl->id);
            $model->nota_aprovechamiento = $final;
            $model->nota_comportamiento = $notaComp[5];
            $model->save();
        }
    }

    private function ingresar_espacios_promedios_anuales($paralelo) {
        $periodo = Yii::$app->user->identity->periodo_id;
        $usuario = Yii::$app->user->identity->usuario;
        $con = Yii::$app->db;
        $query = "insert into scholaris_promedios_anuales (alumno_inscription_id, scholaris_periodo_id, nota_aprovechamiento, nota_comportamiento, creado_por, creado_fecha, actualizado_por, actualizado_fecha)
                        select 	i.id, $periodo,0,0,'$usuario',current_timestamp, '$usuario', current_timestamp 
                        from	op_student_inscription i
                        where	i.parallel_id = $paralelo
                                        and i.inscription_state = 'M'
                                        and i.id not in (
                                                select alumno_inscription_id from scholaris_promedios_anuales spa 
                                                where scholaris_periodo_id = $periodo
                                        );";
        $con->createCommand($query)->execute();
    }

}
