<?php

namespace backend\controllers;

use backend\models\PlanificacionOpciones;
use Yii;
use backend\models\ScholarisArchivosprofesor;
use backend\models\ScholarisArchivosprofesorSearch;
use backend\models\ScholarisActividad;
use backend\models\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\web\UploadedFile;

/**
 * ScholarisArchivosprofesorController implements the CRUD actions for ScholarisArchivosprofesor model.
 */
class ScholarisArchivosprofesorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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

    public function beforeAction($action)
    {
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
     * Lists all ScholarisArchivosprofesor models.
     * @return mixed
     */
    public function actionIndex1($id)
    {
        $searchModel = new ScholarisArchivosprofesorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScholarisArchivosprofesor model.
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
     * Creates a new ScholarisArchivosprofesor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        
        $modelActividad = ScholarisActividad::find()->where(['id' => $id])->one(); 
        $model = new ScholarisArchivosprofesor();
        /** Extrae path donde se almacena los archivos */
        $path_archivo_profesor = PlanificacionOpciones::find()->where([
            'tipo'=>'SUBIDA_ARCHIVO',
            'categoria'=>'PATH_PROFE'
        ])->one();
        $pathArchivos =$path_archivo_profesor->opcion.$id.'/';       
       
        if (!file_exists($pathArchivos)) {            
            mkdir($pathArchivos, 0777);
        }        
        if ($model->load(Yii::$app->request->post())) {
            $imagenSubida = UploadedFile::getInstance($model,'archivo');         
            if(!empty($imagenSubida))
            {  
                $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);
                $path = $pathArchivos.$imagenSubida->name;
                $model->archivo = $modelActividad->id.'##'.$imagenSubida->name;                 
                if ($imagenSubida->saveAs($path))
                {
                    $model->save();
                }
            }
            else
            { 
                $model->save();
            }
                       
            return $this->redirect(['scholaris-actividad/actividad', 'actividad' => $model->idactividad]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelActividad' => $modelActividad,
        ]);
    }

    /**
     * Updates an existing ScholarisArchivosprofesor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()    
    {        
        $idTarea = $_POST['ScholarisArchivosprofesor']['id'];
        $idActividad = $_POST['ScholarisArchivosprofesor']['idactividad'];
        
        $model = ScholarisArchivosprofesor::findOne($idTarea);    
        $archivo = $model->archivo.$_POST['ScholarisArchivosprofesor']['archivo'];       

        $model->archivo = $archivo;
        $model->nombre_archivo = $_POST['ScholarisArchivosprofesor']['nombre_archivo'];
        $model->publicar= $_POST['ScholarisArchivosprofesor']['publicar'];
        $model->texto = $_POST['ScholarisArchivosprofesor']['texto'];  
        
        $model->save();
        
        return $this->redirect(['scholaris-actividad/actividad', 'actividad' => $idActividad]);
    }

    /**
     * Deletes an existing ScholarisArchivosprofesor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id,$idActividad)
    {
       
        
       echo '<pre>';
       print_r($id);
       echo '<pre>';
       print_r($idActividad);
       die();
        // $idTarea = $_POST['ScholarisArchivosprofesor']['id'];
        // $idActividad = $_POST['ScholarisArchivosprofesor']['idactividad'];
        $model = ScholarisArchivosprofesor::findOne($id);         
        $model->delete();        
        return $this->redirect(['scholaris-actividad/actividad', 'actividad' => $idActividad]);
    }


}
