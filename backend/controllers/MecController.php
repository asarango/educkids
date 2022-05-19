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
class MecController extends Controller {
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
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        
        $modelTipoCalificacion = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where([
            'scholaris_periodo_id' => $periodoId
        ])->one();

        
        if(isset($modelTipoCalificacion->codigo)>=0){
            $tipoCalificacion = $modelTipoCalificacion->codigo;
        }else{
            
        }
        
       
        if($tipoCalificacion == 0){ 
            new \backend\models\ProcesaNotasNormales($paralelo, ''); //invoca clase de procesamiento de notas
        }elseif($tipoCalificacion == 2){
            
        }elseif($tipoCalificacion == 3){
            //new \backend\models\ProcesaNotasInterdisciplinar($paralelo, ''); //clase para interdisciplinar
        }
        else{
           echo '<h1>No se configuró tipos de calificación al periodo!</h1>';
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
        $reporte = $_GET['reporte'];
        
        $modelTipo = \backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'reportesmec'])->one();
        $tipoRec = $modelTipo->valor;
        
        
        switch ($reporte){
            case 'QUIMESTRAL':       
                if($tipoRec == 'ISM'){
                   new \backend\models\MecIsmQuimestre($paralelo); 
                }elseif($tipoRec == 'DIC'){                    
                    new \backend\models\MecDicQuimestre($paralelo);
                }else{
                    echo 'No tiene configurado el tipo de reporte en las opciones parámetros';
                }
                
                break;
            
            case 'FINAL':                
                new \backend\models\MecIsmFInal($paralelo);
                break;
            
            case 'REMEDIAL':                
                new \backend\models\MecIsmRemedial($paralelo);
                break;
                        
            case 'PROMOCION':        
                if($tipoRec == 'ISM'){
                   new \backend\models\MecIsmPromocion($paralelo);
                }elseif($tipoRec == 'DIC'){
                    
                   new \backend\models\MecDicPromocion($paralelo);
                }else{
                    echo 'No tiene configurado el tipo de reporte en las opciones parámetros';
                }
                
                break;
            
            case 'PROMOCION2':        
                if($tipoRec == 'DIC'){
                   new \backend\models\MecDicPromocion2($paralelo);                
                }else{
                    echo 'No tiene configurado el tipo de reporte en las opciones parámetros';
                }
                break;
            
            case 'PROMOCION3':        
                if($tipoRec == 'DIC'){
                   new \backend\models\MecDicPromocion3($paralelo);                
                }else{
                    echo 'No tiene configurado el tipo de reporte en las opciones parámetros';
                }
                break;
            
            case 'MATRICULADOS':                
                new \backend\models\MecIsmNominaMatriculados($paralelo);
                break;
            
            case 'MATRICULADOS2':                
                new \backend\models\MecIsmNominaMatriculados2($paralelo);
                break;
            
            case 'MATRICULADOS2EXCEL':                
                new \backend\models\MecIsmNominaMatriculados2Excel($paralelo);
                break;
            
            case 'RESUMENFINAL':                
                   $reporte = new \backend\models\InfLibretaResumenFinal($paralelo, '', 'q2');
//                   $reporte->genera_reporte($paralelo, '','q2');
                   break;
            
        }
        
        
    }
    
    
    
    
}
