<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\ScholarisClase;
use backend\models\ScholarisClaseSearch;
use backend\models\ScholarisPeriodo;
use app\models\OpFaculty;
use backend\models\ResUsers;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisTipoActividad;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class ProfesorInicioController extends Controller {

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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {
        
        $usuarioLogueado    = \Yii::$app->user->identity->usuario;
        $periodoId          = Yii::$app->user->identity->periodo_id;
        $periodo            = ScholarisPeriodo::findOne($periodoId);
        
        $scripts = new \backend\models\helpers\Scripts();
        $clases = $scripts->get_todas_clases_profesor();

        return $this->render('index',[
            'clases' => $clases
        ]);
    }
    

    public function actionClases() {

        $usuarioLogueado = \Yii::$app->user->identity->usuario;
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $profesorPartnerId = ResUsers::find()->where(['login' => $usuarioLogueado])->one();

        $profesorId = \backend\models\OpFaculty::find()->where(['partner_id' => $profesorPartnerId->partner_id])->one();

        $modelRealizaPud = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'realizapud'])
                ->one();
        if ($modelRealizaPud) {
            $realiza = $modelRealizaPud->valor;
        } else {
            $realiza = 0;
        }


        $model = ScholarisClase::find()
                ->innerJoin("op_course c", "c.id = scholaris_clase.idcurso")
                ->leftJoin("op_course_paralelo p", "p.id = scholaris_clase.paralelo_id")
                ->where([
                    'periodo_scholaris' => $periodo,
                    'idprofesor' => $profesorId->id,
                ])
                ->orderBy('c.name', 'p.name')
                ->all();

        $modelTipoCalificacion = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where([
            'scholaris_periodo_id' => $periodoId
        ])->all();

        $modelEmergencia = $modelTipoCalificacion;
        
        
        $modelEditaProfesor = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'editaclaseprof'])->one();
        
        return $this->render('clases', [
                    'model' => $model,
                    'realiza' => $realiza,
                    'modelEmergencia' => $modelEmergencia,
                    'modelEditaProfesor' => $modelEditaProfesor
        ]);
    }

    public function actionActividades($id) {
        $clase = $id;
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelClase = ScholarisClase::find()->where(['id' => $id])->one();
        $modelBloques = ScholarisBloqueActividad::find()
                ->where(['tipo_uso' => $modelClase->tipo_usu_bloque, 'scholaris_periodo_codigo' => $periodo])
                ->orderBy("orden")
                ->all();
        if ($modelClase->course->section0->code == 'PAI') {
            $modelInsumos = ScholarisTipoActividad::find()
                    ->orderBy("orden")
                    ->all();
        } else {
//            $usertypes=Usertype::find()->where(['not',['user_type_id'=>['2,3,4']]])->all();
            $modelInsumos = ScholarisTipoActividad::find()
                    ->where(['<>', 'tipo', 'P'])
                    ->orderBy("orden")
                    ->all();
        }

        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()
                ->innerJoin("op_student_inscription i", "i.student_id = scholaris_grupo_alumno_clase.estudiante_id")
                ->where([
                    'clase_id' => $clase,
                    'i.inscription_state' => 'M'
                ])
                ->all();
        
        $modelTipoCalificacion = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'tipocalif'])->one();

        return $this->render('actividades', [
                    'modelClase' => $modelClase,
                    'modelBloques' => $modelBloques,
                    'modelInsumos' => $modelInsumos,
                    'modelGrupo' => $modelGrupo,
                    'modelTipoCalificacion' => $modelTipoCalificacion
        ]);
    }
    
    
    public function actionActividadesDetalle() {
        
        $claseId = $_GET['clase_id'];
        $bloqueId = $_GET['bloque_id'];
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
//        $periodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $modelClase = ScholarisClase::findOne($claseId);
        $modelBloques = ScholarisBloqueActividad::findOne($bloqueId);
        
        if ($modelClase->course->section0->code == 'PAI') {
            $modelInsumos = ScholarisTipoActividad::find()
                    ->orderBy("orden")
                    ->all();
        } else {
//            $usertypes=Usertype::find()->where(['not',['user_type_id'=>['2,3,4']]])->all();
            $modelInsumos = ScholarisTipoActividad::find()
                    ->where(['<>', 'tipo', 'P'])
                    ->orderBy("orden")
                    ->all();
        }

        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()
                ->innerJoin("op_student_inscription i", "i.student_id = scholaris_grupo_alumno_clase.estudiante_id")
                ->where([
                    'clase_id' => $claseId,
                    'i.inscription_state' => 'M'
                ])
                ->all();
        
        $modelTipoCalificacion = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'tipocalif'])->one();
        
        $totalCalificados = $this->total_calificados_bloque($claseId, $bloqueId);        

        return $this->render('actividades-detalle', [
                    'modelClase' => $modelClase,
                    'bloque' => $modelBloques,
                    'modelInsumos' => $modelInsumos,
                    'modelGrupo' => $modelGrupo,
                    'modelTipoCalificacion' => $modelTipoCalificacion,
                    'totalCalificados' => $totalCalificados
        ]);
    }
    

    public function actionCambiarclave() {
        $usuario = Yii::$app->user->identity->usuario;

        $model = \backend\models\Usuario::find()->where(['usuario' => $usuario])->one();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->oldAttributes['clave'] != $model->clave) {
                $model->clave = md5($model->clave);
            }

            $model->save();
            Yii::$app->user->logout();
            return $this->goHome();
        }

        return $this->render('cambiarclave', [
                    'model' => $model,
        ]);
    }

    public function actionCreate() {
        $sentencias = new \backend\models\SentenciasCursos();
        $sentenciasMalla = new \backend\models\SentenciasMallas();
        $usuario = Yii::$app->user->identity->usuario;
        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);
        $instituto = Yii::$app->user->identity->instituto_defecto;
        $modelUsuario = ResUsers::find()->where(['login' => $usuario])->one();
        $modelProfesor = \backend\models\OpFaculty::find()
                        ->where(['partner_id' => $modelUsuario->partner_id])->one();

        $modelCursos = $sentencias->get_cursos1($periodo, $instituto);
        $modelMaterias = $sentenciasMalla->get_materias($periodo);
        $modelMateriasCurriculo = $sentenciasMalla->get_materias_curriculo();


        $modelHorario = \backend\models\ScholarisHorariov2Cabecera::find()
                ->where(['periodo_id' => $periodo])
                ->all();

        $modelComparte = \backend\models\ScholarisBloqueComparte::find()->all();

        $modelCursosCurriculo = \backend\models\GenCurso::find()
                ->orderBy('nombre')
                ->all();

        $model = new ScholarisClase();

        if ($model->load(Yii::$app->request->post())) {

            $modelCodigoCurMat = \backend\models\GenMallaMateria::findOne($model->materia_curriculo_codigo);

            $model->materia_curriculo_codigo = $modelCodigoCurMat->materia->codigo;
            $modelMallaMat = \backend\models\ScholarisMallaMateria::findOne($model->malla_materia);
            $model->idmateria = $modelMallaMat->materia_id;
            $model->peso = $modelMallaMat->total_porcentaje;
            $model->idprofesor = $modelProfesor->id;
            if ($modelMallaMat == true) {
                $model->promedia = 1;
            } else {
                $model->promedia = 0;
            }

            $model->periodo_scholaris = $modelPeriodo->codigo;

            $model->save();
            $primary = $model->getPrimaryKey();
            return $this->redirect(['update', 'id' => $primary]);
        }

        return $this->render('_form', [
                    'model' => $model,
                    'profesor' => $modelProfesor->id,
                    'modelCursos' => $modelCursos,
                    'modelMaterias' => $modelMaterias,
                    'modelHorario' => $modelHorario,
                    'modelComparte' => $modelComparte,
                    'modelMateriasCurriculo' => $modelMateriasCurriculo,
                    'modelCursosCurriculo' => $modelCursosCurriculo
        ]);
    }

    public function actionUpdate() {
        $id = $_GET['id'];
        $sentencias = new \backend\models\SentenciasCursos();
        $sentenciasMalla = new \backend\models\SentenciasMallas();
        $sentenciasClases = new \backend\models\SentenciasClase();

        $usuario = Yii::$app->user->identity->usuario;
        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);
        $instituto = Yii::$app->user->identity->instituto_defecto;
        $modelUsuario = ResUsers::find()->where(['login' => $usuario])->one();
        $modelProfesor = \backend\models\OpFaculty::find()
                        ->where(['partner_id' => $modelUsuario->partner_id])->one();

        $modelCursos = $sentencias->get_cursos1($periodo, $instituto);

        $modelMaterias = $sentenciasMalla->get_materias($periodo);
        $modelMateriasCurriculo = $sentenciasMalla->get_materias_curriculo();

        $modelHorario = \backend\models\ScholarisHorariov2Cabecera::find()
                ->where(['periodo_id' => $periodo])
                ->all();

        $modelComparte = \backend\models\ScholarisBloqueComparte::find()->all();

        $modelCursosCurriculo = \backend\models\GenCurso::find()
                ->orderBy('nombre')
                ->all();


        $modelGrupo = $sentenciasClases->get_alumnos_clase($id, $periodo);

        $model = ScholarisClase::findOne($id);
        $modelParalelos = \backend\models\OpCourseParalelo::find()
                ->where(['course_id' => $model->idcurso])
                ->all();
        $modelDias = \backend\models\ScholarisHorariov2Dia::find()->orderBy('numero')->all();
        $modelHoras = $sentenciasClases->get_horas_horario($model->asignado_horario);


        if ($model->load(Yii::$app->request->post())) {

            echo $model->malla_materia;
            $modelMallaMat = \backend\models\ScholarisMallaMateria::findOne($model->malla_materia);
            $model->idmateria = $modelMallaMat->materia_id;
            $model->peso = $modelMallaMat->total_porcentaje;
            if ($modelMallaMat == true) {
                $model->promedia = 1;
            } else {
                $model->promedia = 0;
            }

            $model->periodo_scholaris = $modelPeriodo->codigo;

            $model->save();
            return $this->redirect(['update', 'id' => $id]);
        }

        return $this->render('_formupdate', [
                    'model' => $model,
                    'profesor' => $modelProfesor->id,
                    'modelCursos' => $modelCursos,
                    'modelMaterias' => $modelMaterias,
                    'modelHorario' => $modelHorario,
                    'modelComparte' => $modelComparte,
                    'modelMateriasCurriculo' => $modelMateriasCurriculo,
                    'modelCursosCurriculo' => $modelCursosCurriculo,
                    'modelGrupo' => $modelGrupo,
                    'modelParalelos' => $modelParalelos,
                    'modelDias' => $modelDias,
                    'modelHoras' => $modelHoras
        ]);
    }

    public function actionTodos() {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $id = $_GET['id'];
        $model = $this->findModel($id);

        $sentencias = new \backend\models\SentenciasClase();
        $sentencias->ingresar_alumnos_todos($id, $model->paralelo_id);


        $modelMalla = \backend\models\ScholarisMallaCurso::find()
                ->where(['curso_id' => $model->idcurso])
                ->one();

        $modelGrupo = $sentencias->get_alumnos_clase($id, $periodoId);

        return $this->redirect(['update',
                    'id' => $id
        ]);
    }

    protected function findModel($id) {
        if (($model = ScholarisClase::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionQuitar() {
        $sentencias = new \backend\models\SentenciasClase();
        $sentencias->quitar_clase_horario($_GET['clase'], $_GET['detalle']);

        return $this->redirect(['update', 'id' => $_GET['clase']]);
    }

    public function actionAsignar() {
        $sentencias = new \backend\models\SentenciasClase();
        $model = \backend\models\ScholarisHorariov2Detalle::find()
                ->where([
                    'cabecera_id' => $_GET['cabecera'],
                    'hora_id' => $_GET['hora'],
                    'dia_id' => $_GET['dia'],
                ])
                ->one();

        $sentencias->asignar_clase_horario($_GET['clase'], $model->id);
        return $this->redirect(['update', 'id' => $_GET['clase']]);
    }

    public function actionBorrar() {
        $id = $_GET['id'];
        $model = ScholarisClase::findOne($id);

        $model->delete();

        return $this->redirect(['clases']);
    }

    public function actionUnitario() {
        $sentencias = new \backend\models\SentenciasClase();

        if (isset($_GET['id'])) {

            $id = $_GET['id'];
            $model = $this->findModel($id);
            $curso = $model->idcurso;

            if ($model->mallaMateria->tipo == 'PROYECTOS') {
                $modelAlumnos = $sentencias->get_alumnos_todos();
            } else {
                $modelAlumnos = $sentencias->get_alumnos_curso($id, $curso);
            }

            return $this->render('unitario', [
                        'model' => $model,
                        'modelAlumnos' => $modelAlumnos
            ]);
        } else {

//            print_r($_POST);
            $id = $_POST['id'];
            $alumno = $_POST['alumno'];

            $modelGr = new \backend\models\ScholarisGrupoAlumnoClase();
            $modelGr->clase_id = $id;
            $modelGr->estudiante_id = $alumno;
            $modelGr->save();

            return $this->redirect(['update',
                        'id' => $id
            ]);
        }
    }

    public function actionRetirar() {
        $sentencias = new \backend\models\SentenciasClase();
        $grupoId = $_GET['grupoId'];

        $model = \backend\models\ScholarisGrupoAlumnoClase::findOne($grupoId);

        $clase = $model->clase_id;
        $modelActividades = $sentencias->get_actividades_calificadas_alumnos($grupoId);

        //echo count($modelActividades);

        if (count($modelActividades) == 0) {
            $model->delete();
        }

        return $this->redirect(['update', 'id' => $clase]);
    }

    public function actionCalificacionemergencia() {

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $clase = $_GET['id'];
        $codigo = $_GET['emergencia'];
        $modelClase = ScholarisClase::findOne($clase);
        $alumnos = new \backend\models\SentenciasCovid19();

        $modelQuimestresEmerg = \backend\models\ScholarisQuimestreTipoCalificacion::find()->where([
                    'periodo_scholaris_id' => $periodoId,
                    'codigo' => 'covid19'
                ])->all();


        if (isset($_GET['quimestretipo'])) {

            $this->ingresar_calificaciones_covid19($modelClase->paralelo_id, $_GET['quimestretipo']);
            $this->actualiza_notas_padre($modelClase->paralelo_id, $_GET['quimestretipo']);

            $modelAlumnos = $alumnos->get_calificaciones_paralelo($modelClase->paralelo_id, $_GET['quimestretipo']);

            $modelTipoQui = \backend\models\ScholarisQuimestreTipoCalificacion::findOne($_GET['quimestretipo']);

            if ($codigo == 'covid19') {
                return $this->render('calificacioncovid19', [
                            'modelClase' => $modelClase,
                            'modelAlumnos' => $modelAlumnos,
                            'modelQuimestresEmerg' => $modelQuimestresEmerg,
                            'modelTipoQui' => $modelTipoQui
                ]);
            }
        } else {
            if ($codigo == 'covid19') {
                return $this->render('calificacioncovid19', [
                            'modelClase' => $modelClase,
                            'modelQuimestresEmerg' => $modelQuimestresEmerg
                ]);
            }
        }
    }

    private function ingresar_calificaciones_covid19($paralelo, $tipoQuimestre) {

        $modelPortafolio = \backend\models\ScholarisRubricasCalificaciones::find()->where([
                    'tipo' => 'portafolio',
                    'estado' => true
                ])->one();

        $con = \Yii::$app->db;
        $query = "insert into scholaris_calificacion_covid19(inscription_id, tipo_quimestre_id, padre, portafolio, contenido, presentacion)
                        select 	i.id, $tipoQuimestre, 0,$modelPortafolio->valor,0,0
                        from 	op_student_inscription i 
                        where	i.parallel_id = $paralelo
                                        and i.inscription_state = 'M'
                                        and i.id not in (select 	inscription_id 
                        from 	scholaris_calificacion_covid19
                        where	tipo_quimestre_id = $tipoQuimestre);";
        $con->createCommand($query)->execute();
    }

    private function actualiza_notas_padre($paralelo, $quimestreTipo) {

        $alumnos = new \backend\models\SentenciasAlumnos();
        $model = $alumnos->get_alumnos_paralelo($paralelo);
        foreach ($model as $al) {

            $modelCalifiPadre = \backend\models\ScholarisQuimestreCalificacion::find()->where([
                        'inscription_id' => $al['inscription_id'],
                        'quimestre_calificacion_id' => $quimestreTipo
                    ])->one();



            $modelCalifConvid = \backend\models\ScholarisCalificacionCovid19::find()->where([
                        'inscription_id' => $al['inscription_id'],
                        'tipo_quimestre_id' => $quimestreTipo
                    ])->one();

            if(isset($modelCalifiPadre->rubrica->valor)){
                $modelCalifConvid->padre = $modelCalifiPadre->rubrica->valor;
            }else{
                $modelCalifConvid->padre = 0;
            }
            $modelCalifConvid->save();
        }
    }
    
    public function actionActualizanota(){
        
        $sentencias = new \backend\models\SentenciasCovid19();
        
        $inscriptionId = $_POST['inscriptionId'];
        $tipoQuimestre = $_POST['tipoQuimestre'];
        $campo = $_POST['campo'];
        $calificacion = $_POST['valor'];
        
        $model = \backend\models\ScholarisCalificacionCovid19::find()->where([
                        'inscription_id' => $inscriptionId,
                        'tipo_quimestre_id' => $tipoQuimestre
                    ])->one();
        
        $model->$campo = $calificacion;
        $model->save();
        
        $sentencias->calcula_total_quimestre($inscriptionId, $tipoQuimestre);
        
        
    } 
    
    
    
    
    
    public function actionCalificacion(){
        
        $fecha = date("Y-m-d H:i:s");
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $codigoCalifiacion = $_GET['claseUsada'];
        $bloqueId = $_GET['bloque_id'];
        $claseId = $_GET['clase_id'];
        
        //realiza el recalculo de nota de parciales y crea el espacio en
        //clase libreta
        
        $sentenciasRC = new \backend\models\SentenciasRecalcularUltima();
        $sentenciasRC->genera_recalculo_por_clase($claseId);
        
        /////////////////////////////////////////////////////////////
        
        $modelClase = ScholarisClase::findOne($claseId);
        $modelBloque = ScholarisBloqueActividad::findOne($bloqueId);
        $modelActividades = $this->get_total_actividades_calificadas($modelClase->paralelo_id);
        
        $alumnos = new \backend\models\SentenciasAlumnos();
        $modelAlumnos = $alumnos->get_alumnos_paralelo($modelClase->paralelo_id);
        
        $modelCalificacionAutoma = \backend\models\ScholarisParametrosOpciones::find()->where([
            'codigo' => 'covidautoma'
        ])->one();
        
        if($modelCalificacionAutoma){
            $automatico = $modelCalificacionAutoma->valor;
        }else{
            $automatico = 0;
        }
        
        $totalActividades = \backend\models\ScholarisActividad::find()->where([
            'paralelo_id' => $claseId,
            'bloque_actividad_id' => $bloqueId,
            'calificado' => 'SI'
        ])->all();
        
        
        if($fecha < $modelBloque->hasta){
            $estado = 'abierto';
        }else{
            $estado = 'cerrado';
        }
       
        return $this->render('calificacion',[
            'modelClase' => $modelClase,
            'modelBloque' => $modelBloque,
            'modelActividades' => $modelActividades,
            'modelAlumnos' => $modelAlumnos,
            'periodoId' => $periodoId,
            'codigoCalifiacion' => $codigoCalifiacion,
            'estado' => $estado,
            'totalActividades' => $totalActividades,
            'automatico' => $automatico
        ]);
    }
    
    private function total_calificados_bloque($claseId, $bloqueId){
        $con = \Yii::$app->db;
        $query = "select 	count(c.bloque_id)/4 as total_calificados 
                    from 	scholaris_calificaciones_parcial c
                                    inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id 
                    where 	g.clase_id = $claseId
                                    and c.bloque_id = $bloqueId;";
        $res = $con->createCommand($query)->queryOne();
        return $res['total_calificados'];
    }
    
    
    private function get_total_actividades_calificadas($paraleloId){
        $con = \Yii::$app->db;
        $query = "select 	count(a.id) as total
from 	scholaris_actividad a
		inner join scholaris_clase c on c.id = a.paralelo_id 
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id 
		inner join op_student_inscription i on i.student_id = g.estudiante_id 
where 	i.parallel_id = $paraleloId;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    public function actionActualizanotacovid(){
        
        $bloqueId = $_POST['bloqueId'];
        $alumnoId = $_POST['alumnoId'];
        $claseId = $_POST['claseId'];
        $queCalifica = $_POST['queCalifica'];
        $valor = $_POST['valor'];
        $motivo = $_POST['motivo'];
        $automatico = $_POST['automatico'];
        
        
        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()->where([
            'estudiante_id' => $alumnoId,
            'clase_id' => $claseId
        ])->one();
        
        $model = \backend\models\ScholarisCalificacionesParcial::find()->where([
            'bloque_id' => $bloqueId,
            'grupo_id' => $modelGrupo->id,
            'codigo_que_califica' => $queCalifica
        ])->one();
        
        
        if($automatico == 1){
            
            $hoy = date('Y-m-d H:i:s');
            $usuario = Yii::$app->user->identity->usuario;
            
            $modelCambio = new \backend\models\ScholarisCalificacionesParcialCambios();
            $modelCambio->bloque_id = $bloqueId;
            $modelCambio->grupo_id = $modelGrupo->id;
            $modelCambio->codigo_que_califica = $queCalifica;
            isset($model->nota) ? $modelCambio->nota_anterior = $model->nota : $modelCambio->nota_anterior = null;
            $modelCambio->nota_nueva = $valor;
            $modelCambio->motivo_cambio = $motivo;
            $modelCambio->fecha_cambio = $hoy;
            $modelCambio->creado_por = $usuario;
            $modelCambio->save();
        }
        
        
        
//        $model = \backend\models\ScholarisCalificacionesParcial::find()->where([
//            'bloque_id' => $bloqueId,
//            'grupo_id' => $modelGrupo->id,
//            'codigo_que_califica' => $queCalifica
//        ])->one();
        
        $model->nota = $valor;
        $model->save();
        
        
    } 

}
