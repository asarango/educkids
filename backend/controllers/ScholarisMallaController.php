<?php

namespace backend\controllers;

use Yii;
use backend\models\ScholarisMalla;
use backend\models\ScholarisMallaSearch;
use backend\models\ScholarisPeriodo;

use backend\models\OpSection;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScholarisMallaController implements the CRUD actions for ScholarisMalla model.
 */
class ScholarisMallaController extends Controller
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
     * Lists all ScholarisMalla models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $periodoId = \Yii::$app->user->identity->periodo_id;        
        
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
        
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        
        $modelSection = OpSection::find()
                ->select(["op_section.id", "concat(op_section.name,' ',op_section.code) as name"])
                ->innerJoin("scholaris_op_period_periodo_scholaris","scholaris_op_period_periodo_scholaris.op_id = op_section.period_id")
                ->innerJoin("scholaris_periodo", "scholaris_periodo.id = scholaris_op_period_periodo_scholaris.scholaris_id")
                ->innerJoin("op_period", "op_period.id = scholaris_op_period_periodo_scholaris.op_id")
                ->where(["scholaris_periodo.id" => $modelPeriodo->id, "op_period.institute" => $institutoId])
                ->all();
        
        $searchModel = new ScholarisMallaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$periodoId, $institutoId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelPeriodo' => $modelPeriodo,
            'modelSection' => $modelSection
        ]);
    }

    /**
     * Displays a single ScholarisMalla model.
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
     * Creates a new ScholarisMalla model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
        
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        
        $modelSection = OpSection::find()
                ->select(["op_section.id", "concat(op_section.name,' ',op_section.code) as name"])
                ->innerJoin("scholaris_op_period_periodo_scholaris","scholaris_op_period_periodo_scholaris.op_id = op_section.period_id")
                ->innerJoin("scholaris_periodo", "scholaris_periodo.id = scholaris_op_period_periodo_scholaris.scholaris_id")
                ->innerJoin("op_period", "op_period.id = scholaris_op_period_periodo_scholaris.op_id")
                ->where(["scholaris_periodo.id" => $modelPeriodo->id, "op_period.institute" => $institutoId])
                ->all();
        
        $model = new ScholarisMalla();
        
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelPeriodo' => $modelPeriodo,
            'modelSection' => $modelSection
        ]);
    }

    /**
     * Updates an existing ScholarisMalla model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        
        $modelSection = OpSection::find()
                ->select(["op_section.id", "concat(op_section.name,' ',op_section.code) as name"])
                ->innerJoin("scholaris_op_period_periodo_scholaris","scholaris_op_period_periodo_scholaris.op_id = op_section.period_id")
                ->innerJoin("scholaris_periodo", "scholaris_periodo.id = scholaris_op_period_periodo_scholaris.scholaris_id")
                ->innerJoin("op_period", "op_period.id = scholaris_op_period_periodo_scholaris.op_id")
                ->where(["scholaris_periodo.id" => $modelPeriodo->id, "op_period.institute" => $institutoId])
                ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelPeriodo' => $modelPeriodo,
            'modelSection' => $modelSection
        ]);
    }

    /**
     * Deletes an existing ScholarisMalla model.
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
     * Finds the ScholarisMalla model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScholarisMalla the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScholarisMalla::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
