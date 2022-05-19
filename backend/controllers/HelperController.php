<?php
namespace backend\controllers;

use backend\models\helpers\Condiciones;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\PudPep;
use backend\models\pudpep\CriteriosEvaluacion;
use backend\models\pudpep\Indicadores;
use backend\models\PlanificacionOpciones;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter; 

class HelperController extends Controller{


    /**
     * Undocumented function
     * Ajax para seleccionar objetivos disponibles
     *
     * @return void
     */
    public function actionAjaxObjetivosDisponibles(){
        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];
        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $cabeceraId = $planUnidad->plan_cabecera_id;

        $objetivos = $this->consulta_objetivos_disponibles($cabeceraId, $planUnidadId);

        $html = '<table class="table table-bordered table-striped table-hover my-text-small">';
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<th>CÓDIGO</th>";
        $html .= "<th>OBJETIVO</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        foreach($objetivos as $objetivo){
            $html .= '<tr>';
            $html .= '<td>'.$objetivo['codigo'].'</td>';
            $html .= '<td>'.$objetivo['contenido'].'</td>';
            $html .= '<td>';
            $html .= '<a href="#" onclick="ajaxInsertarCriterio(\'objetivos_generales\', \''.$objetivo['codigo'].'\', \''.$objetivo['contenido'].'\')"><i class="fas fa-cart-plus" style="color: #9e28b5"> Seleccionar</i></a>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= "</tbody>";
        $html .= "</table>";

        return $html;

    }

    /**
     * Metodo que devuelve los objetivos disponibles
     *
     * @param [type] $cabeceraId
     * @param [type] $planUnidadId
     * @return void
     */
    private function consulta_objetivos_disponibles($cabeceraId, $planUnidadId){
        $con = Yii::$app->db;
        $query = "select 	id, desagregacion_cabecera_id, tipo, codigo, contenido, estado 
        from 	pca_detalle pca
        where 	pca.tipo = 'objetivos_generales'
                and pca.desagregacion_cabecera_id = $cabeceraId
                and pca.codigo not in (select 	codigo 
        from 	pud_pep
        where 	tipo = 'objetivos_generales'
                and planificacion_bloque_unidad_id = $planUnidadId
                and codigo = pca.codigo)
        order by codigo;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    /**
     * Ajax que muestra los objetivos disponibles
     *
     * @return void
     */
    public function actionAjaxObjetivosSeleccionados(){
        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];
        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $cabeceraEstado = $planUnidad->planCabecera->estado;
        $bloqueIsOpen = $planUnidad->is_open;
        $bloqueEsConfigurado = $planUnidad->settings_status;

        $helperAprobacion = new Condiciones();
        $validaAprobacion = $helperAprobacion->aprobacion_planificacion($cabeceraEstado, $bloqueIsOpen, $bloqueEsConfigurado);

        $objetivos = PudPep::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'tipo' => 'objetivos_generales'
        ])->all();

        $html = '';
        foreach($objetivos as $objetivo){
            $html .= '<tr>';
            $html .= '<td>'.$objetivo->codigo.'</td>';
            $html .= '<td>'.$objetivo->contenido.'</td>';
            $html .= '<td>';
            if($validaAprobacion == true){
                $html .= '<a href="#" onclick="ajaxDeleteOption('.$objetivo->id.', \'objetivos_generales\')"><i class="fas fa-trash-alt" style="color: #ab0a3d"> Eliminar</i></a>';
            }
            
            $html .= '</td>';
            $html .= '</tr>';
        }
        return $html;
    }


    /**
     * Ajax para Tomar los CE deisponibles
     *
     * @return void
     */
    public function actionAjaxCeDisponibles(){

        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];

        $ce = new CriteriosEvaluacion($planUnidadId);
        $criterios = $ce->consultar_ce_disponibles();

        $html = '<table class="table table-bordered table-striped table-hover my-text-small">';
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<th>CÓDIGO</th>";
        $html .= "<th>CRITERIOS DE EVALUACIÓN</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        foreach($criterios as $criterio){
            $html .= '<tr>';
            $html .= '<td>'.$criterio['codigo'].'</td>';
            $html .= '<td>'.$criterio['contenido'].'</td>';
            $html .= '<td>';
            $html .= '<a href="#" onclick="ajaxInsertarCriterio(\'criterio\', \''.$criterio['codigo'].'\', \''.$criterio['contenido'].'\')"><i class="fas fa-cart-plus" style="color: #9e28b5"> Seleccionar</i></a>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= "</tbody>";
        $html .= "</table>";

        return $html;
    }

    /**
     * Ajas para mostrar los CE seleccionado
     */
    public function actionAjaxCeSeleccionados(){
        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];
        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $cabeceraEstado = $planUnidad->planCabecera->estado;
        $bloqueIsOpen = $planUnidad->is_open;
        $bloqueEsConfigurado = $planUnidad->settings_status;

        $helperAprobacion = new Condiciones();
        $validaAprobacion = $helperAprobacion->aprobacion_planificacion($cabeceraEstado, $bloqueIsOpen, $bloqueEsConfigurado);

        $criterios = PudPep::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'tipo' => 'criterio'
        ])
        ->orderBy('codigo')
        ->all();

        $html = '';
        foreach($criterios as $criterio){
            $html .= '<tr>';
            $html .= '<td>'.$criterio->codigo.'</td>';
            $html .= '<td>'.$criterio->contenido.'</td>';
            $html .= '<td>';
            if($validaAprobacion == true){
                $html .= '<a href="#" onclick="ajaxDeleteOption('.$criterio->id.', \'criterios_evaluacion\')"><i class="fas fa-trash-alt" style="color: #ab0a3d"> Eliminar</i></a>';
            }
            
            $html .= '</td>';
            $html .= '</tr>';
        }
        return $html;
    }


    /**
     * Ajax para mostrar los INDICADORES seleccionado
     */
    public function actionAjaxIndicadoresDisponibles(){
        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];

        $indi = new Indicadores($planUnidadId);
        $indicadores = $indi->consulta_indicadores_disponibles();

        $html = '<table class="table table-bordered table-striped table-hover my-text-small">';
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<th>CÓDIGO</th>";
        $html .= "<th>INDICADORES DE EVALUACIÓN DE LA UNIDAD</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        foreach($indicadores as $indicador){
            $html .= '<tr>';
            $html .= '<td>'.$indicador['codigo'].'</td>';
            $html .= '<td>'.$indicador['contenido'].'</td>';
            $html .= '<td>';
            $html .= '<a href="#" onclick="ajaxInsertarCriterio(\'indicador\', \''.$indicador['codigo'].'\', \''.$indicador['contenido'].'\')"><i class="fas fa-cart-plus" style="color: #9e28b5"> Seleccionar</i></a>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= "</tbody>";
        $html .= "</table>";

        return $html;
    }

    /**
     * ajax para mostrar indicadores seleccionados
     */
    public function actionAjaxIndicadoresSeleccionados(){
        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];
        $seccion = $_GET['seccion'];
        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);
        
        
        $cabeceraEstado = $planUnidad->planCabecera->estado;
        $bloqueIsOpen = $planUnidad->is_open;
        $bloqueEsConfigurado = $planUnidad->settings_status;

        $helperAprobacion = new Condiciones();
        $validaAprobacion = $helperAprobacion->aprobacion_planificacion($cabeceraEstado, $bloqueIsOpen, $bloqueEsConfigurado);

        $indicadores = PudPep::find()->where([
            'planificacion_bloque_unidad_id' => $planUnidadId,
            'tipo' => 'indicador'
        ])
        ->orderBy('codigo')
        ->all();
        
        $html = '';
        foreach($indicadores as $indicador){
$html .= '
    <div class="accordion accordion-flush" id="accordionFlushExample">
        <div class="accordion-item">
              <h6 class="accordion-header" id="flush-headingOne">
                <button onclick="ajaxDetalle('.$indicador->id.')" class="accordion-button border btn-outline-primary collapsed my-text-small" type="button" data-bs-toggle="collapse" data-bs-target="#acordion'.$indicador->id.'" aria-expanded="false" aria-controls="flush-collapseOne">
                    <u><b>'.$indicador->codigo.'</b></u>'.$indicador->contenido.'
                </button>
              </h6>
            <div id="acordion'.$indicador->id.'" class="accordion-collapse collapse border" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="row" style="text-align:center">';
                     
$html.='                <div class="col-lg-3 col-md-3" style="text-align:center;background-color: #0a1f8f">';
                            if($validaAprobacion == false){
                                
                            }else{
                                $html.= '<a><i class="fas fa-pencil-alt" style="color: white"> Desagregar Indicador</i></a>';
                            }
$html.='                </div>                        
                        <div class="col-lg-3 col-md-3" style="text-align:center;background-color: #ab0a3d">';
                            if($validaAprobacion == false){
                                
                            }else{
                                $html.= '<a href="#" onclick="ajaxDeleteIndicador('.$indicador->id.')"><i class="fas fa-trash-alt" style="color: white"> Eiminar Indicador</i></a>';
                            }

                            
$html.='                </div>                        
                    </div>


                    <div class="row" style="" >
                        <div class="table table-responsive" > 
                            <table class="table table-hover table-bordered table-striped my-text-small">
                                <thead>
                                    <tr>';
                                    if($validaAprobacion == false){
                                        $html.='<td style="width:300px">DESTREZAS</td>
                                        <td style="width:300px">ACTIVIDAD DE APRENDIZAJE</td>
                                        <td style="width:200px">RECURSO</td>
                                        <td style="width:300px">TEC.INSTR. DE EVALUACION</td>';
                                    }else{
                                        $html.='<td style="width:300px">DESTREZAS'.$this->modal_destreza($indicador->id,$indicador->codigo,$planUnidadId).'</td>
                                        <td style="width:300px">ACTIVIDAD DE APRENDIZAJE'.$this->modal_ada($indicador->id,$seccion,$planUnidad->id).'</td>
                                        <td style="width:200px">RECURSO</td>
                                        <td style="width:300px">TEC.INSTR. DE EVALUACION</td>';
                                    }
                                        
$html.='                            </tr>
                                </thead>
                                <tbody id="div-detalle-indicador-p'.$indicador->id.'">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';

            if($validaAprobacion == true){
//                $html .= '<a href="#" onclick="ajaxDeleteOption('.$indicador->id.', \'indicadores\')"><i class="fas fa-trash-alt" style="color: #ab0a3d">_Eliminar</i></a>';
            }
            
            $html .= '</td>';
            $html .= '</tr>';
        }
        return $html;
    }
    
    
    private function modal_destreza($indicadorId,$indicadorCode,$planUnidadId){
        
$html='     <a type="button" data-bs-toggle="modal" '
                . 'onclick="showDestrezasDisponibles('.$planUnidadId.','.$indicadorId.',\''.$indicadorCode.'\')" '
                . 'data-bs-target="#modalDes'.$indicadorId.'">
              <i class="fas fa-plus-square"></i>
            </a>

            <!-- Modal -->
            <div class="modal fade" id="modalDes'.$indicadorId.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Destrezas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">';
                            $html.= '<div class=""table table-responsive>
                                        <table class="table table-hover table-striped">
                                            <thead style="background-color:#ff9e18">
                                                <tr>
                                                    <th>CÓDIGO</th>
                                                    <th>DESTREZA</th>
                                                    <th>ACCIÓN</th>
                                                </tr>
                                            </thead>
                                            <tbody id="div-destrezas-disponibles'.$indicadorId.'">
                                            </tbody>
                                        </table>
                                    </div>';
$html.='         </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>';
        return $html;
    }
    

    /**
     * PARA PRESENTAR MODAL CON ACTIVIDADES DE APRENDIZAJE DISPONIBLES
     *
     * @param [type] $indicadorId
     * @param [type] $seccion
     * @param [type] $planBloqueId
     * @return void
     */
        private function modal_ada($indicadorId,$seccion,$planBloqueId){
        
$html='     <a type="button" data-bs-toggle="modal" data-bs-target="#modalAda'.$indicadorId.'">
              <i class="fas fa-plus-square"></i>
            </a>

            <!-- Modal -->
            <div class="modal fade" id="modalAda'.$indicadorId.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar actividades-recursos y técnicas del indicador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">';

$html.='            
                    <input onkeypress="showDetallesDisponibles(\''.$seccion.'\',this, '.$planBloqueId.', '.$indicadorId.')" 
                        type="text" name="buscador" id="buscar-recursos" class="form-control" placeholder="Buscar...">
                    <div class="table table-responsive">
                        <table class="table table-condensed table-hover table-striped">
                            <thead style="background-color:#ff9e18">
                                <tr>
                                    <th>TIPO DE RECURSO</th>
                                    <th>CATEGORIA</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody id="div-ada-disponibles'.$indicadorId.'">';           
$html.='                    </tbody>
                        </table>
                    <div>';
$html.='         </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>';
        return $html;
    }
    
  
    /***
     * METODO QUE MUESTRA LOS DETALLES DISPONIBLES DEL PUNTO 2 DEPENDIENDO DEL INDICADO
     */
    public function actionAjaxShowDetallesDisponibles(){
        $seccion            = $_GET['seccion'];
        $word               = $_GET['word'];
        $bloqueUnidadId     = $_GET['planificacion_bloque_unidad_id'];
        $indicadorId        = $_GET['indicador_id'];
              
        
//Muestra contenidos de aprendizajes, recursos, tecnicas e instrumentos para escoger segun cada INDICADOR
        $opciones = $this->select_opciones($seccion,$word,$bloqueUnidadId,$indicadorId);
        
//  
        
        $html = '';
       
         foreach($opciones as $opcion){
            $html.= '<tr>';
                $html.= '<td>'.$opcion['tipo'] .'</td>';
                $html.= '<td>'.$opcion['categoria'] .'</td>';
                $html.= '<td>'.$opcion['opcion'] .'</td>';
                $html.= '<td>
                    
                            <a type="button" 
                                onclick="ajaxInsertarContenido(\''.$opcion['tipo'].'\',\''.$opcion['id'].'\',\''.$opcion['opcion'].'\','.$indicadorId.')">
                                <i class="fas fa-cart-plus" style="color:#9e28b5">Seleccionar</i>
                            </a>
                        </td>';
            $html.= '</tr>';
        }

        return $html;
    }
    
    public function actionAjaxShowDestrezasDisponibles(){
        $planUnidadId = $_GET['planificacion_bloque_unidad_id'];
        $indicadorId = $_GET['indicador_id'];
        $indicadorCode = $_GET['codigo'];
        
        $destrezas = $this->select_destrezas($planUnidadId,$indicadorCode);
        
//        print_r($destrezas);
//        die();

//      <tbody id="div-destrezas-disponibles">
        $html = '';
        
        foreach($destrezas as $destreza){
        $html.='<tr>';  
            $html.= '<td>'.$destreza['codigo'].'</td>';
            $html.= '<td>'.$destreza['contenido'].'</td>';
            $html.= '<td>
                            <a type="button" 
                                onclick="ajaxInsertarDestreza(\'destreza\',\''.$destreza['codigo'].'\',\''.$destreza['contenido'].'\','.$indicadorId.',\''.$indicadorCode.'\')">
                                <i class="fas fa-cart-plus" style="color:#9e28b5">Seleccionar</i>
                            </a>
                    </td>';
        $html.='</tr>';
        }
        
        return $html;
        
        
    }


    private function select_opciones($seccion,$word,$planBloqueId,$indicadorId){
        
        $con = Yii::$app->db;
        $sql ="select po.tipo,po.categoria,po.opcion ,po.id
                from planificacion_opciones po 
                where po.estado = true
                        and po.seccion = '$seccion'
                        and po.id not in(select cast(pud.codigo as int)
                                         from pud_pep pud
                                         where pud.tipo = po.tipo
                                            and planificacion_bloque_unidad_id = $planBloqueId
                                            and cast(pud.codigo as int) = po.id
                                            and pud.pertenece_indicador_id = $indicadorId)
                and (po.opcion ilike '%$word%' or po.tipo ilike '%$word%') "
                . "and po.tipo <> 'EJE-TRANSVERSAL'";

        $respuesta = $con->createCommand($sql)->queryAll();
        return $respuesta;  
    }
    
//    Sentencia que muestran destrezas disponibles para INDICADOR ESPECÍFICO
    private function select_destrezas($planUnidadId,$indicadorCode){
        
//        echo $planUnidadId;
//        echo $indicadorCode;
//        die();
        
        $con = Yii::$app->db;
        $sql ="select 	cmd.code as codigo
		,des.opcion_desagregacion 
		,des.content as contenido 
from 	planificacion_desagregacion_criterios_destreza des
		inner join planificacion_desagregacion_criterios_evaluacion cri on cri.id = des.desagregacion_evaluacion_id 		
		inner join curriculo_mec cm on cm.id = cri.criterio_evaluacion_id 
		inner join curriculo_mec cmd on cmd.id = des.curriculo_destreza_id 
where 	cri.bloque_unidad_id = $planUnidadId
		and cm.code = (select 	mec.belongs_to 
						from 	pud_pep pp 
								inner join curriculo_mec mec on mec.code = pp.codigo 
						where 	pp.planificacion_bloque_unidad_id = $planUnidadId
								and pp.tipo = 'indicador'
								and pp.codigo = '$indicadorCode') 
		and cmd.code not in (select codigo from pud_pep where planificacion_bloque_unidad_id = $planUnidadId and codigo = cmd.code);";
        
//        echo $sql;
//        die();

        $respuesta = $con->createCommand($sql)->queryAll();
        return $respuesta;  
    }
             
    
/* 
    *Método para mostrar detalles del indicador 
    (destrezas,ada,recursos,tecnicas e instrumentos de evaluación)
*/
    private function tabla_detalle($indicadorId){
        $html = '';
        $html .= '<div class="table table-responsive" > 
                    <table class="table table-hover table-striped my-text-small">
                        <thead>
                            <tr>
                                <td>DESTREZAS</td>
                                <td>ADA</td>
                                <td>RECURSOS</td>
                                <td>TEC.INSTR. DE EVALUACIÓN</td>
                            </tr>
                        </thead>
                        <tbody id="div-detalle-indicador'.$indicadorId.'">
                        </tbody>
                     </table>
                 </div>';
        
        return $html;
    }

    /**
     * ajax para mostrar detalle de indicadores en la modal
     */

     public function actionAjaxDetalle(){
        $indicador     = $_GET['indicador_id'];
        $seccion = $_GET['seccion'];
        $planUnidadId  = $_GET['planificacion_bloque_unidad_id'];
         
        $planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $cabeceraEstado = $planUnidad->planCabecera->estado;
        $bloqueIsOpen = $planUnidad->is_open;
        $bloqueEsConfigurado = $planUnidad->settings_status;

        $helperAprobacion = new Condiciones();
        $validaAprobacion = $helperAprobacion->aprobacion_planificacion($cabeceraEstado, $bloqueIsOpen, $bloqueEsConfigurado);  
         
         
         $detalle = PudPep::find()->where([
             'planificacion_bloque_unidad_id' => $planUnidadId,
             'pertenece_indicador_id' => $indicador
         ])
         ->orderBy('tipo')
         ->all();
        
         $html = '';
         $html .= '<tr>';
         $html .= '<td style="text-align:start">';
         $html .= '<ul>';
         foreach($detalle as $destreza){            
            if($destreza->tipo == 'destreza'){
                
                if($validaAprobacion == false){
                    $html.= '<p>'
                                . '<b>'.$destreza->codigo.'</b> '.$destreza->contenido
                            .'</p>';
                }else{
                    $html .= '<li>'
                                .'<a type="button" onclick="ajaxDeleteContenido('.$destreza->id.', '.$destreza->pertenece_indicador_id.')"><i class="fas fa-trash-alt" style="color: #ab0a3d"></i></a>'
                                .'&nbsp;<strong>'.$destreza->codigo.'</strong> '.$destreza->contenido.''
                            . '</li>';
                }
                
            }                            
         }
         $html .= '</ul>';
         $html .='</td>';
         $html .='<td>';
            $html .='<ul>';
            foreach($detalle as $ada){
                if($ada->tipo == 'ACTV-APRENDIZAJE'){
                    
                    if($validaAprobacion == false){
                        $html.='<p>
                                '.$ada->contenido.'
                                </p>';
                    }else{
                    $html.= '<li>
                                    <a type="button" onclick="ajaxDeleteContenido('.$ada->id.', '.$ada->pertenece_indicador_id.')">
                                        <i class="fas fa-trash-alt" style="color: #ab0a3d"></i>
                                        '.$ada->contenido.'
                                    </a>
                                 </li>';
                    }

                }
            }
            $html .='</ul>';
         $html .='</td>';
         
         $html.= '<td>';
                 $html.= '<ul>';
                    foreach($detalle as $rec){
                        if($rec->tipo == 'RECURSO'){
                            
                            if($validaAprobacion == false){
                                $html.= '<p>
                                        '.$rec->contenido.'
                                        </p>';
                            }else{
                            $html.= '<li>
                                    <a type="button" onclick="ajaxDeleteContenido('.$rec->id.', '.$rec->pertenece_indicador_id.')">
                                        <i class="fas fa-trash-alt" style="color: #ab0a3d"></i>
                                        '.$rec->contenido.'
                                    </a>
                                    </li>'; 
                            }

                        }
                    }
                 $html.= '</ul>';
         $html.= '</td>';
         
         $html.= '<td>';
                 $html.= '<ul>';
                    foreach($detalle as $tec){
                        if($tec->tipo == 'TECNICA-INSTRUMENTO'){
                            
                            if($validaAprobacion == false){
                                $html.='<p>
                                            '.$tec->contenido.'
                                        </p>';
                            }else{
                                $html.= '<li>
                                    <a type="button" onclick="ajaxDeleteContenido('.$tec->id.', '.$tec->pertenece_indicador_id.')">
                                        <i class="fas fa-trash-alt" style="color: #ab0a3d"></i>
                                        '.$tec->contenido.'
                                    </a>
                                    </li>'; 
                            }
                            
                        }
                    }
                 $html.= '</ul>';
         $html.= '</td>';
         
         $html .= '</tr>';
         
         
         return $html;

     }
    
    

}
?>