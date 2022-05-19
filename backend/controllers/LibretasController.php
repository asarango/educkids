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
class LibretasController extends Controller {
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
    

    
    public function actionInformes2(){
        $paralelo = $_GET['id'];
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        
        
        $modelTipoCalificacion = \backend\models\ScholarisParametrosOpciones::find()->where([
            'codigo' => 'tipocalif'
        ])->one();
        $tipoCalificacion = $modelTipoCalificacion->valor;
       
        if($tipoCalificacion == 0){
            
                    new \backend\models\ProcesaNotasNormales($paralelo, ''); //invoca clase de procesamiento de notas
        }
        
        return $this->render('informes2', [
            'modelParalelo' => $modelParalelo
        ]);        
        
    }
    
   
    
    public function actionInformedireccion2(){
        /*  REPORTES PARA PERSONALIZACION SANTO QUITO
         * 
         * q1inicial        ==> Informe Quimestral de inicial y Primero
         * 
         */
        
      
        $paralelo = $_GET['paralelo'];
        $quimestre = $_GET['quimestre'];
        $reporte = $_GET['reporte'];
        
        
        switch ($reporte){
            case 'LIBRETAQ1':
                
                $reporte = new \backend\models\InfLibretaPdfQ1N($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfLibretaPdfQ1N($paralelo, 1498, $quimestre);
                break;
            
            case 'LIBRETAQ1V1':
                $reporte = new \backend\models\InfLibretaPdfQ2V1N($paralelo, $alumno='', $quimestre);
                ///$reporte = new \backend\models\InfLibretaPdfQ2V1($paralelo, 17211, $quimestre);
                break;
            
            case 'LIBRETAQ1ISM':
                $reporte = new \backend\models\InfLibretaPdfQ1IsmN($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfLibretaPdfQ1Ism($paralelo, 3922, $quimestre);//ism 8vo A
                break;
            
            case 'SABANAQ1';
                $reporte = new \backend\models\InfSabanaQ1($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfSabanaQ1($paralelo, 15212, $quimestre);
                
                break;
            
            case 'SABANAPDFQ1';
//                $reporte = new \backend\models\InfSabanaPdfQ1($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfSabanaPdfQ1($paralelo, 17211, $quimestre);
                
                break;
            
            case 'SABANAPDFPARCIALES';
                $reporte = new \backend\models\InfSabanaPdfParciales($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfSabanaPdfParciales($paralelo, 5118, $quimestre);
                
                break;
            
            case 'PRUEBA';
                $reporte = new \backend\models\InfSabanaPdfQ1P($paralelo,$alumno='', $quimestre);
//                $reporte = new \backend\models\InfSabanaPdfQ1P($paralelo, 17211, $quimestre);
                break;
        }
        
        
    }
    
    
    
    
}
