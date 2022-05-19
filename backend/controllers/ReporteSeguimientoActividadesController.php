<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * RolController implements the CRUD actions for Rol model.
 */
class ReporteSeguimientoActividadesController extends Controller
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
     * Lists all Rol models.
     * @return mixed
     */
    public function actionIndex()
    {
        $periodo = Yii::$app->user->identity->periodo_id;
        $instituto = Yii::$app->user->identity->instituto_defecto;
        
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodo);
        
        
        $sentencias = new \backend\models\SentenciasSeguimientoActividades();
        $cursos = $sentencias->get_cursos($instituto, $periodo);
        $modelBloques = $sentencias->get_bloques_agrupados($instituto, $modelPeriodo->codigo);
        
        
        return $this->render('index',[
            'cursos' => $cursos,
            'modelBloques' => $modelBloques
        ]);
        
    }
    
    public function actionAnalizar(){
        $sentencias = new \backend\models\SentenciasSeguimientoActividades();
        $curso = $_GET['curso'];
        $modelCurso = \backend\models\OpCourse::findOne($curso);
        
        if(isset($_GET['orden'])){
            $bloqueOrden = $_GET['orden'];
            
        }else{
            $bloqueOrden = 1;
        }
        $paralelos = $sentencias->get_paralelos($curso, $bloqueOrden);
        
        
        if(isset($_GET['paralelo'])){
            $para = $_GET['paralelo'];
            $modelParalelo = \backend\models\OpCourseParalelo::findOne($para);
        }else{
            $para = 0;
            $modelParalelo = '';
        }
        
        
        return $this->render('analizar',[
            'paralelos' => $paralelos,
            'modelCurso' => $modelCurso,
            'orden' => $bloqueOrden,
            'para' => $para,
            'modelParalelo' => $modelParalelo
        ]);
        
        
    }
    
    
    public function actionNotasprofesor(){
        $sentencias = new \backend\models\SentenciasBloque();
        $orden = $_GET['orden'];
        $clase = $_GET['clase'];
        $modelClase = \backend\models\ScholarisClase::findOne($clase);
                
        $bloqueId = $sentencias->recupera_bloque_por_orden($modelClase->tipo_usu_bloque, $orden);
        
        $seccion = $modelClase->paralelo->course->section0->code;
        
//        echo $clase.'<br>';
//        echo $bloqueId;
//        
//        die();
        
        
        
        if($seccion == 'PAI'){
                       
            $reporte = new \backend\models\InformeNotasProfesorPai();
            $reporte->parcial($bloqueId, $clase);
        }else{
            $reporte = new \backend\models\InformeNotasProfesorNac();
            $reporte->parcial($bloqueId, $clase);
        }
        
    }
    
}
