<?php
namespace backend\controllers;

//use backend\models\pca\DatosInformativos as PcaDatosInformativos;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PudPai;
use backend\models\pudpai\Accion;
use backend\models\pudpai\Aspecto;
use backend\models\pudpai\Datos;
use backend\models\pudpai\Ensenara;
use backend\models\pudpai\Evaluacion;
use backend\models\pudpai\GrupoHabilidades;
use backend\models\pudpai\Indagacion;
use backend\models\pudpai\Indicadores;
use backend\models\pudpai\Pdf;
use backend\models\pudpai\PerfilesBi;
use backend\models\pudpai\Preguntas;
use backend\models\pudpai\Recursos;
use backend\models\pudpai\Reflexion;
use backend\models\pudpai\ServicioAccion;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class PudPaiController extends Controller{

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
        $planBloqueUnidadId = $_GET['plan_bloque_unidad_id'];

        $planUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);

        return $this->render('index', [
            'planUnidad' => $planUnidad
        ]);
    }  


    public function actionPestana(){
        $planUnidadId   = $_GET['plan_unidad_id'];
        $pestana        = $_GET['pestana'];
        
        if($pestana == 'datos'){
            $datos = new Datos($planUnidadId);
            return $datos->html;
        }elseif($pestana == 'conceptos'){
            $indagacion = new Indagacion($planUnidadId);
            return $indagacion->html;
        }elseif($pestana == 'preguntas'){
            $preguntas = new Preguntas($planUnidadId);
            return $preguntas->html;
        }elseif($pestana == 'evaluacion'){
            $evaluacion = new Evaluacion($planUnidadId);
            $this->ingresa_eval_sumativa($planUnidadId);
            $this->ingresa_sumativa2($planUnidadId);
            return $evaluacion->html;
        }elseif($pestana == 'grupo_habilidades'){
            $grupos = new GrupoHabilidades($planUnidadId);
            return $grupos->html;
        }elseif($pestana == 'aspecto_objetivo'){
            $aspectos = new Aspecto($planUnidadId);
            return $aspectos->html;
        }elseif($pestana == 'inidicador_habilidad'){
            $indicadores = new Indicadores($planUnidadId);
            return $indicadores->html;
        }elseif($pestana == 'como_se_ensenara'){
            $ensenara = new Ensenara($planUnidadId);
            return $ensenara->html;
        }elseif($pestana == 'perfil_bi'){
            $perfiles = new PerfilesBi($planUnidadId);
            return $perfiles->html;
        }elseif($pestana == 'accion'){
            $accion = new Accion($planUnidadId);
            return $accion->html; 
        }elseif($pestana == 'servicio_accion'){
            $servicio = new ServicioAccion($planUnidadId);
            return $servicio->html; 
        }elseif($pestana == 'recursos'){
            $recursos = new Recursos($planUnidadId);
            return $recursos->html; 
        }elseif($pestana == 'reflexion'){
            $reflexion = new Reflexion($planUnidadId);
            return $reflexion->html;
        }
    }



    private function ingresa_eval_sumativa($planUnidadId){
        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy   = date('Y-m-d H:i:s');

        $con = Yii::$app->db;
        
        $querySumativas = "insert into pud_pai (planificacion_bloque_unidad_id, seccion_numero, tipo, criterio_id, contenido, created_at, created, updated_at, updated) 
                            select 	$planUnidadId, 3, 'eval_sumativa', c.id, 'sin contenido', '$fechaHoy', '$usuarioLog', '$fechaHoy', '$usuarioLog' 
                            from 	planificacion_vertical_pai_descriptores v
                                            inner join ism_criterio_descriptor_area maes on maes.id = v.descriptor_id
                                            inner join ism_criterio c on c.id = maes.id_criterio
                            where 	v.plan_unidad_id = $planUnidadId
                                            and c.id not in(
                                                    select criterio_id from pud_pai 
                                                    where planificacion_bloque_unidad_id = $planUnidadId and tipo in ('eval_sumativa')
                                            )
                            group by c.id, c.nombre 
                            order by c.id;";
        
        $con->createCommand($querySumativas)->execute();

       
    }

    private function ingresa_sumativa2($planUnidadId){

        $usuarioLog = Yii::$app->user->identity->usuario;
        $fechaHoy   = date('Y-m-d H:i:s');

        $pudPai = PudPai::find()->where([
            'tipo' => 'relacion-suma-eval',
            'planificacion_bloque_unidad_id' => $planUnidadId
        ])->one();

        if(!$pudPai){
            $model = new PudPai();
            $model->planificacion_bloque_unidad_id = $planUnidadId;
            $model->seccion_numero = 3;
            $model->tipo = 'relacion-suma-eval';
            $model->contenido = 'sin contenido';
            $model->created = $usuarioLog;
            $model->created_at = $fechaHoy;
            $model->updated = $usuarioLog;
            $model->updated_at = $fechaHoy;
            $model->save();
       }
    }

    public function actionGeneraPdf(){
        $planUnidadId = $_GET['planificacion_unidad_bloque_id'];
        $pdf = new Pdf($planUnidadId);
        
    }
    

}

?>