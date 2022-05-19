<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class RevisionesPudCoordinadorController extends Controller {

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
    public function actionIndex1() {
        
        $sentencias = new \backend\models\SentenciasPud();
        
        $usuario = Yii::$app->user->identity->usuario;
        $periodo = Yii::$app->user->identity->periodo_id;
        $institu = Yii::$app->user->identity->instituto_defecto;
        
        $modelo = $sentencias->get_revisonC($usuario);
        
       
        return $this->render('index',[
            'modelo' => $modelo
        ]);
    }
    
    public function actionCorreciones(){
        $sentencias = new \backend\models\SentenciasPud();
        $pudId = $_GET['pudId'];
        
        $modelPud = \backend\models\ScholarisPlanPud::findOne($pudId);
        $modelPudDetalle = \backend\models\ScholarisPlanPudDetalle::find()
                ->where(['pud_id' => $pudId])
                ->orderBy('tipo')
                ->all();
        $modelReporte = \backend\models\ScholarisParametrosOpciones::find()
        ->where(['codigo' => 'repopud'])
        ->one();
        
        $model = new \backend\models\ScholarisPlanPudCorrecciones();
        
        if ($model->load(Yii::$app->request->post())) {
            
            $sentencias->cambia_estado($pudId, 'RECHAZADO');
            
            $model->save();
            return $this->redirect(['index1']);
        }
        
        return $this->render('correcciones',[
            'modelPud' => $modelPud,
            'modelPudDetalle' => $modelPudDetalle,
            'model' => $model,
            'modelReporte' => $modelReporte
        ]);
        
    }
    
    public function actionEnviarvicerrector(){
        $sentencias = new \backend\models\SentenciasPud();
        
        $pudId = $_GET['pudId'];
        
        $sentencias->cambia_estado($pudId, 'REVISIONV');
        
        return $this->redirect(['index1']);
    }

    
}
