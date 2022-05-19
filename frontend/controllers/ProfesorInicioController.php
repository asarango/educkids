<?php

namespace frontend\controllers;

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
        return $this->render('index');
    }

    public function actionClases() {

        $usuarioLogueado = \Yii::$app->user->identity->usuario;
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $periodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

        $profesorPartnerId = ResUsers::find()->where(['login' => $usuarioLogueado])->one();

        $profesorId = OpFaculty::find()->where(['partner_id' => $profesorPartnerId->partner_id])->one();
        
        $modelRealizaPud = \backend\models\ScholarisParametrosOpciones::find()
                ->where(['codigo' => 'realizapud'])
                ->one();
        if($modelRealizaPud){
            $realiza = $modelRealizaPud->valor;
        }else{
            $realiza = 0;
        }
        

        $model = ScholarisClase::find()
                ->innerJoin("op_course c","c.id = scholaris_clase.idcurso")
                ->leftJoin("op_course_paralelo p","p.id = scholaris_clase.paralelo_id")
                ->where([
                         'periodo_scholaris' => $periodo,
                         'idprofesor' => $profesorId->id,
                        ])
                ->orderBy('c.name','p.name')
                ->all();
        
        

        return $this->render('clases', [
                    'model' => $model,
                    'realiza' => $realiza
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
        if($modelClase->course->section0->code == 'PAI'){
            $modelInsumos = ScholarisTipoActividad::find()                    
                    ->orderBy("orden")
                    ->all();
        }else{
//            $usertypes=Usertype::find()->where(['not',['user_type_id'=>['2,3,4']]])->all();
            $modelInsumos = ScholarisTipoActividad::find()
                    ->where(['<>', 'tipo', 'P'])
                    ->orderBy("orden")
                    ->all();
        }
        
        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()->where(['clase_id' => $clase])->all();
        
        return $this->render('actividades',[
            'modelClase' => $modelClase,
            'modelBloques' => $modelBloques,
            'modelInsumos' => $modelInsumos,
            'modelGrupo' => $modelGrupo
        ]);
        
    }
    
    public function actionCambiarclave(){
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
    
    public function actionCreate(){
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
            if($modelMallaMat == true){
             $model->promedia = 1;   
            }else{
              $model->promedia = 0;  
            }
            
            $model->periodo_scholaris = $modelPeriodo->codigo;
            
            $model->save();
            $primary = $model->getPrimaryKey();
            return $this->redirect(['update','id' => $primary]);
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
    
    public function actionUpdate(){
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
            if($modelMallaMat == true){
             $model->promedia = 1;   
            }else{
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
    
    public function actionQuitar(){
        $sentencias = new \backend\models\SentenciasClase();
        $sentencias->quitar_clase_horario($_GET['clase'], $_GET['detalle']);

        return $this->redirect(['update','id' => $_GET['clase']]);
    }
    
    public function actionAsignar(){
        $sentencias = new \backend\models\SentenciasClase();
        $model = \backend\models\ScholarisHorariov2Detalle::find()
                ->where([
                        'cabecera_id' => $_GET['cabecera'],
                        'hora_id' => $_GET['hora'],
                        'dia_id' => $_GET['dia'],
                    ])
                ->one();
        
        $sentencias->asignar_clase_horario($_GET['clase'], $model->id);
        return $this->redirect(['update','id' => $_GET['clase']]);
    }
    
    public function actionBorrar(){
        $id = $_GET['id'];
        $model = \app\models\ScholarisClase::findOne($id);
        
        $model->delete();
        
        return $this->redirect(['clases']);
        
    }

    public function actionUnitario() {
        $sentencias = new \backend\models\SentenciasClase();

        if (isset($_GET['id'])) {

            $id = $_GET['id'];
            $model = $this->findModel($id);
            $curso = $model->idcurso;

            if($model->mallaMateria->tipo == 'PROYECTOS'){
                $modelAlumnos = $sentencias->get_alumnos_todos();  
            }else{
                $modelAlumnos = $sentencias->get_alumnos_curso($id, $curso);
            }

            return $this->render('unitario', [
                        'model' => $model,
                        'modelAlumnos' => $modelAlumnos
            ]);
        }else{
            
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

    public function actionRetirar(){
        $sentencias = new \backend\models\SentenciasClase();
        $grupoId = $_GET['grupoId'];

        $model =  \backend\models\ScholarisGrupoAlumnoClase::findOne($grupoId);
        
        $clase = $model->clase_id;
        $modelActividades = $sentencias->get_actividades_calificadas_alumnos($grupoId);

        //echo count($modelActividades);

        if(count($modelActividades)==0){
            $model->delete();
        }
        
        return $this->redirect(['update','id' => $clase]);

    }

}
