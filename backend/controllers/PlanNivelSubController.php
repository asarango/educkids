<?php

namespace backend\controllers;

use Yii;
use backend\models\PlanNivelSub;
use backend\models\PlanNivelSubSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * PlanNivelSubController implements the CRUD actions for PlanNivelSub model.
 */
class PlanNivelSubController extends Controller
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
     * Lists all PlanNivelSub models.
     * @return mixed
     */
    public function actionIndex1($id)
    {
        $searchModel = new PlanNivelSubSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id
        ]);
    }

    /**
     * Displays a single PlanNivelSub model.
     * @param integer $curso_template_id
     * @param integer $nivel_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($curso_template_id, $nivel_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($curso_template_id, $nivel_id),
        ]);
    }

    /**
     * Creates a new PlanNivelSub model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new PlanNivelSub();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'curso_template_id' => $model->curso_template_id, 'nivel_id' => $model->nivel_id]);
            return $this->redirect(['index1', 'id' => $id]);
        }

        return $this->render('create', [
            'model' => $model,
            'id' => $id
        ]);
    }

    /**
     * Updates an existing PlanNivelSub model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $curso_template_id
     * @param integer $nivel_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($curso_template_id, $nivel_id)
    {
        $model = $this->findModel($curso_template_id, $nivel_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'curso_template_id' => $model->curso_template_id, 'nivel_id' => $model->nivel_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PlanNivelSub model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $curso_template_id
     * @param integer $nivel_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($curso_template_id, $nivel_id)
    {
        $this->findModel($curso_template_id, $nivel_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PlanNivelSub model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $curso_template_id
     * @param integer $nivel_id
     * @return PlanNivelSub the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($curso_template_id, $nivel_id)
    {
        if (($model = PlanNivelSub::findOne(['curso_template_id' => $curso_template_id, 'nivel_id' => $nivel_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
