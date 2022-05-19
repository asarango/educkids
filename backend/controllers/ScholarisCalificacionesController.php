<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisCalificaciones;
use backend\models\ScholarisCalificacionesSearch;
use backend\models\ScholarisPeriodo;
use backend\models\ScholarisActividad;
use backend\models\ScholarisCalificacionesCambia;

use backend\models\OpCourse;
use backend\models\OpFaculty;

use backend\models\ResUsers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisCalificacionesController implements the CRUD actions for ScholarisCalificaciones model.
 */
class ScholarisCalificacionesController extends Controller
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
    
    
    public function actionIndex1(){
        
        $modelProfesor = OpFaculty::find()
                ->select(["id", "concat(last_name, ' ', x_first_name, ' ') as last_name"])
                ->all();
                
        return $this->render('index1',[
            'modelProfesor' => $modelProfesor
        ]);
    }

    
    /**
     * Lists all ScholarisCalificaciones models.
     * @return mixed
     */
    public function actionIndex2()
    {
               
        
//        $periodoId = Yii::$app->user->identity->periodo_id;
//        $modelPeriodo = ScholarisPeriodo::find()
//                ->where(['id' => $periodoId])
//                ->one();
//        
//        $searchModel = new ScholarisCalificacionesSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelPeriodo->codigo);
        
        
        //$modelCalificacion = ScholarisCalificaciones::find()->where(['id' => $_POST['calificacion']])->one();
        $modelCalificacion = $this->findModel($_POST['calificacion']);

        return $this->render('index', [   
            'modelCalificacion' => $modelCalificacion
        ]);
    }
    
    public function actionCambiaNota(){
        
        
        $usuario = \Yii::$app->user->identity->usuario;
        $modelUsuario = ResUsers::find()->where(['login' => $usuario])->one();
        
        $modelCambia = new ScholarisCalificacionesCambia();
        
        $modelCalificacion = $this->findModel($_POST['idCalif']);
        $notaSaliente = $modelCalificacion->calificacion;
        $modelCalificacion->calificacion = $_POST['nota'];
        $modelCalificacion->save();
        
        
        $modelCambia->nota_id = $_POST['idCalif'];
        $modelCambia->fecha_modificacion = date("Y-m-d");
        $modelCambia->nota_saliente = $notaSaliente;
        $modelCambia->nota_nueva = $modelCalificacion->calificacion;
        $modelCambia->motivo = $_POST['motivo'];
        $modelCambia->documento = $_POST['documento'];
        $modelCambia->aprobado_por = $_POST['autorizado'];
        $modelCambia->usuario_modifica = $modelUsuario->id;
        
        $modelCambia->save();
        
        $paralelo = $modelCalificacion->actividad->clase->paralelo_id;
        
        $sentencias = new \backend\models\SentenciasRecalcularUltima();
        $sentencias->por_paralelo($paralelo);
        
        return $this->redirect(['index1']);
        
    }

    /**
     * Displays a single ScholarisCalificaciones model.
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
     * Creates a new ScholarisCalificaciones model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScholarisCalificaciones();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisCalificaciones model.
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
     * Deletes an existing ScholarisCalificaciones model.
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
     * Finds the ScholarisCalificaciones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisCalificaciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisCalificaciones::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
