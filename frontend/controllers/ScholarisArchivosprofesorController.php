<?php

namespace frontend\controllers;

use Yii;
use frontend\models\ScholarisArchivosprofesor;
use frontend\models\ScholarisArchivosprofesorSearch;
use backend\models\ScholarisActividad;

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

        if ($model->load(Yii::$app->request->post())) {

            $imagenSubida = UploadedFile::getInstance($model,'archivo');
            $imagenSubida->name = str_replace(' ', '', $imagenSubida->name);           

            // var_dump($imagenSubida);
            //die();

        
            if(!empty($imagenSubida)){
                
                $path = '../web/imagenes/instituto/archivos-profesor/';
                $model->archivo = $modelActividad->id.$imagenSubida->name;            
                
                $model->save();
                $imagenSubida->saveAs($path.$modelActividad->id.$imagenSubida->name);
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
     * Deletes an existing ScholarisArchivosprofesor model.
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
     * Finds the ScholarisArchivosprofesor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisArchivosprofesor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisArchivosprofesor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
