<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisTomaAsistecia;
use backend\models\ScholarisTomaAsisteciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisTomaAsisteciaController implements the CRUD actions for ScholarisTomaAsistecia model.
 */
class ScholarisTomaAsisteciaController extends Controller {

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
                    ],
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
     * Lists all ScholarisTomaAsistecia models.
     * @return mixed
     */
    public function actionIndex() {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;

        $modelPeriodo = \backend\models\ScholarisPeriodo::find()
                ->innerJoin("scholaris_op_period_periodo_scholaris sop", "sop.scholaris_id = scholaris_periodo.id")
                ->where(['scholaris_periodo.id' => $periodoId])
                ->one();
        $periodo = $modelPeriodo->codigo;

        $searchModel = new \backend\models\OpCourseParaleloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $periodoId, $institutoId);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modelPeriodo' => $modelPeriodo,
                    'institutoId' => $institutoId
        ]);
    }

    public function actionRegistrar() {

        $paralelo = $_GET['id'];
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);

        $modelTipoUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();

        $uso = $modelTipoUso->tipo_usu_bloque;

        $searchModel = new ScholarisTomaAsisteciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $paralelo);

        return $this->render('registrar', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modelParalelo' => $modelParalelo,
                    'uso' => $uso,
        ]);
    }

    /**
     * Displays a single ScholarisTomaAsistecia model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisTomaAsistecia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $sentencias = new \backend\models\SentenciasBloque();

        $paralelo = $_GET['paralelo'];
        $model = new ScholarisTomaAsistecia();

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $modelClase = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo ])->one();
        $uso = $modelClase->tipo_usu_bloque;
        

        if ($model->load(Yii::$app->request->post())) {
            $modelTipoUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
            $uso = $modelTipoUso->tipo_usu_bloque;
            $bloqueId = $sentencias->get_bloque_con_fecha($uso, $model->fecha, $modelPeriodo->codigo);

            $model->bloque_id = $bloqueId;

//            echo $model->fecha;
//            echo $model->hubo_clases;
//            echo $model->observacion;
//            die();
            //$model->save();

            $this->registra_todos_paralelos($model->fecha, $model->hubo_clases, $model->observacion, $uso);

            return $this->redirect(['registrar', 'id' => $paralelo]);
        }

        return $this->render('create', [
                    'model' => $model,
                    'paralelo' => $paralelo
        ]);
    }

    private function registra_todos_paralelos($fecha, $hubo, $observacion, $uso) {

        if ($hubo == 1) {
            $hubo = 'TRUE';
        } else {
            $hubo = 'FALSE';
        }

        $usuario = \Yii::$app->user->identity->usuario;
        $instituto = \Yii::$app->user->identity->instituto_defecto;
        $periodo = \Yii::$app->user->identity->periodo_id;

        $hoy = date('Y-m-d H:i:s');

        $con = \Yii::$app->db;
        $query = "insert into scholaris_toma_asistecia(paralelo_id, fecha, bloque_id, hubo_clases, observacion, creado_por, creado_fecha, actualizado_por, actualizado_fecha)
select 	p.id as paralelo_id
		,'$fecha' as fecha
		,(
			select 	blo.id 
			from	scholaris_clase cla
					inner join scholaris_bloque_actividad blo on blo.tipo_uso = cla.tipo_usu_bloque
			where	cla.paralelo_id = p.id
					and '$fecha' between blo.bloque_inicia and blo.bloque_finaliza
			limit 1
		) as bloque_id
		,$hubo
		,'$observacion'
		,'$usuario'
		,'$hoy'
		,'$usuario'
		,'$hoy'
from	op_course_paralelo p
		inner join op_course c on c.id = p.course_id
		inner join op_section s on s.id = c.section
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = s.period_id
		inner join scholaris_periodo pe on pe.id = sop.scholaris_id
                inner join scholaris_clase cl on cl.paralelo_id = p.id 
where	pe.id = $periodo
		and c.x_institute = $instituto 
                and cl.tipo_usu_bloque = '$uso'
		and p.id not in (select paralelo_id 
						 from 	scholaris_toma_asistecia
						 where 	paralelo_id = p.id
						 		and fecha = '$fecha') and 
( select blo.id from scholaris_clase cla inner join scholaris_bloque_actividad blo on blo.tipo_uso = cla.tipo_usu_bloque 
where cla.paralelo_id = p.id and '2020-06-26' between blo.bloque_inicia and blo.bloque_finaliza limit 1 ) is not null "
                . "group by p.id ;";

        $con->createCommand($query)->execute();
    }

    /**
     * Updates an existing ScholarisTomaAsistecia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisTomaAsistecia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisTomaAsistecia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisTomaAsistecia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisTomaAsistecia::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDetalle() {

        $sentencias = new \backend\models\SentenciasTomaAsistencia();




        if ($_POST) {
            $detalleId = $_POST['id'];
            $opcion = $_POST['opcion'];
            $campo = $_POST['campo'];
            $modelDetalle = \backend\models\ScholarisTomaAsisteciaDetalle::findOne($_POST['id']);
            $asistenciaId = $modelDetalle->toma_id;

            $this->modifica_opciones($detalleId, $opcion, $campo);
        } else {
            $asistenciaId = $_GET['id'];
        }

        $modelAsistencia = ScholarisTomaAsistecia::findOne($asistenciaId);

        $paralelo = $modelAsistencia->paralelo_id;
        $sentencias->ingresa_alumnos($paralelo, $asistenciaId);

        $detalle = $sentencias->get_detalle_asistencias($asistenciaId, $paralelo);

        return $this->render("detalle", [
                    'modelAsistencia' => $modelAsistencia,
                    'detalle' => $detalle,
        ]);
    }

    private function modifica_opciones($id, $opcion, $campo) {

        if ($opcion == 'true') {
            $opcion = true;
        } else {
            $opcion = false;
        }

        $model = \backend\models\ScholarisTomaAsisteciaDetalle::findOne($id);

        if ($campo == 'falta') {
            $model->asiste = false;
            $model->atraso = false;
            $model->falta = $opcion;
        } elseif ($campo == 'atraso') {
            $model->asiste = true;
            $model->atraso = $opcion;
            $model->falta = false;
        }
        $model->save();
    }

    public function actionJustificar() {
        $detalle = $_GET['id'];
        $model = \backend\models\ScholarisTomaAsisteciaDetalle::findOne($detalle);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['detalle', 'id' => $model->toma_id]);
        }

        return $this->render('justificar', [
                    'model' => $model
        ]);
    }

    public function actionComportamiento() {
        $sentencias = new \backend\models\SentenciasLeccionario();
        $tomaId = $_GET['id'];
        $modelToma = ScholarisTomaAsistecia::findOne($tomaId);
        $diaNumero = date("w", strtotime($modelToma->fecha));

        $modelClases = $sentencias->get_clases_fecha($modelToma->paralelo_id, $diaNumero);

        $modelEstudiantes = $sentencias->get_estudiantes($modelToma->fecha, $modelToma->paralelo_id);

        return $this->render('leccionario', [
                    'modelToma' => $modelToma,
                    'modelClases' => $modelClases,
                    'modelEstudiantes' => $modelEstudiantes
        ]);
    }

    public function actionJustificarprofesor() {

        $clase = $_GET['clase'];
        $fecha = $_GET['fecha'];
        $hora = $_GET['hora'];

        $modelHora = \backend\models\ScholarisHorariov2Hora::findOne($hora);

        $modelClase = \backend\models\ScholarisClase::findOne($clase);

        $model = new \backend\models\ScholarisAsistenciaJustificacionProfesor();

        $modelToma = ScholarisTomaAsistecia::find()
                ->where(['paralelo_id' => $modelClase->paralelo_id, 'fecha' => $fecha])
                ->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['comportamiento', 'id' => $modelToma->id]);
        }

        return $this->render('justificarprofesor', [
                    'model' => $model,
                    'modelClase' => $modelClase,
                    'fecharegistro' => $fecha,
                    'hora' => $hora,
                    'modelHora' => $modelHora
                        //'modelClase' => $modelClase
        ]);
    }

    public function actionJustificadoprofesor() {
        $clase = $_GET['clase'];
        $fecha = $_GET['fecha'];
        $hora = $_GET['hora'];
        $modelHora = \backend\models\ScholarisHorariov2Hora::findOne($hora);
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        $model = \backend\models\ScholarisAsistenciaJustificacionProfesor::find()
                ->where([
                    'codigo_persona' => $modelClase->idprofesor,
                    'fecha_registro' => $fecha,
                    'hora_registro' => $hora
                ])
                ->one();
        $modelToma = ScholarisTomaAsistecia::find()
                ->where(['paralelo_id' => $modelClase->paralelo_id, 'fecha' => $fecha])
                ->one();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['comportamiento', 'id' => $modelToma->id]);
        }

        return $this->render('justificarprofesor', [
                    'model' => $model,
                    'modelClase' => $modelClase,
                    'fecharegistro' => $fecha,
                    'hora' => $hora,
                    'modelHora' => $modelHora
        ]);
    }

    public function actionJustificaralumno() {
//        print_r($_GET);
        $novedadId = $_GET['novedadId'];
        $tomaId = $_GET['tomaId'];
        $modelNovedad = \backend\models\ScholarisAsistenciaAlumnosNovedades::findOne($novedadId);

        $model = new \backend\models\ScholarisAsistenciaJustificacionAlumno();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['comportamiento', 'id' => $tomaId]);
        }

        return $this->render('justificaralumno', [
                    'modelNovedad' => $modelNovedad,
                    'model' => $model
        ]);
    }

    public function actionUpdatejustalumno() {
        $novedadId = $_GET['novedadId'];
        $tomaId = $_GET['tomaId'];

        $modelNovedad = \backend\models\ScholarisAsistenciaAlumnosNovedades::findOne($novedadId);

        $model = \backend\models\ScholarisAsistenciaJustificacionAlumno::find()
                ->where(['novedad_id' => $novedadId])
                ->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['comportamiento', 'id' => $tomaId]);
        }

        return $this->render('justificaralumno', [
                    'modelNovedad' => $modelNovedad,
                    'model' => $model
        ]);
    }

    public function actionRegistracomportamiento() {
        $clase = $_GET['clase'];
        $fecha = $_GET['fecha'];
        $hora = $_GET['hora'];

        $modelRegistro = \backend\models\ScholarisAsistenciaProfesor::find()
                ->where(['clase_id' => $clase,
                    'fecha' => $fecha,
                    'hora_id' => $hora
                ])
                ->one();

        if ($modelRegistro) {

            $model = \backend\models\ScholarisAsistenciaProfesor::find()
                    ->where([
                        'clase_id' => $clase,
                        'hora_id' => $hora,
                        'fecha' => $fecha
                    ])
                    ->one();

            return $this->redirect(['comportamiento/index', 'id' => $model->id]);
        } else {
            $usuario = \Yii::$app->user->identity->usuario;
            $modelUsuario = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();
            $hoy = date('Y-m-d');

            $modelHora = \backend\models\ScholarisHorariov2Hora::findOne($hora);

            $model = new \backend\models\ScholarisAsistenciaProfesor();
            $model->clase_id = $clase;
            $model->hora_id = $hora;
            //$model->hora_ingresa = $modelHora->hasta;
            $model->hora_ingresa = '15:00:00';
            $model->fecha = $fecha;
            $model->user_id = $modelUsuario->id;
            $model->creado = $hoy;
            $model->modificado = $hoy;
            $model->estado = 1;
            $model->save();

            return $this->redirect(['comportamiento/index', 'id' => $model->primaryKey]);
        }
    }

}
