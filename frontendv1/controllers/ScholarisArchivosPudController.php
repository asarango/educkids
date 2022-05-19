<?php

namespace frontend\controllers;

use Yii;
use backend\models\ScholarisArchivosPud;
use backend\models\ScholarisArchivosPudSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\UploadForm;
use yii\web\UploadedFile;

/**
 * ScholarisArchivosPudController implements the CRUD actions for ScholarisArchivosPud model.
 */
class ScholarisArchivosPudController extends Controller
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
     * Lists all ScholarisArchivosPud models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $bloque = $_GET['bloqueId'];
        $clase = $_GET['claseId'];
        
        $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($bloque);
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        
        $searchModel = new ScholarisArchivosPudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$clase, $bloque);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelBloque' => $modelBloque,
            'modelClase' => $modelClase
        ]);
    }

    /**
     * Displays a single ScholarisArchivosPud model.
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
     * Creates a new ScholarisArchivosPud model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $bloque = $_GET['bloque'];
        $clase = $_GET['clase'];
        
        $modelBloque = \backend\models\ScholarisBloqueActividad::findOne($bloque);
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
        
        $model = new ScholarisArchivosPud();
        $modelArchivos = new UploadForm(); //nuevo

        if ($model->load(Yii::$app->request->post())) {
            
            $imagenSubida = \yii\web\UploadedFile::getInstance($model,'nombre');
//            $path = '../web/imagenes/pud/';
//            $imagenSubida->saveAs($path.$model->codigo);
            
            
            $modelArchivos->imageFile = UploadedFile::getInstance($model, 'nombre');
            if (!$modelArchivos->upload($model->codigo)) {
                // file is not uploaded successfully
                return $this->redirect(['error']);;
            }
            
            $model->nombre = $imagenSubida->name;
            $model->save();
            
            return $this->redirect(['index1', 'claseId' => $clase, 'bloqueId' => $bloque]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelBloque' => $modelBloque,
            'modelClase' => $modelClase
        ]);
    }
       
    
    /**
     * Updates an existing ScholarisArchivosPud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        return $this->redirect(['index1', 'claseId' => $model->clase_id, 'bloqueId' => $model->bloque_id]);

//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
    }
    
    public function actionDescargar($id){
        $path = '../web/imagenes/pud/';
        $model = ScholarisArchivosPud::findOne($id);
        
        $archivo = $path.$model->codigo;
        
        return \Yii::$app->response->sendFile($archivo);
        
    }

    /**
     * Deletes an existing ScholarisArchivosPud model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {       
        $path = '../web/imagenes/pud/';
        $model = $this->findModel($id);
        $archivo = $path.$model->codigo;
        $this->findModel($id)->delete();
        
        try {
            unlink($archivo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }



        
    
        return $this->redirect(['index1','claseId' => $model->clase_id, 'bloqueId' => $model->bloque_id]);
    }

    /**
     * Finds the ScholarisArchivosPud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisArchivosPud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisArchivosPud::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
