<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMecV2MallaDisribucion;
use backend\models\ScholarisMecV2MallaDisribucionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisMecV2MallaDisribucionController implements the CRUD actions for ScholarisMecV2MallaDisribucion model.
 */
class ScholarisMecV2MallaDisribucionController extends Controller {

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
     * Lists all ScholarisMecV2MallaDisribucion models.
     * @return mixed
     */
    public function actionIndex1() {
        $searchModel = new ScholarisMecV2MallaDisribucionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScholarisMecV2MallaDisribucion model.
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
     * Creates a new ScholarisMecV2MallaDisribucion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        
        $model = new ScholarisMecV2MallaDisribucion();
        
        
        if (isset($_GET['id'])) {
            $materiaId = $_GET['id'];
            
            $modelMateria = \backend\models\ScholarisMecV2MallaMateria::findOne($materiaId);
        } else {
            
            $model->materia_id = $_POST['mate'];
            $model->tipo_homologacion = $_POST['tipo'];
            $model->codigo_materia_source = $_POST['materia'];
            
            $model->save();
            
            $modelMateria = \backend\models\ScholarisMecV2MallaMateria::findOne($model->materia_id);
            
            return $this->redirect([
                    'scholaris-mec-v2-malla-area/index1',
                    'id' => $modelMateria->area->malla_id
                ]);
            
        }

        

        return $this->render('create', [
                    'model' => $model,
                    'modelMateria' => $modelMateria
        ]);
    }

    /**
     * Updates an existing ScholarisMecV2MallaDisribucion model.
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
     * Deletes an existing ScholarisMecV2MallaDisribucion model.
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
     * Finds the ScholarisMecV2MallaDisribucion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisMecV2MallaDisribucion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScholarisMecV2MallaDisribucion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionDelete1(){
        $model = ScholarisMecV2MallaDisribucion::findOne($_GET['id']);
        $malla = $model->materia->area->malla_id;
        $model->delete();
        
        return $this->redirect(['scholaris-mec-v2-malla-area/index1',
                    'id' => $malla
            ]);
        
    }

}
