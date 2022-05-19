<?php
namespace backend\controllers;

//use backend\models\pca\DatosInformativos as PcaDatosInformativos;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PlanificacionVerticalDiploma;
use backend\models\PlanificacionBloquesUnidadSubtitulo; 
use backend\models\PlanificacionBloquesUnidadSubtitulo2;
use backend\models\PlanificacionVerticalDiplomaRelacionTdc;
use backend\models\puddip\Pdf;
use backend\models\puddip\DatosInformativos;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use DateTime;


class PudDipController extends Controller{

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
    /*******************************************************************************************************************************************/
    /*** ACCIONES  */

    public function actionIndex1()
    {       
        $planBloqueUnidadId = $_GET['plan_bloque_unidad_id'];        
        $planUnidad = PlanificacionBloquesUnidad::findOne($planBloqueUnidadId);
        return $this->render('index', [
            'planUnidad' => $planUnidad
        ]);       
    }  

    public function actionPestana(){
        
        $planUnidadId = $_GET['plan_unidad_id'];
        $pestana   = $_GET['pestana'];   
        $respuesta =   '';

        switch ($pestana) {
            case '1.1.-':
                $respuesta = $this->get_datos_informativos($planUnidadId); 
                break;
            case '2.1.-':
                $respuesta =$this->get_descripcion_text_unidad($planUnidadId);
                break;
            case '3.1.-':
                $respuesta =$this->get_evaluacion_pd_unidad($planUnidadId);
                break;
            case '4.1.-':
                $respuesta =$this->get_indagacion($planUnidadId);
                    break;
            case '5.1.-':
                $respuesta =$this->get_accion_habilidades($planUnidadId);
                break;
            case '5.2.-':
                $respuesta = $this->get_accion_proceso_aprendizaje($planUnidadId);;
                break;
            case '5.3.-':
                $respuesta = $this->get_accion_enfoque_aprendizaje($planUnidadId);;
                break;
            case '5.4.-':
                $respuesta =$this->get_accion_lenguaje_aprendizaje($planUnidadId);
                break;
            case '5.4.1.-':
                $respuesta =$this->get_accion_lenguaje_aprendizaje($planUnidadId);
                break;
            case '5.5.-':
                $respuesta = $this->get_accion_conexion_tdc($planUnidadId);
                break;
            case '5.6.-':
                $respuesta = $this->get_accion_conexion_cas($planUnidadId);;
                break;
            case '5.6.1.-':
                $respuesta = $this->get_accion_conexion_cas($planUnidadId);;
                break;
            case '6.1.-':
                $respuesta = $this->get_accion_recurso($planUnidadId);
                break;
            case '7.1.-':
                $respuesta = $this->get_accion_lo_que_funciono($planUnidadId);
                break;
            case '7.2.-':
                $respuesta = $this->get_accion_lo_que_no_funciono($planUnidadId);
                break;
            case '7.3.-':
                $respuesta = $this->get_accion_observacion($planUnidadId);
                break;                
        }    
        return  $respuesta;      
    }
    
    //2.1.- Descripcion y Texto de una Unidad
    public function actionUpdateDescriTextUni(){                                 
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               

        $model->descripcion_texto_unidad = $contenido;
        $model->save();        
    }
    //5.1.- Contenido, Habilidades y conceptos
    public function actionUpdateHabilidades(){             
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               

        $model->habilidades = $contenido;
        $model->save();        
    }
    //5.2.- Proceso de Aprendizaje
    public function actionUpdateProcesoAprendizaje(){             
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               

        $model->proceso_aprendizaje = $contenido;
        $model->save();        
    }
    
    //5.4.- Lenguaje de Aprendizaje
    public function actionUpdateLenguajeAprendizaje(){             
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               
                
        $model->detalle_len_y_aprendizaje = $contenido;
        $model->save();        
    }
    //5.4.1.- Conexion CAS
    public function actionUpdateLenguajeAprendizajeCheck(){ 
        
        $idPvd = $_GET['id_plani_vert_dip'];
        $idPvd_Op = $_GET['id_pvd_op'];
        $tipoProc   = $_GET['tipo_proceso'];

        $this->insertUpdateConexionCas($idPvd, $idPvd_Op,$tipoProc);
    }
    //5.5.- Conexion TDC
    public function actionUpdateConexionTdc(){             
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               

        $model->conexion_tdc = $contenido;
        $model->save();        
    }
    //5.6.- Conexion CAS
    public function actionUpdateConexionCas(){             
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               

        $model->detalle_cas = $contenido;
        $model->save();        
    }
    //5.6.1.- Conexion CAS
    public function actionUpdateConexionCasCheck(){ 
        
        $idPvd = $_GET['id_plani_vert_dip'];
        $idPvd_Op = $_GET['id_pvd_op'];
        $tipoProc   = $_GET['tipo_proceso'];

        $this->insertUpdateConexionCas($idPvd, $idPvd_Op,$tipoProc);
    }
    private function insertUpdateConexionCas($idPvd, $idPvd_Op,$tipo_proceso) 
    { 
        //realiza la actualizacion de conexion cas TDC       
        $userLog = Yii::$app->user->identity->usuario;
        $fechaHoy = date('Y-m-d H:i:s');        
        $plan_vertical_id=$idPvd;
        $pvd_tdc_id=$idPvd_Op;
        
        if ($tipo_proceso=='Agregar'){            
            $model = new PlanificacionVerticalDiplomaRelacionTdc();
            $model->vertical_diploma_id = $plan_vertical_id;
            $model->relacion_tdc_id =  $pvd_tdc_id;
            $model->created =  $userLog;
            $model->created_at =  $fechaHoy;
            $model->save();
        }
        else
        {
            $model=  PlanificacionVerticalDiplomaRelacionTdc::findOne($pvd_tdc_id);
            $model->delete();
        }      
                
    }
    //6.1.- Recursos
    public function actionUpdateRecursos(){             
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               

        $model->recurso = $contenido;
        $model->save();        
    }
    //7.1.- funciono
    public function actionUpdateFunciono(){             
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               

        $model->reflexion_funciono = $contenido;
        $model->save();        
    }
    //7.2.- no funciono
    public function actionUpdateNoFunciono(){             
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               

        $model->reflexion_no_funciono = $contenido;
        $model->save();        
    }
    //7.3.- no funciono
    public function actionUpdateObservacion(){             
        $idPlanVertDip = $_GET['id_plani_vert_dip'];
        $contenido   = $_GET['contenido']; 
        $model = PlanificacionVerticalDiploma::find()->where([
            'id' => $idPlanVertDip
        ])->one();               

        $model->reflexion_observacion= $contenido;
        $model->save();        
    }
    //generador pdf, pud dip
    public function actionPdfPudDip()
    {
        $idPlanUniBloque = $_GET['planificacion_unidad_bloque_id'];                
        new Pdf($idPlanUniBloque);
    }
    
    /*** FIN  ACCIONES  */
    /*******************************************************************************************************************************************/
    /*** METODOS CONSULTAS  */

    /*** 1.-  Datos Informativos */
    private function get_datos_informativos($planUnidadId){
        //llamada a los  modelos
        
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);       
        $scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $institutoId = Yii::$app->user->identity->instituto_defecto;  
        
        $tiempo = $this->calcula_horas($planBloqueUnidad->planCabecera->scholaris_materia_id, 
                                      $planBloqueUnidad->planCabecera->op_course_template_id,$scholarisPeriodoId,$planBloqueUnidad);      

       //creacion html                                           
       $html ='';        
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $html .= '<div class="card" style="width: 70%; margin-top:20px">';

                $html .= '<div class="card-header">';
                $html .= '<h5 class=""><b>1.- DATOS INFORMATIVOS</b></h5>';
                $html .= '</div>';
                
            $html .= '<div class="card-body">';
                // inicia row
                $html .= '<div class="row">';
                $html .= '<div class="col"><b>GRUPO DE ASIGNATURAS Y DISCIPLINA</b></div>';
                $html .= '<div class="col">'.$planBloqueUnidad->planCabecera->scholarisMateria->name.'</div>';
                $html .= '<div class="col"><b>PROFESOR(ES)</b></div>';
                $docentes = $this->get_docentes($planBloqueUnidad,$scholarisPeriodoId);
                $html .= '<div class="col">';
                    foreach($docentes as $docente){
                        $html .= '◘ '.$docente['docente'].' <br> ';
                    }                    
                $html .= '</div>';
                $html .= '</div>';
                //******finaliza row
                $html .= '<hr>';
                //inicia row
                $html .= '<div class="row">';
                $html .= '<div class="col"><b>UNIDAD Nº</b></div>';
                $html .= '<div class="col">'.$planBloqueUnidad->curriculoBloque->last_name.'</div>';
                $html .= '<div class="col"><b>TÍTULO DE LA UNIDAD</b></div>';            
                $html .= '<div class="col">'.$planBloqueUnidad->unit_title.'</div>';
                $html .= '</div>';
                //******finaliza row
                $html .= '<hr>';
                //inicia row
                $html .= '<div class="row">';
                $html .= '<div class="col"><b>AÑO DEL DIP:</b></div>';
                $html .= '<div class="col">'.$planBloqueUnidad->planCabecera->opCourseTemplate->name.'</div>';
                $html .= '<div class="col"><b>DURACIÓN DE LA UNIDAD EN HORAS:</b></div>';                
                $html .= '<div class="col">'.$tiempo['horas'].'</div>';
                $html .= '</div>';
                //******finaliza row
                $html .= '<hr>';
                //inicia row
                $html .= '<div class="row">';
                $html .= '<div class="col"><b>FECHA INICIO:</b></div>';
                $html .= '<div class="col">'.$tiempo['fecha_inicio'].'</div>';
                $html .= '<div class="col"><b>FECHA FIN:</b></div>';                
                $html .= '<div class="col">'.$tiempo['fecha_final'].'</div>';
                $html .= '</div>';
                //******finaliza row
            $html .= '</div>';//fin de card-body
            $html .= '</div>';
        $html .= '</div>';
               
        return     $html;

    }

    private function get_docentes($planBloqueUnidad,$scholarisPeriodoId){
        $materiaId = $planBloqueUnidad->planCabecera->scholaris_materia_id;       
        $templateId = $planBloqueUnidad->planCabecera->op_course_template_id;

        $con = Yii::$app->db;
        $query = "select 	concat(f.x_first_name,' ', f.last_name) as docente
        from 	scholaris_clase c 
                 inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
                 inner join op_course oc on oc.id = c.idcurso 
                inner join op_faculty f on f.id = c.idprofesor 
         where 	c.idmateria  = $materiaId
                 and p.id = $scholarisPeriodoId
                 and oc.x_template_id = $templateId
         group by f.x_first_name, f.last_name;";

        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }

    private function calcula_horas($materiaId, $courseTemplateId,$scholarisPeriodoId,$planBloqueUnidad){
        $con = Yii::$app->db;
         
        $query = "select 	count(h.detalle_id) as hora_semanal
                    ,h.clase_id 
                    ,cla.tipo_usu_bloque
        from	scholaris_horariov2_horario h		
                inner join scholaris_clase cla on cla.id = h.clase_id
        where 	h.clase_id = (select 	max(c.id)
                                from 	scholaris_clase c 
                                        inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
                                        inner join op_course oc on oc.id = c.idcurso 
                                        inner join op_faculty f on f.id = c.idprofesor 
                                where 	c.idmateria  = $materiaId
                                        and p.id = $scholarisPeriodoId
                                        and oc.x_template_id = $courseTemplateId) group by h.clase_id, cla.tipo_usu_bloque;";         
                                             
        $resH = $con->createCommand($query)->queryOne();
       
        $horasSemana = $resH['hora_semanal'];
        $uso = $resH['tipo_usu_bloque'];
        $orden = $planBloqueUnidad->curriculoBloque->code;
        
        $queryFechas = "select 	b.bloque_inicia 
                                ,b.bloque_finaliza 
                        from 	scholaris_bloque_actividad b
                                inner join scholaris_periodo p on p.codigo = b.scholaris_periodo_codigo 
                        where 	b.tipo_uso = '$uso'
                                and p.id = $scholarisPeriodoId
                                and b.orden = $orden;";
        $resF = $con->createCommand($queryFechas)->queryOne();        
        
        $fechaInicia = new DateTime($resF['bloque_inicia']);
        $fechaFinal = new DateTime($resF['bloque_finaliza']);

        $diff = $fechaInicia->diff($fechaFinal);

        return array(
            'horas' => ($diff->days) * $horasSemana,
            'fecha_inicio' => $resF['bloque_inicia'],
            'fecha_final' => $resF['bloque_finaliza']
        );

    }

    /*** FIN 1.- CONSULTA DATOS INFORMATIVOS */
    
    /*** 2.1.-  Descripcion y textos de la Unidad */
    private function get_descripcion_text_unidad($planBloqueUnidad)
    { 
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);  
        $accion_update = "2.1.-";
        $titulo = "2.1.- DESCRIPCION Y TEXTO DE LA UNIDAD";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id'=>$planBloqueUnidad->id
        ])->one();                       
        return     $this->mostrar_campo_simple($planifVertDipl->id,$planifVertDipl->descripcion_texto_unidad,$titulo,$accion_update,"");
    } 
    /*** 3.1.-  EVALUACION DEL PD PARA LA UNIDAD */
    private function get_evaluacion_pd_unidad($planBloqueUnidad)
    { 
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);  
        $accion_update = "3.1.-";
        $titulo = "3.1.- EVALUACION DEL PD PARA LA UNIDAD";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id'=>$planBloqueUnidad->id
        ])->one();                       
        return     $this->mostrar_campo_viene_de($planifVertDipl->id,$planifVertDipl->objetivo_asignatura,$titulo,$accion_update,"");
    } 
     /*** 4.1.-  Indagacion */
     private function get_indagacion($planBloqueUnidad)
     { 
        $text_intro = "OBJETIVOS DE TRANFERENCA
        <br>
        Haga una lista de uno a tres objetivos grandes, globales y de largo plazo para esta unidad. Los objetivos de transferencia 
        son aquellos que los estudiantes aplicarán, sus conocimientos, habilidades y conceptos al final de la unidad bajo circunstancias 
        nuevas / diferentes, y por si mismos sin el andamiaje del maestro. 
        <hr>"; 
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);  
         $accion_update = "4.1.-";
         $titulo = "4.1.- INDAGACION";
         $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
             'planificacion_bloque_unidad_id'=>$planBloqueUnidad->id
         ])->one();                       
         return  $this->mostrar_campo_viene_de($planifVertDipl->id,$planifVertDipl->objetivo_asignatura,$titulo,$accion_update,$text_intro);
     }   
     /*** 5.1 Accion contenido habilidad y concepto */ 
     private function get_accion_habilidades($planBloqueUnidad)
    {       
        $contenidoImp='';
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);  
        $accion_update = "5.1.-";
        $titulo = "5.1.- CONTENIDO, HABILIDADES Y CONCEPTOS: CONOCIMIENTOS ESENCIALES";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id'=>$planBloqueUnidad->id
        ])->one(); 
        //bucle para capturar el contenido
        $arrayContenido=$this->select_contenidos($planBloqueUnidad->id);
        
        if(count($arrayContenido)>0){
            foreach($arrayContenido as $arraySubContenido){
                $contenidoImp .='<li>';   
                $contenidoImp .= '<u><b>'.$arraySubContenido['subtitulo'].'</b></u>';   
                $contenidoImp .= '<ul>';                
                foreach($arraySubContenido['subtitulos'] as $contenido){
                    $contenidoImp .= '<li>♠ '.$contenido['contenido'].'</li>';                    
                }
                $contenidoImp .= '</ul>';
                $contenidoImp .= '</li>';  
            }
        }
        //FIN bucle para capturar el contenido       
        $contenido = $this->mostrar_campo_viene_de($planifVertDipl->id,$contenidoImp,"CONTENIDOS",$accion_update,"");
        $habilidad =$this->mostrar_campo_simple($planifVertDipl->id,$planifVertDipl->habilidades,"HABILIDADES",$accion_update,"");
        $concepto = $this->mostrar_campo_viene_de($planifVertDipl->id,$planifVertDipl->concepto_clave,"CONCEPTOS",$accion_update,"");
        $respuesta = $this->mostrar_campo_viene_de($planifVertDipl->id,$contenido.$habilidad.$concepto,$titulo,$accion_update,"");
        //$respuesta = $contenido.$habilidad.$concepto ; 
        return $respuesta;     
    }
    /*** 5.2 Accion Proceso de Aprendizaje*/
    private function get_accion_proceso_aprendizaje($planBloqueUnidad)
    {
        $textCab = 'Escriba el enfoque pedagógico que usará durante la unidad, este debe emplearse para ayudar a facilitar el aprendizaje.';
        $textCab .= '<br><br>';

        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "5.2.-";
        $titulo = "5.2.- PROCESO DE APRENDIZAJE";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
        ])->one();
        $texto = $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->proceso_aprendizaje, $titulo, $accion_update,$textCab);
        $instEvaluacion = $this->mostrar_campo_viene_de($planifVertDipl->id, $planifVertDipl->intrumentos, "Evaluación Formativa / Sumativa", $accion_update,"");
        return   $texto.  $instEvaluacion;
    }  
    /*** 5.3 Enfoque del aprendizaje*/
    private function get_accion_enfoque_aprendizaje($planBloqueUnidad)
    {
        $textItem='';
        $textDetalle=''; 
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "5.3.-";
        $titulo = "5.3.- ENFOQUE DEL APRENDIZAJE (EDA)";
        $titulo2="Detalles";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
        ])->one();
        //bucle para capturar las habilidades
        $tipo_consulta='titulos';
        $arrayHabEnfoque = $this->select_habilidadesTDC($planifVertDipl->id,$tipo_consulta);        
       if(count($arrayHabEnfoque)>0){
            foreach ($arrayHabEnfoque as $arraySubHab) {
                $textItem .= '<ul>';
                foreach ($arraySubHab as $contenido) {
                    $textItem .= '<li><font size="4"><b>♠ ' . $contenido . '</b></font></li>';
                }
                $textItem .= '</ul>';                               
            }
            $textItem .= '<br>'; 
            
        } 
        $textDetalle .= '';    
        $tipo_consulta='detalles';
        $arrayHabEnfoque = $this->select_habilidadesTDC($planifVertDipl->id,$tipo_consulta);        
       if(count($arrayHabEnfoque)>0){
            foreach ($arrayHabEnfoque as $arraySubHab) {
                $textDetalle .= '<ul>';
                foreach ($arraySubHab as $contenido) {
                    $textDetalle .= '<li>♠ ' . $contenido . '</li>';
                }
                $textDetalle .= '</ul>';                               
            }            
        }    
        $impItems = $this->mostrar_campo_viene_de($planifVertDipl->id, $textItem, $titulo, $accion_update,"");
        $impDetalles = $this->mostrar_campo_viene_de($planifVertDipl->id, $textDetalle, $titulo2, $accion_update,"");

        return   $impItems. $impDetalles;
    }  
    /*** 5.4 Accion Lenguaje y aprendizaje */
    private function get_accion_lenguaje_aprendizaje($planBloqueUnidad)
    {
        $idPvdOp='';
        $agregar='Agregar';
        $quitar='Quitar'; 
        $textoCab ='';
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "5.4.-";
        $accion_update_op = "5.4.1.-";
        $titulo = "5.4.- LENGUAJE Y APRENDIZAJE";
        $titulo2 = "Detalles";

        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
        ])->one();
        $modelPlanifVertDiplTDC = $this->consultar_lenguaje_y_aprendizaje_ckeck($planifVertDipl->id); 

        $itemConexionCas = '';
        $itemConexionCas.="<table class=\"table table-hover table-condensed table-striped table-bordered\">";
        $idPvdOp ='';

        foreach($modelPlanifVertDiplTDC as $tdc)
        {            
            if($tdc['es_seleccionado'])
            {
                $idPvdOp=$tdc['pvd_tdc_id'];//Id de tabla planifi_vd_relacion_tdc
                $itemConexionCas.='<tr>';
                $itemConexionCas.='<td>';
                $itemConexionCas.='<font size="3"><u><b>♠ '.$tdc['opcion'].'</b></u></font>';
                $itemConexionCas.='</td>';
                $itemConexionCas.='<td>';
                $itemConexionCas.='<a href="#"   class="far fa-thumbs-up" style="color: #0a1f8f" onclick="update_campos_check('.$planifVertDipl->id.','.$idPvdOp.',\''.$accion_update_op.'\',\''.$quitar.'\')"></a>';
                $itemConexionCas.='</td>';
                $itemConexionCas.='</tr>';                             
            }
            else
            {
                $idPvdOp=$tdc['tdc_id'];//Id de tabla planificacion opciones
                $itemConexionCas.='<tr>';
                $itemConexionCas.='<td>';
                $itemConexionCas.='<font size="3"><b>♠ '.$tdc['opcion'].'</b></font>';
                $itemConexionCas.='</td>';
                $itemConexionCas.='<td>';
                $itemConexionCas.='<a href="#" class="fas fa-thumbs-down" style="color: #ab0a3d" onclick="update_campos_check('.$planifVertDipl->id.','.$idPvdOp.',\''.$accion_update_op.'\',\''.$agregar.'\')"></a>';
                $itemConexionCas.='</td>';
                $itemConexionCas.='</tr>';             
                
            }
        }
        $itemConexionCas.="</table>";      
       
       $texto= $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->detalle_len_y_aprendizaje, $titulo2, $accion_update,"");
       $selectcion = $this->mostrar_campo_viene_de($planifVertDipl->id, $itemConexionCas, $titulo, $accion_update,$textoCab); 
       return $selectcion.$texto;

    }  
    /*** 5.5 Conexion con TDC */
    private function get_accion_conexion_tdc($planBloqueUnidad)
    {
        $textoImp = '';
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "5.5.-";
        $titulo = "5.5.- CONEXION CON TDC";
        $titulo2 = "Detalle";

        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
        ])->one();
        $arrayConexionTDC = $this->select_relacionTDC($planifVertDipl);
        foreach($arrayConexionTDC as $conexionTDC)
        {
            $textoImp = '<font size="3">♠ '.$conexionTDC->relacionTdc->opcion.'</font><br>'. $textoImp;
        }
        $textoImp.='<br>';
        $relacionTdc2 =$this->mostrar_campo_viene_de($planifVertDipl->id, $textoImp, $titulo, $accion_update,"");
        $relacionTdc =$this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->conexion_tdc, $titulo2, $accion_update,"");
        return  $relacionTdc2.$relacionTdc;
    } 
    /** 5.6 Conexion con CAS*/
    private function get_accion_conexion_cas($planBloqueUnidad)
    {
        $idPvdOp='';
        $agregar='Agregar';
        $quitar='Quitar'; 
        $textoCab ='Marque las casillas para ver si hay conexiones CAS explicitas, Si marca alguna de las casillas, proporcione 
                    una nota breve en la sección de “Detalles”, que explique cómo los estudiantes se involucraron en CAS para esta unidad.
                    <br><br>';

        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "5.6.-";
        $accion_update_op = "5.6.1.-";
        $titulo = "5.6.- CONEXION CON CAS";
        $titulo2 = "Detalles";
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
        ])->one();
        $modelPlanifVertDiplTDC = $this->consultar_conexion_cas_ckeck($planifVertDipl->id); 

        $itemConexionCas = '';
        $itemConexionCas.="<table class=\"table table-hover table-condensed table-striped table-bordered\">";
        $idPvdOp ='';

        foreach($modelPlanifVertDiplTDC as $tdc)
        {            
            if($tdc['es_seleccionado'])
            {
                $idPvdOp=$tdc['pvd_tdc_id'];//Id de tabla planifi_vd_relacion_tdc
                $itemConexionCas.='<tr>';
                $itemConexionCas.='<td>';
                $itemConexionCas.='<font size="3"><u><b>♠ '.$tdc['opcion'].'</b></u></font>';
                $itemConexionCas.='</td>';
                $itemConexionCas.='<td>';
                $itemConexionCas.='<a href="#"   class="far fa-thumbs-up" style="color: #0a1f8f" onclick="update_campos_check('.$planifVertDipl->id.','.$idPvdOp.',\''.$accion_update_op.'\',\''.$quitar.'\')"></a>';
                $itemConexionCas.='</td>';
                $itemConexionCas.='</tr>';                             
            }
            else
            {
                $idPvdOp=$tdc['tdc_id'];//Id de tabla planificacion opciones
                $itemConexionCas.='<tr>';
                $itemConexionCas.='<td>';
                $itemConexionCas.='<font size="3"><b>♠ '.$tdc['opcion'].'</b></font>';
                $itemConexionCas.='</td>';
                $itemConexionCas.='<td>';
                $itemConexionCas.='<a href="#" class="fas fa-thumbs-down" style="color: #ab0a3d" onclick="update_campos_check('.$planifVertDipl->id.','.$idPvdOp.',\''.$accion_update_op.'\',\''.$agregar.'\')"></a>';
                $itemConexionCas.='</td>';
                $itemConexionCas.='</tr>';             
                
            }
        }
        $itemConexionCas.="</table>";      
        $selectcion = $this->mostrar_campo_viene_de($planifVertDipl->id, $itemConexionCas, $titulo, $accion_update,$textoCab); 
        $texto=       $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->detalle_cas, $titulo2, $accion_update,"");
       
       return $selectcion.$texto;
    } 
    /** 6.1.- Recursos*/
    private function get_accion_recurso($planBloqueUnidad)
    {
        $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
        $accion_update = "6.1.-";
        $titulo = "6.1.- RECURSOS";
        
        $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
            'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
        ])->one();        
        return  $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->recurso, $titulo, $accion_update,"");
    }  
     /** 7.1.- REFLEXION, LO QUE FUNCINO*/
     private function get_accion_lo_que_funciono($planBloqueUnidad)
     {
         $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
         $accion_update = "7.1.-";
         $titulo = "7.1.- Lo que funcino";
         $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
             'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
         ])->one();
         return     $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->reflexion_funciono, $titulo, $accion_update,"");
     } 
     /** 7.2.- REFLEXION, LO QUE NO FUNCINO*/
     private function get_accion_lo_que_no_funciono($planBloqueUnidad)
     {
         $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
         $accion_update = "7.2.-";
         $titulo = "7.2.- Lo que no funcino";
         $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
             'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
         ])->one();
         return     $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->reflexion_no_funciono, $titulo, $accion_update,"");
     }  
      /** 7.3.- REFLEXION, OBSERVACION, CAMBIOS. SUGERECIAS*/
    private function get_accion_observacion($planBloqueUnidad)
    {
          $planBloqueUnidad   = PlanificacionBloquesUnidad::findOne($planBloqueUnidad);
          $accion_update = "7.3.-";
          $titulo = "7.3.- Observaciones, Cambios y Sugerencias";
          $planifVertDipl = PlanificacionVerticalDiploma::find()->where([
              'planificacion_bloque_unidad_id' => $planBloqueUnidad->id
          ])->one();
          return     $this->mostrar_campo_simple($planifVertDipl->id, $planifVertDipl->reflexion_observacion, $titulo, $accion_update,"");
    }  
    //metodo usado para 5.1.-, llamada a contenidos
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
    // metodo usado para 5.3.- llamada a habilidades
    private function select_habilidadesTDC($planVerticalDiplId,$tipo_consulta)
    {
        //muestra todas las habilidades, segun el codigo del plan vertical diploma, que este asociada       
        $con = Yii::$app->db;
        switch($tipo_consulta){
            case 'titulos':
                 $query ="select distinct cph.es_titulo2 
                    from contenido_pai_habilidades cph , planificacion_vertical_diploma_habilidades pvdh 
                    where cph.id = pvdh .habilidad_id  
                    and pvdh.vertical_diploma_id = $planVerticalDiplId
                    order by cph.es_titulo2;"; 
            break;
            case 'detalles':
                $query ="select  (cph.es_titulo2 || ': ' ||cph.es_exploracion) as dato  
                from contenido_pai_habilidades cph , planificacion_vertical_diploma_habilidades pvdh 
                where cph.id = pvdh .habilidad_id  
                and pvdh.vertical_diploma_id = $planVerticalDiplId
                order by cph.es_titulo2;"; 

            break;
        }
        $resultado = $con->createCommand($query)->queryAll();        
        return $resultado;
    }
    //metodo usado para 5.5.- llamada a relacion tdc
    private function select_relacionTDC($planVerticalDipl)
    {
        //muestra todas las relacion tdc, segun el codigo del plan vertical diploma, que este asociada
        $idPlanVertDipl = $planVerticalDipl->id;       
        $planVertDip_Relacion = PlanificacionVerticalDiplomaRelacionTdc::find()->where([
            'vertical_diploma_id' => $idPlanVertDipl
        ])->all(); 
        
        return $planVertDip_Relacion;
    }
    //metodo usado para 5.6.- llamada a Conexion CAS
    private function consultar_conexion_cas_ckeck($planVertDiplId) 
    {
        //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados
       $con = Yii::$app->db;
       $query = "select p.categoria ,p.opcion,pr.id as pvd_tdc_id ,p.id as tdc_id, 
                case 
                when p.id is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                planificacion_vertical_diploma pvd 
                where p.tipo='CONEXION_CAS'  and pvd.id =$planVertDiplId 
                and pr.vertical_diploma_id = pvd.id   
                and pr.relacion_tdc_id  = p.id
                union all 
                select p.categoria ,p.opcion,0 as pvd_tdc_id,p.id tdc_id, 
                case 
                when null is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p
                where p.tipo='CONEXION_CAS'
                and p.id not in 
                ( select relacion_tdc_id  from planificacion_vertical_diploma_relacion_tdc 
                where vertical_diploma_id = $planVertDiplId)
                order by opcion;
                ";
        $resultado = $con->createCommand($query)->queryAll();
        return $resultado;
    } 
    //metodo usado para 5.4.- llamada a lenguaje y aprendizaje
    private function consultar_lenguaje_y_aprendizaje_ckeck($planVertDiplId) 
    {
        //consulta los los tdc que han sido marcados con check, mas los que aun no estan marcados
       $con = Yii::$app->db;
       $query = "select p.categoria ,p.opcion,pr.id as pvd_tdc_id ,p.id as tdc_id, 
                case 
                when p.id is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p, planificacion_vertical_diploma_relacion_tdc pr,
                planificacion_vertical_diploma pvd 
                where p.tipo='LENGUAJE_Y_CONOCIMIENTO'  and pvd.id =$planVertDiplId 
                and pr.vertical_diploma_id = pvd.id   
                and pr.relacion_tdc_id  = p.id
                union all 
                select p.categoria ,p.opcion,0 as pvd_tdc_id,p.id tdc_id, 
                case 
                when null is not null then true else false
                end as es_seleccionado
                from planificacion_opciones p
                where p.tipo='LENGUAJE_Y_CONOCIMIENTO'
                and p.id not in 
                ( select relacion_tdc_id  from planificacion_vertical_diploma_relacion_tdc 
                where vertical_diploma_id = $planVertDiplId)
                order by opcion;
                ";
        $resultado = $con->createCommand($query)->queryAll();
        return $resultado;
    } 
    

    // metodos genericos
    private function mostrar_campo_simple($idPlanifVertDipl,$texto_a_mostrar,$titulo,$accion_update,$text_intro_cab ){
        $html ='';      
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $html .= '<div class="card" style="width: 70%; margin-top:20px">';             
                $html .= '<div class="card-header">';
                $html .= '<div class="row">';
                    $html .= '<h5 class=""><b>'.$titulo.'</b></h5>';
                    $html .= '</div>'; 
                $html .= '</div>';                
                $html .= '<div class="card-body">';
                    // inicia row
                   $html .= '<small style="color: #65b2e8">
                                <font size="2">
                                ' . $text_intro_cab . ' 
                                </font>
                            '.$this->modal_generico($idPlanifVertDipl, $texto_a_mostrar,$titulo,$accion_update).'
                            <font size="2"><u>EDITAR</u></font>
                            <hr></small>';                    
                    $html .= '<div class="row">';                    
                        $html .= '<div class="col">'.$texto_a_mostrar.'</div>';                
                        $html .= '</div>';
                    $html .= '</div>';               
                    //******finaliza row
                $html .= '</div>';//fin de card-body
            $html .= '</div>';
        $html .= '</div>';  
        return $html;
    }    
    
    private function mostrar_campo_viene_de($idPlanifVertDipl,$texto_a_mostrar,$titulo,$accion_update,$text_intro)
    {
        $html ='';      
        $html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $html .= '<div class="card" style="width: 70%; margin-top:20px">';             
                $html .= '<div class="card-header">';
                $html .= '<div class="row">';
                    $html .= '<h5 class=""><b>'.$titulo.'</b></h5>';
                    $html .= '</div>'; 
                $html .= '</div>';                
                $html .= '<div class="card-body">';
                    // inicia row
                   $html .= '<small style="color: #898b8d">                                
                                <font size="2">
                                '.$text_intro.' 
                                </font>
                                </small>
                                ';                    
                    $html .= '<div class="row">';                    
                        $html .= '<div class="col">'.$texto_a_mostrar.'</div>';                
                        $html .= '</div>';
                    $html .= '</div>';               
                    //******finaliza row
                $html .= '</div>';//fin de card-body
            $html .= '</div>';
        $html .= '</div>';  
        return $html;
    }   

    private function modal_generico($id, $texto,$titulo,$accion_update){
       
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#modalS2'.$id.'"> 
                    <i class="fas fa-edit"></i>';
        $html .= '</a>';
        $html.= '<div class="modal fade" id="modalS2'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">'.$titulo.'</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                         <hr>';                
                $html .= '<textarea id="editor-text-unidad" name="sumativas" " class="form-control">'.$texto.'</textarea>
                            <script>
                                CKEDITOR.replace("editor-text-unidad", {
                                    customConfig: "/ckeditor_settings/config.js"
                                    })
                            </script>';

                 $html .= '</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_campo_simple_pud_dip('.$id.',\''.$accion_update.'\')">Actualizar</button>
                        </div>
                    </div>
                    </div>
                </div>';
        return $html;
    }   
    

}

?>