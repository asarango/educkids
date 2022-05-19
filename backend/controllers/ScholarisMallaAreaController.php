<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMallaArea;
use backend\models\ScholarisMallaAreaSearch;
use backend\models\ScholarisMalla;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisMallaAreaController implements the CRUD actions for ScholarisMallaArea model.
 */
class ScholarisMallaAreaController extends Controller
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
     * Lists all ScholarisMallaArea models.
     * @return mixed
     */
    public function actionIndex1($id)
    {
        $modelMalla = ScholarisMalla::find()->where(['id' => $id])->one();
        $modelArea = ScholarisMallaArea::find()
                ->where(['malla_id' => $id])
                ->orderBy("orden")
                ->all();
        
//        $searchModel = new ScholarisMallaAreaSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
            'modelMalla' => $modelMalla,
            'modelArea' => $modelArea
        ]);
    }

    /**
     * Displays a single ScholarisMallaArea model.
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
     * Creates a new ScholarisMallaArea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ScholarisMallaArea();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $id]);
        }
        
        $modelAreas = $this->get_areas_ultimo_periodo();

        return $this->render('create', [
            'model' => $model,
            'modelAreas' => $modelAreas,
            'id' => $id,
        ]);
    }
    
    private function get_areas_ultimo_periodo(){
        $con = \Yii::$app->db;
        $query = "select id, concat(name,' ',period_id) as name 
                    from	scholaris_area
                    where	period_id = (select max(period_id)
                    from 	scholaris_area)
                    order by name asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Updates an existing ScholarisMallaArea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelAreas = $this->get_areas_ultimo_periodo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $model->malla_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'id' => $model->malla_id,
            'modelAreas' => $modelAreas
        ]);
    }

    /**
     * Deletes an existing ScholarisMallaArea model.
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
     * Finds the ScholarisMallaArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisMallaArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisMallaArea::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
