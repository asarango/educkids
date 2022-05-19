<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class ProcesoAcademicoController extends Controller
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
     * Lists all PlanPlanificacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $usuario = Yii::$app->user->identity->usuario;
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
        $institutoId = Yii::$app->user->identity->instituto_defecto;

               
        $searchModel = new \backend\models\OpCourseParaleloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$periodoId,$institutoId);
        $modelPeriodoOdoo = $searchModel->toma_periodo_odoo($institutoId, $modelPeriodo->id);
        
        $modelCursos = \backend\models\OpCourse::find()
                ->innerJoin("op_section", "op_section.id = op_course.section")
                ->where(['op_section.period_id' => $modelPeriodoOdoo['id']])
                ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelCursos' => $modelCursos,
        ]);
        
        
//        return $this->render('index', [
//            'periodo' => $periodoId,
//        ]);
    }
    
    
    public function actionOpciones()
    {
        
        $paralelo = $_GET['id'];
        
        $modelParalelo = \backend\models\OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();

        return $this->render('opciones', [
            'modelParalelo' => $modelParalelo,
        ]);
    }

    
}
