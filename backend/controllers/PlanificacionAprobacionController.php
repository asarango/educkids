<?php

namespace backend\controllers;

use backend\models\pca\Pca;
use backend\models\PlanificacionDesagregacionCabecera;
use backend\models\PlanificacionDesagregacionCriteriosDestreza;
use backend\models\PlanificacionVerticalPaiOpciones;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * PlanificacionDesagregacionCabeceraController implements the CRUD actions for PlanificacionDesagregacionCabecera model.
 */
class PlanificacionAprobacionController extends Controller{

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

    public function actionIndex(){

        $periodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;
        $section = 'PAI';

        $detalle = array();

        $cursos = $this->consulta_detalle_cursos($periodoId, $institutoId, $section);
        
        foreach($cursos as $curso){

            $curso['totales'] = $this->consulta_totales($curso['x_template_id']);
            array_push($detalle, $curso);            
        }        

        return $this->render('index', [
            'detalle' => $detalle
        ]);

    }


    private function consulta_detalle_cursos($periodoId, $institutoId, $section){
        $con = Yii::$app->db;
        $query = "select c.id ,c.name as curso ,c.x_template_id
                    ,(select 	count(mal.id) as total
                    from	ism_malla mal 
                                    inner join ism_periodo_malla malipm on malipm.malla_id = mal.id 
                                    inner join ism_malla_area malima on malima.periodo_malla_id = malipm.id  
                                    inner join ism_area_materia maliam on maliam.malla_area_id = malima.id 
                    where 	mal.op_course_template_id = c.x_template_id) as total_materias
                    from op_course c 
                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = c.period_id 
                    inner join op_section s on s.id = c.section 
                    where sop.scholaris_id = $periodoId
                    and s.code = '$section' 
                    and c.x_institute = $institutoId
                    group by c.id ,c.name ,c.x_template_id 
                    order by c.x_template_id desc;";
       
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }
    
    private function consulta_totales($templateId){
        $con = Yii::$app->db;
        $query = "select 	estado,count(pc.estado) as total 
                    from 	planificacion_desagregacion_cabecera pc 
                                    inner join ism_area_materia iam on iam.id = pc.ism_area_materia_id 
                                    inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                                    inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                                    inner join ism_malla im on im.id = ipm.malla_id 
                    where 	im.op_course_template_id = $templateId 
                    group by estado order by estado;";        
        
        $res = $con->createCommand($query)->queryAll();

        return $res;
    }

    public function actionAsignaturas(){
        $periodoId  = Yii::$app->user->identity->periodo_id;
        $templateId = $_GET['template_id'];        
        
        $asignaturas = $this->get_asignaturas($periodoId, $templateId);
        
        
        return $this->render('asignaturas', [
            'asignaturas' => $asignaturas
        ]);

    }
    
    private function get_asignaturas($periodoId, $opCourseTemplateId){
        $con = Yii::$app->db;
        $query = "select pc.id, pc.year_from, pc.year_to, pc.is_active, pc.comments
                                , pc.scholaris_periodo_id, pc.carga_horaria_semanal, pc.semanas_trabajo
                                , pc.evaluacion_aprend_imprevistos, pc.total_semanas_clase
                                , pc.total_periodos, pc.estado, pc.coordinador_user
                                , pc.fecha_envio_coordinador, pc.fecha_revision_coordinacion
                                , pc.revision_coordinacion_observaciones, pc.fecha_de_cambios
                                , pc.fecha_aprobacion_coordinacion, pc.ism_area_materia_id 
                                , im2.nombre as materia 
                                , oct.name
                from 	planificacion_desagregacion_cabecera pc
                                inner join ism_area_materia iam ON iam.id = pc.ism_area_materia_id 
                                inner join ism_malla_area ima on ima.id = iam.malla_area_id 
                                inner join ism_periodo_malla ipm on ipm.id = ima.periodo_malla_id 
                                inner join ism_malla im on im.id = ipm.malla_id 
                                inner join ism_materia im2 on im2.id = iam.materia_id 
                                inner join op_course_template oct on oct.id = im.op_course_template_id
                where 	pc.scholaris_periodo_id = $periodoId
                                and im.op_course_template_id = $opCourseTemplateId;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function actionDetalle(){
        $cabeceraId = $_GET['cabecera_id'];
        $cabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);
        $estado = $cabecera->estado;
        $pca = new Pca($cabeceraId);

        $desagregacion = array();
        $bloques = $this->consultar_bloques($cabeceraId); //consulta los bloques configurados en el curriculo mec
        
        foreach($bloques as $blo){
            $bloqueId = $blo['id'];

            //Inicia detalle de criterios de evaluación y destrezas
            $criterios = $this->consultar_criterios_evaluacion($bloqueId, $cabeceraId);            
            $arregloCriterios = array();

            foreach($criterios as $ce){
                $destrezas = PlanificacionDesagregacionCriteriosDestreza::find()->where([
                    'desagregacion_evaluacion_id' => $ce['plan_ce_id']
                ])->all();                
                $ce['destrezas'] = $destrezas;              
                array_push($arregloCriterios, $ce);
            }  
            
            $blo['criterios'] = $arregloCriterios;            
            //Fin de proceso de criterios de evaluacion y destrezas

            //INICIA Detalle de PH y PV
            $phPv = $this->consulta_ph_pv($bloqueId, $cabeceraId);
            $blo['ph_pv'] = $phPv;
            //FINALIZA Detalle de PH y PV//////////////////


            ////// INICIA CRITERIOS PAI //////
            $criteriosPai = $this->criterios_pai($bloqueId, $cabeceraId);
            $blo['criterios_pai'] = $criteriosPai;
            ////// FINALIZA CRITERIOS PAI //////

            array_push($desagregacion, $blo);
        }

        if(isset($_POST['revision_coordinacion_observaciones'])){            

            $fechaHoy = date('Y-m-d H:i:s');

            // echo $estado;
            // die();

            if($estado == 'DEVUELTO'){
                $cabecera->fecha_de_cambios = $fechaHoy; 
            }

            $cabecera->fecha_revision_coordinacion = $fechaHoy;
            $cabecera->revision_coordinacion_observaciones = $_POST['revision_coordinacion_observaciones'];
            $cabecera->estado = 'DEVUELTO';
            $cabecera->save();
            return $this->redirect(['detalle', 'cabecera_id' => $cabeceraId]);
        }

        return $this->render('detalle', [
            'pca'       => $pca,
            'cabecera'  => $cabecera,
            'desagregacion' => $desagregacion
        ]);
    }

    private function consultar_bloques($cabeceraId){
        $con = Yii::$app->db;
        $query = "select 	b.id 
                            ,b.last_name 
                            ,pb.unit_title 
                            ,pb.enunciado_indagacion 
                    from	curriculo_mec_bloque b
                            inner join planificacion_bloques_unidad pb on b.id = pb.curriculo_bloque_id 
                    where 	pb.plan_cabecera_id = $cabeceraId
                    order by b.code ;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consultar_criterios_evaluacion($curriculoBloqueId, $cabeceraId){
        $con = Yii::$app->db;
        $query = "select 	ce.id as plan_ce_id
                            ,cm.code 
                            ,cm.description 
                    from 	planificacion_bloques_unidad bu
                            inner join planificacion_desagregacion_criterios_evaluacion ce ON ce.bloque_unidad_id = bu.id 
                            inner join curriculo_mec cm on cm.id = ce.criterio_evaluacion_id 
                    where 	bu.curriculo_bloque_id = $curriculoBloqueId
                            and	bu.plan_cabecera_id = $cabeceraId;";
        // echo $query;
        // die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_ph_pv($bloqueId, $cabeceraId){
        $con = Yii::$app->db;
        $query = "select 	op.plan_unidad_id, op.tipo 
                    from 	planificacion_vertical_pai_opciones op
                            inner join planificacion_bloques_unidad bl on bl.id = op.plan_unidad_id 
                    where 	bl.plan_cabecera_id = $cabeceraId
                            and bl.curriculo_bloque_id = $bloqueId
                    group by op.plan_unidad_id, op.tipo;";
        // echo $query;
        // die();
        $res = $con->createCommand($query)->queryAll();

        $arregloPhPv = array();
        
        foreach($res as $phPv){
            $contenidos = PlanificacionVerticalPaiOpciones::find()->where([
                'plan_unidad_id' => $phPv['plan_unidad_id'],
                'tipo' => $phPv['tipo']
            ])->all();

            $phPv['contenidos'] = $contenidos;

            array_push($arregloPhPv, $phPv);
        }         

        return $arregloPhPv;
    }

    private function criterios_pai($bloqueId, $cabeceraId){
        $con = Yii::$app->db;
        $query = "select 	c.criterio 
                            , sd.descricpcion 
                    from 	planificacion_vertical_pai_descriptores d
                            inner join planificacion_bloques_unidad b on b.id = d.plan_unidad_id 
                            inner join scholaris_criterio_descriptor sd on sd.id = d.descriptor_id 
                            inner join scholaris_criterio c on c.id = sd.criterio_id 
                    where 	b.curriculo_bloque_id = $bloqueId
                            and b.plan_cabecera_id = $cabeceraId
                    order by c.criterio, sd.codigo;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }


    public function actionAprobacion(){
        $fechaHoy = date('Y-m-d H:i:s');
        $cabeceraId = $_GET['cabecera_id'];
        $model = PlanificacionDesagregacionCabecera::findOne($cabeceraId);

        $model->estado = 'APROBADO';
        $model->fecha_aprobacion_coordinacion = $fechaHoy;
        $model->save();

        //INICIA APROBAR TODOS LOS PLANES DE LA CABECERA
        $this->actualiza_estados_pud($cabeceraId);
        //TERMINA APROBAR TODOS LOS PLANES DE LA CABECERA

        return $this->redirect(['detalle', 'cabecera_id' => $cabeceraId]);

    }

    private function actualiza_estados_pud($cabeceraId){
        $con = Yii::$app->db;
        $query = "update planificacion_bloques_unidad 
                  set pud_status = true,
                      is_open = false
                  where plan_cabecera_id = $cabeceraId";
        $res = $con->createCommand($query)->execute();
    }

}