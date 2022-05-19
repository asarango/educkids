<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMecV2MallaMateria;
use backend\models\ScholarisMecV2MallaMateriaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisMecV2MallaMateriaController implements the CRUD actions for ScholarisMecV2MallaMateria model.
 */
class ScholarisMecV2MallaMateriaController extends Controller
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
     * Lists all ScholarisMecV2MallaMateria models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScholarisMecV2MallaMateriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScholarisMecV2MallaMateria model.
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
     * Creates a new ScholarisMecV2MallaMateria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $areaId = $_GET['id'];
        $modelArea = \backend\models\ScholarisMecV2MallaArea::findOne($areaId);
        
        $model = new ScholarisMecV2MallaMateria();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['scholaris-mec-v2-malla-area/index1', 'id' => $modelArea->malla_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelArea' => $modelArea
        ]);
    }

    /**
     * Updates an existing ScholarisMecV2MallaMateria model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModel($id);
        $modelArea = \backend\models\ScholarisMecV2MallaArea::findOne($model->area_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['scholaris-mec-v2-malla-area/index1', 'id' => $modelArea->malla_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelArea' => $modelArea
        ]);
    }

    /**
     * Deletes an existing ScholarisMecV2MallaMateria model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $mallaId = $model->area->malla_id;
        $model->delete();

        return $this->redirect(['scholaris-mec-v2-malla-area/index1','id' => $mallaId]);
    }

    /**
     * Finds the ScholarisMecV2MallaMateria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisMecV2MallaMateria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisMecV2MallaMateria::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
