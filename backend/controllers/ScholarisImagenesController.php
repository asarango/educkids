<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisImagenes;
use backend\models\ScholarisImagenesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\UploadForm;
use yii\web\UploadedFile;

/**
 * ScholarisImagenesController implements the CRUD actions for ScholarisImagenes model.
 */
class ScholarisImagenesController extends Controller
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
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
                    'message' => "No puede ingresar a este sitio. Por favor comuníquese con el Administrador para que le conceda permisos de acceso.",
                    'name' => 'Esto no es un Error!!! - Acceso denegado',
                ]);
            }
        } else {
            header("Location:" . \yii\helpers\Url::to(['site/login']));
            exit();
        }
        return true;
    }

    /**
     * Lists all ScholarisImagenes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScholarisImagenesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScholarisImagenes model.
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
     * Creates a new ScholarisImagenes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScholarisImagenes();

        if ($model->load(Yii::$app->request->post())) {
            
            $fileUploadImage = UploadedFile::getInstance($model, 'fileImagen');
            
            if(empty($fileUploadImage)){
                $model->nombre_archivo = 'noimage.png';
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                $model->nombre_archivo = rand(1,12345).'.'.$fileUploadImage->extension;
                if($model->save()){
                    $fileUploadImage->saveAs('imagenesEducandi/'.$model->nombre_archivo);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScholarisImagenes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $fileUploadImage = UploadedFile::getInstance($model, 'fileImagen');
            
            if(empty($fileUploadImage)){                
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                $lastImage = $model->nombre_archivo;
                $model->nombre_archivo = rand(1,12345).'.'.$fileUploadImage->extension;
                if($model->save()){
                    $fileUploadImage->saveAs('imagenesEducandi/'.$model->nombre_archivo);
                    unlink('imagenesEducandi/'.$lastImage);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisImagenes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
            
        
        $model = $this->findModel($id);
        if(isset($model->nombre_archivo)){
            $lastImage = $model->nombre_archivo;
            
            if($model->delete()){
                unlink('imagenesEducandi/'.$lastImage);
            }
            
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisImagenes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisImagenes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisImagenes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
