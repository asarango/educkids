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
class PlanificacionesCoordinadorController extends Controller {

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
            if (!Yii::$app->user->identity->tienePermiso($operacion_actual)) {
                echo $this->render('/site/error', [
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
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex1() {
        $sentencias = new \backend\models\SentenciasBloque();
        $paralelo = $_GET['id'];
        
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        
        $modelSemanas = $sentencias->recupera_semanas_paralelo($paralelo);
        
        if(isset($_GET['semanaId'])){
            $semanaId = $_GET['semanaId'];
        }else{
            $semanaId = $modelSemanas[0]['id'];
        }
        
        $modelSemana = \backend\models\ScholarisBloqueSemanas::findOne($semanaId);
        
        $modelHorario = $this->toma_horario($semanaId, $paralelo);        
        
        return $this->render('index',[
            'modelParalelo' => $modelParalelo,
            'modelSemanas' => $modelSemanas,
            'modelSemana' => $modelSemana,
            'modelHorario' => $modelHorario
        ]);
        
    }
    
    private function toma_horario($semanaId, $paralelo){
        $modelClase = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $horario = $modelClase->asignado_horario;
        
        $con = Yii::$app->db;
        $query = "select 	d.id as detalle_id
		,dia.nombre 
		,ho.sigla 
from 	scholaris_horariov2_detalle d
		inner join scholaris_horariov2_dia dia on dia.id = d.dia_id
		inner join scholaris_horariov2_hora ho on ho.id = d.hora_id 
where 	d.cabecera_id = $horario
order by dia.numero, ho.numero;";
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }  
    

}
