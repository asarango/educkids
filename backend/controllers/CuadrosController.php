<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use kartik\mpdf\Pdf;
use Mpdf\Mpdf;
//use backend\models\SentenciasSql;
use frontend\models\SentenciasSql;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class CuadrosController extends Controller {
    /**
     * {@inheritdoc}
     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ]
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }
//
//    public function beforeAction($action) {
//        if (!parent::beforeAction($action)) {
//            return false;
//        }
//
//        if (Yii::$app->user->identity) {
//
//            //OBTENGO LA OPERACION ACTUAL
//            list($controlador, $action) = explode("/", Yii::$app->controller->route);
//            $operacion_actual = $controlador . "-" . $action;
//            //SI NO TIENE PERMISO EL USUARIO CON LA OPERACION ACTUAL
//            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
//                echo $this->render('/site/error', [
//                    'message' => "Acceso denegado. No puede ingresar a este sitio !!!",
//                    'name' => 'Acceso denegado!!',
//                ]);
//            }
//        } else {
//            header("Location:" . \yii\helpers\Url::to(['site/login']));
//            exit();
//        }
//        return true;
//    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {

        $periodoId = \Yii::$app->user->identity->periodo_id;
        $institutoId = \Yii::$app->user->identity->instituto_defecto;
        
        $modelPeriodo = \backend\models\ScholarisPeriodo::find()
                ->innerJoin("scholaris_op_period_periodo_scholaris sop", "sop.scholaris_id = scholaris_periodo.id")
                ->where(['scholaris_periodo.id' => $periodoId])
                ->one();
        $periodo = $modelPeriodo->codigo;

        $searchModel = new \backend\models\OpCourseParaleloSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $periodoId, $institutoId);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modelPeriodo' => $modelPeriodo,
                    'institutoId' => $institutoId
        ]);
        
        
    }
    
    public function actionInformes(){
        
        
        $paralelo = $_GET['id'];
        
        $modelTipoCalificacion = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'tipocalif'])->one();
        $tipoCalificacion = $modelTipoCalificacion->valor;
        
        if($tipoCalificacion == 0){
            echo 'llamar a libretas_clase';
        }elseif($tipoCalificacion == 1){
            new \backend\models\ProcesaNotasMateriasInterdisciplinar($paralelo);
        }elseif($tipoCalificacion == 2){
            echo 'llamar a dscipinar';
        }
        
    }
}
