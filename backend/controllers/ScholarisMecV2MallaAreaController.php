<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMecV2MallaArea;
use backend\models\ScholarisMecV2MallaAreaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisMecV2MallaAreaController implements the CRUD actions for ScholarisMecV2MallaArea model.
 */
class ScholarisMecV2MallaAreaController extends Controller
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
     * Lists all ScholarisMecV2MallaArea models.
     * @return mixed
     */
    public function actionIndex1()
    {
        $mallaId = $_GET['id'];   
        $model = ScholarisMecV2MallaArea::find()->where(['malla_id' => $mallaId])->orderBy('orden')->all();
        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($mallaId);
        return $this->render('index', [
            'modelMalla' => $modelMalla,
            'model' => $model
        ]);
    }

    /**
     * Displays a single ScholarisMecV2MallaArea model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($model->malla_id);
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'modelMalla' => $modelMalla,
        ]);
    }

    /**
     * Creates a new ScholarisMecV2MallaArea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $mallaId = $_GET['mallaId'];
        
        $model = new ScholarisMecV2MallaArea();
        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($mallaId);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $mallaId]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelMalla' => $modelMalla,
        ]);
    }

    /**
     * Updates an existing ScholarisMecV2MallaArea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($model->malla_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelMalla' => $modelMalla
        ]);
    }

    /**
     * Deletes an existing ScholarisMecV2MallaArea model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
        $model = $this->findModel($id);
        
        $this->findModel($id)->delete();
//
        return $this->redirect(['index1','id' => $model->malla_id]);
    }

    /**
     * Finds the ScholarisMecV2MallaArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisMecV2MallaArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisMecV2MallaArea::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
