<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMallaCurso;
use backend\models\ScholarisMallaCursoSearch;
use backend\models\ScholarisMalla;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisMallaCursoController implements the CRUD actions for ScholarisMallaCurso model.
 */
class ScholarisMallaCursoController extends Controller
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
     * Lists all ScholarisMallaCurso models.
     * @return mixed
     */
    public function actionIndex1($id)
    {
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        
        $modelMalla = ScholarisMalla::find()
                ->where(['periodo_id' => $periodoId, 'id' => $id])
                ->one();
         
        
        $searchModel = new ScholarisMallaCursoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelMalla' => $modelMalla,       
            'id' => $id,       
            
        ]);
    }

    /**
     * Displays a single ScholarisMallaCurso model.
     * @param integer $malla_id
     * @param integer $curso_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($malla_id, $curso_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($malla_id, $curso_id),
        ]);
    }

    /**
     * Creates a new ScholarisMallaCurso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($malla)
    {
        
        echo $malla;
        
        $modelMalla = ScholarisMalla::find()->where(['id' => $malla])->one();
        
        $model = new ScholarisMallaCurso();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $malla]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelMalla' => $modelMalla
        ]);
    }

    /**
     * Updates an existing ScholarisMallaCurso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $malla_id
     * @param integer $curso_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($malla_id, $curso_id)
    {
        $model = $this->findModel($malla_id, $curso_id);
        
        $modelMalla = ScholarisMalla::find()->where(['id' => $model->malla_id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'malla_id' => $model->malla_id, 'curso_id' => $model->curso_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelMalla' => $modelMalla
        ]);
    }

    /**
     * Deletes an existing ScholarisMallaCurso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $malla_id
     * @param integer $curso_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($malla_id, $curso_id)
    {
        $this->findModel($malla_id, $curso_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisMallaCurso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $malla_id
     * @param integer $curso_id
     * @return ScholarisMallaCurso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($malla_id, $curso_id)
    {
        if (($model = ScholarisMallaCurso::findOne(['malla_id' => $malla_id, 'curso_id' => $curso_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
