<?php

namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;



class EstadisticasCriteriosPaiController extends Controller{

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
     * Lists all ScholarisClase models.
     * @return mixed
     */

     public function actionIndex(){        
        
        $modelDatos = $this->get_data_total();
        $formativas = urldecode(serialize($modelDatos['formativas']));
        $sumativas = urldecode(serialize($modelDatos['sumativas']));
        
        $serializado = array(
            'formativas' => $formativas,
            'sumativas'  => $sumativas
        );        

        return $this->render('index',[
            'serializado' => $serializado,
            'data' => $modelDatos['todas'] 
        ]);
     }


     private function get_data_total(){
        $periodoId = Yii::$app->user->identity->periodo_id;  
        
        $con = Yii::$app->db;
        $query = "select 	criterio 
                            ,tipo_actividad 
                            ,sum(total) as total
                    from 	dw_estadisticas_criterios_pai
                    where 	scholaris_periodo_id=$periodoId
                    group by criterio, tipo_actividad 
                    order by criterio, tipo_actividad ;";
        $res = $con->createCommand($query)->queryAll();

        $ySumativas = array();
        $yFormativas = array();

        foreach($res as $dat){
            if($dat['tipo_actividad'] == 'FORMATIVA'){
                array_push($yFormativas, $dat['total']);
            }else{
                array_push($ySumativas, $dat['total']);
            }
            
        }

        return array(
            'formativas'    => $yFormativas,
            'sumativas'     => $ySumativas,
            'todas'         => $res
        );
     }


     public function actionTablaDinamica(){
         return $this->render('tabla-dinamica');
     }
}


?>