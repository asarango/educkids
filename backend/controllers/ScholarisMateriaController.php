<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMateria;
use backend\models\ScholarisMateriaSearch;
use backend\models\ScholarisPeriodo;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisMateriaController implements the CRUD actions for ScholarisMateria model.
 */
class ScholarisMateriaController extends Controller
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
     * Lists all ScholarisMateria models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        // $periodoId = \Yii::$app->user->identity->periodo_id;
        // $modelPeriodo = ScholarisPeriodo::find()
        //         ->where(['id' => $periodoId])
        //         ->one();           
        
        // $modelUltimoPeriodo = \backend\models\ScholarisArea::find()
        //         ->orderBy(['period_id' => SORT_DESC])
        //         ->one();
        // $ultimoPeriodo = $modelUltimoPeriodo->period_id;
        
        // $searchModel = new ScholarisMateriaSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $modelPeriodo->codigo, $ultimoPeriodo);

        $materias  = $this->get_asignaturas();
        return $this->render('index', [
            // 'searchModel' => $searchModel,
            // 'dataProvider' => $dataProvider,
            // 'periodo' => $modelPeriodo->codigo
            'materias' => $materias
        ]);
    }

    private function get_asignaturas(){
        $con = Yii::$app->db;
        $query = "select 	m.id, m.name as materia 
                            ,m.last_name, m.abreviarura, c.name as asignatura_curriculo, m.is_active
                            ,a.name as area
                    from 	scholaris_materia m
                        inner join scholaris_area a on a.id = m.area_id 
                        left join curriculo_mec_asignatutas c on c.id = m.curriculo_asignatura_id 
                    where	m.is_active = true
                    order by m.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;

    }

    /**
     * Displays a single ScholarisMateria model.
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
     * Creates a new ScholarisMateria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScholarisMateria();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        
        $modelUltimoPeriodo = \backend\models\ScholarisArea::find()
                ->orderBy(['period_id' => SORT_DESC])
                ->one();
        $ultimoPeriodo = $modelUltimoPeriodo->period_id;

        return $this->render('create', [
            'model' => $model,
            'ultimoPeriodo' => $ultimoPeriodo
        ]);
    }

    /**
     * Updates an existing ScholarisMateria model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $modelUltimoPeriodo = \backend\models\ScholarisArea::find()
                ->orderBy(['period_id' => SORT_DESC])
                ->one();
        $ultimoPeriodo = $modelUltimoPeriodo->period_id;


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'ultimoPeriodo' => $ultimoPeriodo
        ]);
    }

    /**
     * Deletes an existing ScholarisMateria model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScholarisMateria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisMateria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisMateria::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
}
