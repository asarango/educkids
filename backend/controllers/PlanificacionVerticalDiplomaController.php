<?php

namespace backend\controllers;

use backend\models\ContenidoPaiHabilidades;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionOpciones;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PlanificacionVerticalDiplomaHabilidades;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use Exception;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

class PlanificacionVerticalDiplomaController extends Controller
{

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

    public function actionIndex1()
    {
        $planUnidadId = $_GET['unidad_id'];
        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);        
        
        $planVerticalDipl=$this->select_plan_vertical_diploma($planUnidadId);
        $planVertDiplContenido = $this->select_contenidos($planUnidadId);
        //trae las relaciones y las habilidades, segun planVerticalDipl
        $planVertDiplRelacionTDC=$this->select_relacionTDC($planVerticalDipl);
        $planVertDiplHabilidades=$this->select_habilidadesTDC($planVerticalDipl);
        
        return $this->render('index', [
            'planUnidad' => $planUnidad,
            'planVerticalDiploma' => $planVerticalDipl,
            'planVerticalDiplRelacionTDC' => $planVertDiplRelacionTDC,
            'planVerticalDiplHabilidades' => $planVertDiplHabilidades,
            'planVerticalDiplContenidos'=>$planVertDiplContenido

        ]);
    }
    public function actionUpdate($id) 
    { 
        //realiza la actualizacion de los campos simples de P.V.DIPLOMA      
        $planiVertDiplId = $id;
        $modelPlanifVerticalDipl = PlanificacionVerticalDiploma::find()->where([
            'id'=>$planiVertDiplId
        ])->one();   
        $modelPlanifVertDiplRelacionTDC = $this->consultar_tdc_ckeck($planiVertDiplId);       
        $modelPlanifVertDiplHabilidades = $this->select_habilidad_check($planiVertDiplId) ;
       
        if ($modelPlanifVerticalDipl->load(Yii::$app->request->post())) 
        {            
            
            $userLog = Yii::$app->user->identity->usuario;
            $fechaHoy = date('Y-m-d H:i:s');
            $modelPlanifVerticalDipl->updated =$userLog ;
            $modelPlanifVerticalDipl->updated_at =$fechaHoy ;
            $modelPlanifVerticalDipl->save();
        }       
        return $this->render('update', [
                   'modelPlanifVertDipl' => $modelPlanifVerticalDipl,
                   'modelPlanifVertDiplTDC' => $modelPlanifVertDiplRelacionTDC,
                   'modelPlanifVertDiplHab'=>$modelPlanifVertDiplHabilidades
        ]);
    }
    public function actionUpdateTdc() 
    { 
        //realiza la actualizacion de relaciones TDC       
        $userLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');
        $pvd_tdc_id = $_GET['pvd_tdc_id'];
        $plan_vertical_id=$_GET['plan_vertical_id'];
        $tdc_id=$_GET['tdc_id'];
        $accion = $_GET['accion'];
        
        if ($accion=='agregar'){            
            $model = new PlanificacionVerticalDiplomaRelacionTdc();
            $model->vertical_diploma_id = $plan_vertical_id;
            $model->relacion_tdc_id =  $tdc_id;
            $model->created =  $userLog;
            $model->created_at =  $fechaHoy;
            $model->save();
        }
        else
        {
            $model=  PlanificacionVerticalDiplomaRelacionTdc::findOne($pvd_tdc_id);
            $model->delete();
        }      
        return $this->redirect(['update','id'=>$plan_vertical_id]);   
        
    }
    public function actionUpdateHabilidad() 
    { 
        //realiza la actualizacion de habilidades TDC       
        $userLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');  
        $accion = $_GET['accion'];
        $plan_vertical_id=$_GET['plan_vertical_id'];
        
        if ($accion=='agregar'){
            $pvd_hab_id = $_GET['habilidad_id']; 
            $model = new PlanificacionVerticalDiplomaHabilidades();
            $model->vertical_diploma_id =$plan_vertical_id;
            $model->habilidad_id =$pvd_hab_id;
            $model->created =  $userLog;
            $model->created_at =  $fechaHoy;
            $model->save();
        }
        else
        {
            $hab_id = $_GET['id'];
            $model=  PlanificacionVerticalDiplomaHabilidades::findOne($hab_id);
            $model->delete();
        }      
        return $this->redirect(['update','id'=>$plan_vertical_id]);   
        
    }
    //Devuelve los check que estan activados, y los que no estan activados
    private function consultar_tdc_ckeck($planVertDiplId) 
    {
        //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados
       $con = Yii::$app->db;
       $query = "select p.categoria ,p.opcion,pr.id as pvd_tdc_id ,p.id as tdc_id, 
                case 
                when p.id is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                planificacion_vertical_diploma pvd 
                where p.tipo='RELACION_TDC'  and pvd.id =$planVertDiplId 
                and pr.vertical_diploma_id = pvd.id   
                and pr.relacion_tdc_id  = p.id
                union all 
                select p.categoria ,p.opcion,0 as pvd_tdc_id,p.id tdc_id, 
                case 
                when null is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p
                where p.tipo='RELACION_TDC'
                and p.id not in 
                ( select relacion_tdc_id  from planificacion_vertical_diploma_relacion_tdc 
                where vertical_diploma_id = $planVertDiplId)
                order by opcion;
                ";
        $resultado = $con->createCommand($query)->queryAll();
        return $resultado;
    }    
    private function select_habilidad_check($plaVertDiplId) 
    {    
        //consulta las habilidades que han sido marcados con check, mas las que aun no estan marcados    
        $arrayResp = array();
        $modelCPH = ContenidoPaiHabilidades::find()
       ->select('es_titulo2')
       ->groupBy('es_titulo2')->asArray()->all();

       foreach($modelCPH as $model)
       {
            $esTitulo=$model['es_titulo2'] ;
            $con = Yii::$app->db;
            $query = "select h.es_titulo2 ,h.es_exploracion ,ph.id as pvd_tdc_id ,h.id as hab_id, 
                        case 
                        when h.id is not null then true else false
                        end as es_seleccionado
                        from contenido_pai_habilidades h, planificacion_vertical_diploma_habilidades ph,
                        planificacion_vertical_diploma pvd 
                        where pvd.id =$plaVertDiplId and h.es_titulo2 ='$esTitulo'
                        and ph.vertical_diploma_id = pvd.id   
                        and ph.habilidad_id  = h.id
                        union all
                        select h.es_titulo2 ,h.es_exploracion ,0 as pvd_tdc_id ,h.id as hab_id,
                        case 
                        when null is not null then true else false
                        end as es_seleccionado
                        from contenido_pai_habilidades h
                        where h.id not in (select habilidad_id  from planificacion_vertical_diploma_habilidades where vertical_diploma_id = 3)
                        and h.es_titulo2 ='$esTitulo'
                        order by es_titulo2;
                        ";                          
            $resultado = $con->createCommand($query)->queryAll();        
            $model['subhabilidades'] = $resultado ;            
            array_push($arrayResp,$model);            
       }
       return $arrayResp;       
    }
    private function select_plan_vertical_diploma($planUnidadId)
    {
        //muestra el plan vert diploma, segun el codigo unidad seleccionado
        $planVerticalDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId
        ])->one();
        if (!$planVerticalDipl) {
            $resp = $this->inserta_plan_vertical_diploma_vacios($planUnidadId);
        }else{
            $resp = $planVerticalDipl;
        }
        return  $resp;
    }
    
    private function inserta_plan_vertical_diploma_vacios($planUnidadId)
    {
        $userLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');
        $planVertical = new PlanificacionVerticalDiploma();
        $planVertical->planificacion_bloque_unidad_id = $planUnidadId;
        $planVertical->objetivo_asignatura = "Sin contenido";
        $planVertical->concepto_clave = "Sin contenido";
        $planVertical->objetivo_evaluacion = "Sin contenido";
        $planVertical->intrumentos = "Sin contenido";
        $planVertical->created = $userLog;
        $planVertical->created_at = $fechaHoy;
        $planVertical->save();

        return $planVertical;
    }
  
    private function select_relacionTDC($planVerticalDipl)
    {
        //muestra todas las relacion tdc, segun el codigo del plan vertical diploma, que este asociada
        $idPlanVertDipl = $planVerticalDipl->id;
        $relacionesTDC=array();
        $planVertDip_Relacion = PlanificacionVerticalDiplomaRelacionTdc::find()->where([
            'vertical_diploma_id' => $idPlanVertDipl
        ])->all(); 
        
        return $planVertDip_Relacion;
    }

    private function select_habilidadesTDC($planVerticalDipl)
    {
        //muestra todas las habilidades, segun el codigo del plan vertical diploma, que este asociada
        $idPlanVertDipl = $planVerticalDipl->id;     
        $con = Yii::$app->db;
        $query ="select distinct cph.es_titulo2 
                from contenido_pai_habilidades cph , planificacion_vertical_diploma_habilidades pvdh 
                where cph.id = pvdh .habilidad_id  
                and pvdh.vertical_diploma_id = $idPlanVertDipl;"; 

        $resultado = $con->createCommand($query)->queryAll();        
        return $resultado;
    } 

    private function select_contenidos($planUnidadId)
    {        
        $arrayResp = array();
        $contenido = PlanificacionBloquesUnidadSubtitulo::find()->where([
            'plan_unidad_id'=>$planUnidadId
        ])->asArray()->all();       

        foreach ($contenido as $cont) {             
            $contenidosSubnivel = PlanificacionBloquesUnidadSubtitulo2::find()->where([
                'subtitulo_id'=>$cont['id']
            ])->asArray()->all();
            $cont['subtitulos']=array();

            foreach ($contenidosSubnivel as $contSub) { 
                array_push( $cont['subtitulos'],$contSub);                 
            }
            array_push($arrayResp,$cont);
        }             
        return  $arrayResp;
    }  

}
