<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\filters\AccessControl;


/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class ReporteFaltasAnualController extends Controller {
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
    public function actionIndex1() {
        
        $paralelo = $_GET['id'];
        
        $sentencias = new \backend\models\SentenciasAlumnos();

        $modelAlumnos = $sentencias->get_alumnos_paralelo($paralelo);
        
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        
//
        return $this->render('index', [
                    'modelAlumnos' => $modelAlumnos,
                    'modelParalelo' => $modelParalelo
        ]);
    }
    

}
