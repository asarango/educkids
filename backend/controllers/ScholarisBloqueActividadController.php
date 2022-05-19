<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisBloqueActividadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisBloqueActividadController implements the CRUD actions for ScholarisBloqueActividad model.
 */
class ScholarisBloqueActividadController extends Controller
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
     * Lists all ScholarisBloqueActividad models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $periodo = \Yii::$app->user->identity->periodo_id;
        $instituto = \Yii::$app->user->identity->instituto_defecto;
        
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);
        
        $searchModel = new ScholarisBloqueActividadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelPeriodo->codigo, $instituto);
        
        $modelComparte = \backend\models\ScholarisBloqueComparte::find()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelComparte' => $modelComparte
        ]);
    }

    /**
     * Displays a single ScholarisBloqueActividad model.
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
     * Creates a new ScholarisBloqueActividad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $instituto = \Yii::$app->user->identity->instituto_defecto;
        
        
        $modelComoCalifica = \backend\models\ScholarisBloqueComoCalifica::find()->all();
        
        $model = new ScholarisBloqueActividad();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'instituto' => $instituto,
            'modelComoCalifica' => $modelComoCalifica
        ]);
    }

    /**
     * Updates an existing ScholarisBloqueActividad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $instituto = \Yii::$app->user->identity->instituto_defecto;
        $model = $this->findModel($id);
        
        $modelComoCalifica = \backend\models\ScholarisBloqueComoCalifica::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'instituto' => $instituto,
            'modelComoCalifica' => $modelComoCalifica
        ]);
    }

    /**
     * Deletes an existing ScholarisBloqueActividad model.
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
     * Finds the ScholarisBloqueActividad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisBloqueActividad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisBloqueActividad::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
