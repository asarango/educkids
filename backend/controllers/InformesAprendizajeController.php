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
class InformesAprendizajeController extends Controller {
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

        if (!isset(Yii::$app->user->identity->usuario)) {
            echo 'Su sesión expiró!!!';
            echo \yii\helpers\Html::a("Iniciar Sesión", ['site/index']);
            die();
        }
        
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
        $sentencias = new \backend\models\SentenciasRecalcularUltima();
        
        $paralelo = $_GET['id'];
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        
        $sentencias->por_paralelo($paralelo);
        
        return $this->render('informes', [
            'modelParalelo' => $modelParalelo
        ]);
        
        
    }
    
    public function actionInformes2(){
        
        if (!isset(Yii::$app->user->identity->usuario)) {
            echo 'Su sesión expiró!!!';
            echo \yii\helpers\Html::a("Iniciar Sesión", ['site/index']);
            die();
        }
        
        
        $paralelo = $_GET['id'];
        $modelParalelo = \backend\models\OpCourseParalelo::findOne($paralelo);
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        
        $modelClase = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $modelParalelo->id])->one();
        $uso = $modelClase->tipo_usu_bloque;
        
        $modelBloques = \backend\models\ScholarisBloqueActividad::find()->where([
            'scholaris_periodo_codigo' => "$modelPeriodo->codigo",
            'tipo_uso' => "$uso",
            'tipo_bloque' => 'PARCIAL'
        ])->all();       
        
        
        $tipoCalificacion = \backend\models\ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $periodoId])->one();
        
        if($tipoCalificacion->codigo == 0){ 
            new \backend\models\ProcesaNotasNormales($paralelo, ''); //invoca clase de procesamiento de notas
        }elseif($tipoCalificacion->codigo == 2){
            
        }elseif($tipoCalificacion->codigo == 3){
            //new \backend\models\ProcesaNotasInterdisciplinar($paralelo, ''); //clase para interdisciplinar
        }
        else{
           echo '<h1>No se configuró tipos de calificación al periodo!</h1>';
        }
        
        
        
        
        return $this->render('informes2', [
            'modelParalelo' => $modelParalelo,
            'modelBloques' => $modelBloques
        ]);
        
        
    }
    
    
    public function actionInformedireccion(){
        /*  REPORTES PARA PERSONALIZACION SANTO QUITO
         * 
         * q1inicial        ==> Informe Quimestral de inicial y Primero
         * 
         */
        
        $paralelo = $_GET['paralelo'];
        $quimestre = $_GET['quimestre'];
        $reporte = $_GET['reporte'];
        
        
        switch ($reporte){
            case 'q1inicial':
                $reporte = new \backend\models\InformeAprendizajeIniciales();
                $reporte->genera_reporte($paralelo, $quimestre,'');
                break;
            
            case 'fininicial':
                $reporte = new \backend\models\InformeAprendizajeIniciales();
                $reporte->genera_reporte($paralelo, $quimestre);
                break;
            
            case 'q1aprendizaje':
                $reporte = new \backend\models\InformeQuimestral();
                $reporte->genera_reporte($paralelo, $quimestre);
                break;
            
            case 'libquimestral':
                $reporte = new \backend\models\InformeAprendizajeQuimestral();
                $reporte->genera_reporte($paralelo, $quimestre);
                break;
            
            case 'total':
               $reporte = new \backend\models\InformeAprendizajeTotal();
                $reporte->genera_reporte($paralelo,0);
                break;
            
            case 'total2':                
               $reporte = new \backend\models\InformeAprendizajeTotal2();
                $reporte->genera_reporte($paralelo,0);
                break;
            
            case 'libfinal':
                $reporte = new \backend\models\InformeAprendizajeFinal();
                $reporte->genera_reporte($paralelo);
                break;
            
            case 'sabanaquimestral':
                $reporte = new \backend\models\InformeSabanaQuimestral();
                $reporte->genera_reporte($paralelo, $quimestre);
                break;
            
            case 'sabanaquimestralexcel':
                $reporte = new \backend\models\InformeSabanaQuimestralExcel();
                $reporte->genera_reporte($paralelo, $quimestre);
                break;
            
        }
        
        
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
                $reporte = new \backend\models\InfLibretaPdfQ1($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfLibretaPdfQ1($paralelo, 1498, $quimestre);
                break;
            
            case 'LIBRETAQ1V1':
                $reporte = new \backend\models\InfLibretaPdfQ2V1($paralelo, $alumno='', $quimestre);
                ///$reporte = new \backend\models\InfLibretaPdfQ2V1($paralelo, 17211, $quimestre);
                break;
            
            case 'LIBRETAQ1ISM':
                //$reporte = new \backend\models\InfLibretaPdfQ1Ism($paralelo, $alumno='', $quimestre);
                $reporte = new \backend\models\InfLibretaPdfQ1IsmN($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfLibretaPdfQ1Ism($paralelo, 3922, $quimestre);//ism 8vo A
                break;
            
            case 'LIBRETAQ2ISM':
                //$reporte = new \backend\models\InfLibretaPdfQ1Ism($paralelo, $alumno='', $quimestre);
                $reporte = new \backend\models\InfLibretaPdfQ2IsmN($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfLibretaPdfQ1Ism($paralelo, 3922, $quimestre);//ism 8vo A
                break;
            
            case 'SABANAQ1EXCEL';
                $reporte = new \backend\models\InfSabanaExcel();
                $reporte->genera_reporte($paralelo, $quimestre);
                
//                $reporte = new \backend\models\InfSabanaQ1($paralelo, 15212, $quimestre);
                
                break;
            
            case 'SABANAPDFQ1';
//                $reporte = new \backend\models\InfSabanaPdfQ1($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfSabanaPdfQ1($paralelo, 17211, $quimestre);
                
                break;
            
            case 'SABANAPDFPARCIALES';
                
                $reporte = new \backend\models\InfSabanaPdfParciales($paralelo, $alumno='', $quimestre);
//                $reporte = new \backend\models\InfSabanaPdfParciales($paralelo, 5409, $quimestre);
                
                break;
            
            case 'PRUEBA';
                $reporte = new \backend\models\InfSabanaPdfQ1P($paralelo,$alumno='', $quimestre);
//                $reporte = new \backend\models\InfSabanaPdfQ1P($paralelo, 17211, $quimestre);
                break;
            
            case 'FINALCONSUPLETORIOS';
                $reporte = new \backend\models\InfSabanaFinalSuplePdfQ1P($paralelo,$alumno='', $quimestre);
//                $reporte = new \backend\models\InfSabanaPdfQ1P($paralelo, 17211, $quimestre);
                break;
            
            case 'PRUEBAEXCEL';
                $reporte = new \backend\models\InfSabanaExcelFinal($paralelo,$alumno='', $quimestre);
                break;
        }
        
        
    }
    
    
    
    public function actionInformegeneralpdf(){
        
        $paraleloId = $_GET['paraleloId'];
        $quimestre = $_GET['quimestre'];
        
        new \backend\models\InfGeneralPdf($paraleloId, '', $quimestre);
        
        
        
        
    }
    
    
    
}
