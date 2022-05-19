<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisClase;
use backend\models\ScholarisClaseSearch;
use backend\models\ScholarisPeriodo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisClaseController implements the CRUD actions for ScholarisClase model.
 */
class ScholarisClaseController extends Controller {

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
     * Lists all ScholarisClase models.
     * @return mixed
     */
    public function actionIndex() {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;

        $modelPerido = ScholarisPeriodo::find()
                ->where(['id' => $periodoId])
                ->one();

        $searchModel = new ScholarisClaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelPerido->codigo, $institutoId);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modelPeriodo' => $modelPerido,
                    'institutoId' => $institutoId,
        ]);
    }

    /**
     * Displays a single ScholarisClase model.
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
     * Creates a new ScholarisClase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ScholarisClase();
        $ultimo = $this->toma_ultimo_periodo_area();
       

        if ($model->load(Yii::$app->request->post())) {
            
            $modelMallaMateria = \backend\models\ScholarisMallaMateria::find()->where(['id' => $model->malla_materia])->one();                        
            $model->idmateria = $modelMallaMateria->materia_id;
//            $model->asignado_horario = $modelMallaMateria->mallaArea->malla->tipo_uso;
            $comparte = (string)$modelMallaMateria->mallaArea->malla->tipo_uso;
            $model->tipo_usu_bloque = $comparte;
            
            
            
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
             'model' => $model,
             'ultimo' => $ultimo
        ]);
    }
    
    
    private function toma_ultimo_periodo_area(){
        $con = Yii::$app->db;
        $query = "select max(period_id) as ultimo from scholaris_area;";
        $res = $con->createCommand($query)->queryOne();
        return $res['ultimo'];
    }

    /**
     * Updates an existing ScholarisClase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        
        $scripts = new \backend\models\helpers\Scripts();
        $modelMallaMateria = $scripts->sql_materias_x_periodo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'modelMallaMateria' => $modelMallaMateria
        ]);
    }

    /**
     * Deletes an existing ScholarisClase model.
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
     * Finds the ScholarisClase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisClase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisClase::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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

        return $this->redirect(['scholaris-clase-aux/update',
                    'id' => $id
        ]);
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
            
            return $this->redirect(['scholaris-clase-aux/update',
                    'id' => $id
                ]);
        }
    }
    
    
    public function actionRetirar(){      
        
        $sentencias = new \backend\models\SentenciasClase();
        
        if(isset($_GET['grupoId'])){
            $grupoId = $_GET['grupoId'];
            $model = \backend\models\ScholarisGrupoAlumnoClase::find()->where(['id' => $grupoId])->one();
            
            $modelActividades = $sentencias->get_actividades_calificadas_alumnos($grupoId);
            
            
            return $this->render('retirar',[
                'model' => $model,
                'modelActividades' => $modelActividades
            ]);
            
        }else{
            $grupoId = $_POST['grupoId'];
            $motivo = $_POST['motivo'];
            $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()->where(['id' => $grupoId])->one();
                  
            $this->registra_retiro_alumno_clase($modelGrupo, $motivo);            
            $sentencias->eliminar_alumno_clase($grupoId);
            
            return $this->redirect(['scholaris-clase-aux/update','id' => $modelGrupo->clase_id]);
            
        }
    }
    
    private function registra_retiro_alumno_clase($modelGrupo,$motivo){
        $fecha = date("Y-m-d H:i:s");
        $usuario = Yii::$app->user->identity->usuario;
        $model = new \backend\models\ScholarisAlumnoRetiradoClase();        
        
        $model->clase_id = $modelGrupo->clase_id;
        $model->alumno_id = $modelGrupo->estudiante_id;
        $model->fecha_retiro = $fecha;
        $model->motivo_retiro = $motivo;
        $model->usuario = $usuario;
        $model->save();
    }
    
    

}
