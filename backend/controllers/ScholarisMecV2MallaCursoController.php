<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMecV2MallaCurso;
use backend\models\ScholarisMecV2MallaCursoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisMecV2MallaCursoController implements the CRUD actions for ScholarisMecV2MallaCurso model.
 */
class ScholarisMecV2MallaCursoController extends Controller
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
     * Lists all ScholarisMecV2MallaCurso models.
     * @return mixed
     */
    public function actionIndex1()
    {
        
        $mallaId = $_GET['id'];
        
        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($mallaId);
        
        $searchModel = new ScholarisMecV2MallaCursoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $mallaId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelMalla' => $modelMalla
        ]);
    }

    /**
     * Displays a single ScholarisMecV2MallaCurso model.
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
     * Creates a new ScholarisMecV2MallaCurso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
                
        $mallaId = $_GET['mallaId'];
        $model = new ScholarisMecV2MallaCurso();
        $cursos = $this->get_cursos();
        
        $modelMalla = \backend\models\ScholarisMecV2Malla::findOne($mallaId);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index1', 'id' => $modelMalla->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelMalla' => $modelMalla,
            'cursos' => $cursos
        ]);
    }
    
    
    private function get_cursos(){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $sentencias = new \backend\models\SentenciasCursos();
        
        $cursos = $sentencias->get_cursos1($periodoId, $institutoId);
        
        return $cursos;
        
    }
    

    /**
     * Updates an existing ScholarisMecV2MallaCurso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $malla_id
     * @param integer $curso_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($malla_id, $curso_id)
    {
        $model = $this->findModel($malla_id, $curso_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'malla_id' => $model->malla_id, 'curso_id' => $model->curso_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScholarisMecV2MallaCurso model.
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
     * Finds the ScholarisMecV2MallaCurso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $malla_id
     * @param integer $curso_id
     * @return ScholarisMecV2MallaCurso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($malla_id, $curso_id)
    {
        if (($model = ScholarisMecV2MallaCurso::findOne(['malla_id' => $malla_id, 'curso_id' => $curso_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
