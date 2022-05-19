<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisCalificacionesInicial;
use backend\models\ScholarisCalificacionesInicialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisCalificacionesInicialController implements the CRUD actions for ScholarisCalificacionesInicial model.
 */
class ScholarisCalificacionesInicialController extends Controller
{
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
            if(!Yii::$app->user->identity->tienePermiso($operacion_actual)){
                echo $this->render('/site/error',[
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
     * Lists all ScholarisCalificacionesInicial models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $sentencias = new \backend\models\SentenciasAlumnos();
        
        $clase = $_GET['id'];
        
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        
        if(isset($_GET['quimestre'])){
            $quimestre = $_GET['quimestre'];
            $modelQuimestre = \backend\models\ScholarisQuimestre::find()->where(['orden' => $quimestre])->one();
            $quimestreCodigo = $modelQuimestre->codigo;
        }else{
            $modelQuimestre = \backend\models\ScholarisQuimestre::find()->where(['orden' => 1])->one();
            $quimestre = $modelQuimestre->id;
            $quimestreCodigo = $modelQuimestre->codigo;
        }
        
        $modelAlumnos = $sentencias->get_alumnos_paralelo($modelClase->paralelo_id);
        
        $modelPlanificacion = \backend\models\ScholarisPlanInicial::find()
                                ->where([
                                    'quimestre_codigo' => $quimestreCodigo,
                                    'clase_id' => $clase
                                ])
                                ->orderBy('orden')
                                ->all();
        
                        $this->ingresa_nota_no_evaluada($clase, $modelPlanificacion, $quimestre);
        
        
                        
        $modelQuimestre = \backend\models\ScholarisQuimestre::findOne($quimestre);
                        
        
        return $this->render('index', [
            'modelClase' => $modelClase,          
            'modelAlumnos' => $modelAlumnos,          
            'modelPlanificacion' => $modelPlanificacion,  
            'modelQuimestre' => $modelQuimestre  
        ]);
    }
    
    
    
    private function ingresa_nota_no_evaluada($clase, $modelPlanificacion, $quimestre){
        
        $modelGrupo = \backend\models\ScholarisGrupoAlumnoClase::find()->where(['clase_id' => $clase])->all();
        
        foreach ($modelGrupo as $grupo){
            
            foreach ($modelPlanificacion as $plan){
                $modelCalificacion = ScholarisCalificacionesInicial::find()
                                 ->where([
                                            'grupo_id' => $grupo->id,
                                            'quimestre_id' => $quimestre,
                                            'plan_id' => $plan->id
                                         ])
                                 ->one();
                
                
                if(!isset($modelCalificacion)){
                    
                    $usuario = \Yii::$app->user->identity->usuario;
                    $fecha = date("Y-m-d H:i:s");
                    
                    $model = new \backend\models\ScholarisCalificacionesInicial();
                    $model->grupo_id = $grupo->id;
                    $model->quimestre_id = $quimestre;
                    $model->plan_id = $plan->id;
                    $model->calificacion = 'NE';
                    $model->creado_por = $usuario;
                    $model->creado_fecha = $fecha;
                    $model->actualizado_por = $usuario;
                    $model->actualizado_fecha = $fecha;
                    $model->save();
                }
                
            }
            
            
        }

        //$con = \Yii::$app->db;
        //$query();
        //$res = $con->createCommand($query)->queryOne();
        
        
        
    }
    
    

    /**
     * Displays a single ScholarisCalificacionesInicial model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScholarisCalificacionesInicial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScholarisCalificacionesInicial();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisCalificacionesInicial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisCalificacionesInicial model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisCalificacionesInicial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisCalificacionesInicial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisCalificacionesInicial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionCambianota(){
        $id = $_POST['id'];
        $calificacion = $_POST['calificacion'];
        
        $usuario = \Yii::$app->user->identity->usuario;
        $fecha = date("Y-m-d H:i:s");
        
        $modelAntes = ScholarisCalificacionesInicial::findOne($id);
        
        
        $model = new ScholarisCalificacionesInicial();
        
        $model->grupo_id = $modelAntes->grupo_id;
        $model->plan_id = $modelAntes->plan_id;
        $model->quimestre_id = $modelAntes->quimestre_id;
        $model->calificacion = $calificacion;
        $model->creado_por = $usuario;
        $model->creado_fecha = $fecha;
        $model->actualizado_por = $usuario;
        $model->actualizado_fecha = $fecha;
    
        $model->save();    
    }
    
    public function actionObservaciones(){
//        print_r($_GET);
        $alumno = $_GET['id'];
        $quimestre = $_GET['quimestre'];
        $clase = $_GET['clase'];
        
        $modelAlumno = \backend\models\OpStudent::findOne($alumno);
        $modelQuimestre = \backend\models\ScholarisQuimestre::findOne($quimestre);
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        
        $modelDatos = $this->get_datos($alumno, $quimestre, $clase);
        
        return $this->render('observaciones',[
            'modelAlumno' => $modelAlumno,
            'modelQuimestre' => $modelQuimestre,
            'modelClase' => $modelClase,
            'modelDatos' => $modelDatos
        ]);
        
        
    }
    
    private function get_datos($alumno, $quimestre, $clase){
        $con = \Yii::$app->db;
        $query ="select 	c.id
                                ,c.creado_fecha
                                ,i.codigo_destreza
                                ,i.destreza_desagregada
                                ,c.calificacion
                                ,c.observacion
                from 	scholaris_calificaciones_inicial c
                                inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id
                                inner join scholaris_plan_inicial i on i.id = c.plan_id
                where	g.estudiante_id = $alumno
                                and c.quimestre_id = $quimestre
                                and g.clase_id = $clase
                order by c.plan_id desc
                                ,c.id desc
                                ,c.creado_fecha desc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function actionCambiaobservacion(){
        $id = $_POST['id'];
        $observacion = $_POST['observacion'];
        
        
        $model = ScholarisCalificacionesInicial::findOne($id);
        $model->observacion = $observacion;
        $model->save();
        
    }
    
    public function actionReporteindividual(){
        $alumno = $_GET['alumno'];
        $quimestre = $_GET['quimestre'];
        $clase = $_GET['clase'];
        
        $reporte = new \backend\models\ReporteInicialIndividual();
        
        $reporte->genera_reporte($alumno, $quimestre, $clase);
        
    }
    
    
    
}
