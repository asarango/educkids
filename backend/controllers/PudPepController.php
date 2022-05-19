<?php
namespace backend\controllers;

//use backend\models\pca\DatosInformativos as PcaDatosInformativos;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PudPep;
use backend\models\pudpep\CriteriosEvaluacion;
use backend\models\pudpep\DatosInformativos;
use backend\models\pudpep\Indicadores;
use backend\models\pudpep\Pdf;
use backend\models\pudpep\PlanUnidad;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class PudPepController extends Controller{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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

    public function beforeAction($action)
    {
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

    public function actionIndex1(){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $planBloqueInidadId = $_GET['plan_bloque_unidad_id'];

        $planUnidad = PlanificacionBloquesUnidad::findOne($planBloqueInidadId);
        $templateId = $planUnidad->planCabecera->op_course_template_id;

        $seccion = $this->consultar_seccion($templateId, $periodoId, $institutoId);
        
        $pudPep = PudPep::find()->where([
             'planificacion_bloque_unidad_id' => $planBloqueInidadId
         ])
         ->orderBy('id')
         ->all();

        return $this->render('index1',[
            'planUnidad' => $planUnidad,
            'pudPep' => $pudPep,
            'seccion' => $seccion
        ]);

    }

    private function consultar_seccion($templateId, $periodoId, $institutoId){
        $con = Yii::$app->db;
        $query = "select 	s.code 
        from 	op_course c
                inner join op_period op on op.id = c.period_id 
                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = c.period_id 
                inner join op_section s on s.id = c.section
        where 	c.x_template_id = $templateId
                and sop.scholaris_id = $periodoId
                and c.x_institute = $institutoId";
        $res = $con->createCommand($query)->queryOne();
        return $res['code'];
    }

    public function actionAjaxPud(){

        $planUnidadId = $_GET['plan_unidad_id'];
        $contenedor = $_GET['contenedor'];  

        if($contenedor == 'datos_informativos'){
            $data = new DatosInformativos($planUnidadId);
            $data->generate_information();
            $information = $data->html;            
        }elseif($contenedor == 'criterios_evaluacion'){

            $datace = new CriteriosEvaluacion($planUnidadId);
            $datace->generate_information();
            $information = $datace->html;
            
        }elseif($contenedor == 'indicadores'){
            $dataI = new Indicadores($planUnidadId);
            $dataI->generate_information();
            $information = $dataI->html;
        }

        return $information;
    }


    public function actionAjaxInsertarCriterio(){

        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date("Y-m-d H:i:s");
        
        $planBloqueUnidadId = $_POST['planificacion_bloque_unidad_id'];
        $tipo       = $_POST['tipo'];
        $codigo     = $_POST['codigo'];
        $contenido  = $_POST['contenido'];

        $model = new PudPep();
        $model->planificacion_bloque_unidad_id = $planBloqueUnidadId;
        $model->tipo = $tipo;
        $model->codigo = $codigo;
        $model->contenido = $contenido;
        $model->created_at = $fechaHoy;
        $model->created = $usuarioLog;

        $model->save();
    }

    public function actionGeneraPdf(){
        $planBloqueUnidadId = $_GET['planificacion_unidad_bloque_id'];
        $pdf = new Pdf($planBloqueUnidadId);
    }
    
    
    public function actionAjaxInsertarContenido(){
                
        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date("Y-m-d H:i:s");
        
        $planBloqueUnidadId = $_POST['planificacion_bloque_unidad_id'];
        $tipo       = $_POST['tipo'];
        $codigo     = $_POST['codigo'];
        $contenido  = $_POST['contenido'];
        $pertIndic  = $_POST['pertenece_indicador_id'];

        $model = new PudPep();
      
        $model->planificacion_bloque_unidad_id = $planBloqueUnidadId;
        $model->tipo = $tipo;
        $model->codigo = $codigo;
        $model->contenido = $contenido;
        $model->pertenece_indicador_id = $pertIndic;
        $model->created_at = $fechaHoy;
        $model->created = $usuarioLog;

        $model->save();
    }

    public function actionAjaxDeleteOption(){
        $id = $_GET['id'];
        $model = PudPep::findOne($id);
        $model->delete();        
    }
    
    public function actionAjaxInsertarDestreza(){
        
        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date("Y-m-d H:i:s");
        
        $planUnidadId = $_POST['planificacion_bloque_unidad_id'];
        $tipo = $_POST['tipo'];
        $codigo = $_POST['codigo'];
        $contenido = $_POST['contenido'];
        $indicadorId = $_POST['pertenece_indicador_id'];
        
        $model = new PudPep();
        
        $model->planificacion_bloque_unidad_id = $planUnidadId;
        $model->tipo = $tipo;
        $model->codigo = $codigo;
        $model->contenido = $contenido;
        $model->pertenece_indicador_id = $indicadorId;
        $model->created_at = $fechaHoy;
        $model->created = $usuarioLog;
        
//        echo '<pre>';
//        print_r($model);
//        die();

        $model->save();
        
    }
    
//    Funcion que borra primero los ada-recursos-tecnicas-destrezas pertenecientes al IndicadorID y 
//        luego indicador
    public function actionAjaxDeleteIndicador(){
        $indicadorId = $_POST['indicador_id'];

        $con = Yii::$app->db;
        $sql1 ="delete from pud_pep where pertenece_indicador_id = $indicadorId;";
        $con->createCommand($sql1)->execute();
        
        $sql2 ="delete from pud_pep where id = $indicadorId;";
        $con->createCommand($sql2)->execute();
        
    }
    
    

}

?>