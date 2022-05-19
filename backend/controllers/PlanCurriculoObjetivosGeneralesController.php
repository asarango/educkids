<?php

namespace backend\controllers;

use Yii;
use backend\models\PlanCurriculoObjetivosGenerales;
use backend\models\PlanCurriculoObjetivosGeneralesSearch;
use backend\models\PlanCurriculoEvaluacion;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PlanCurriculoObjetivosGeneralesController implements the CRUD actions for PlanCurriculoObjetivosGenerales model.
 */
class PlanCurriculoObjetivosGeneralesController extends Controller
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
     * Lists all PlanCurriculoObjetivosGenerales models.
     * @return mixed
     */
    public function actionIndex1($id)
    {
        $searchModel = new PlanCurriculoObjetivosGeneralesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $modelEvaluacion = PlanCurriculoEvaluacion::find()->where(['id' => $id])->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelEvaluacion' => $modelEvaluacion,
            
        ]);
    }

    /**
     * Displays a single PlanCurriculoObjetivosGenerales model.
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
     * Creates a new PlanCurriculoObjetivosGenerales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new PlanCurriculoObjetivosGenerales();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $id]);
        }

        return $this->render('create', [
            'model' => $model,
            'id' => $id,
        ]);
    }

    /**
     * Updates an existing PlanCurriculoObjetivosGenerales model.
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
     * Deletes an existing PlanCurriculoObjetivosGenerales model.
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
     * Finds the PlanCurriculoObjetivosGenerales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlanCurriculoObjetivosGenerales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlanCurriculoObjetivosGenerales::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
