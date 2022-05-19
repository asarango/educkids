<?php
namespace backend\models\pudpep;

use backend\models\helpers\Condiciones;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use backend\models\PlanificacionBloquesUnidad;

class DatosInformativos extends ActiveRecord{

    private $planUnidadId;
    private $planUnidad;
    public $arrayData;
    private $scholarisPeriodoId;
    private $institutoId;
    public  $html;


    public function __construct($planUnidadId){
        $this->planUnidadId = $planUnidadId;
        $this->planUnidad = PlanificacionBloquesUnidad::findOne($this->planUnidadId);

        $this->scholarisPeriodoId   = Yii::$app->user->identity->periodo_id;
        $this->institutoId          = Yii::$app->user->identity->instituto_defecto;

        //$this->generate_information();
    }
    
    public function generate_information(){
        $this->html = '<h5><b>1.- DATOS INFORMATIVOS</b></h5>';
        
        $this->html .= '<div class="card shadow p-5" style="margin-bottom: 20px">';
        $this->html .= '<div class="table table-responsive">';
        $this->html .= '<table class="table table-bordered table-striped table-hover">';
        $this->html .= '<thead>';
        $this->html .= '<tr style="background-color: #0a1f8f; color: #ffffff">';
        $this->html .= '<td width="35%" align="center"><b>DOCENTES ASIGANADOS:</b></td>';
        $this->html .= '<td align="center"><b>ASIGNATURA: </b></td>';
        $this->html .= '<td align="center"><b>GRADO:</b></td>';
        $this->html .= '<td align="center"><b>PARALELOS:</b></td>';
        $this->html .= '</tr>';

        $this->html .= '<tr>';
        //para docentes
        $docentes = $this->consulta_docentes();
        $this->html .= '<td>';
        foreach($docentes as $docente){
            
            $this->html .= $docente['docente'];    
            $this->html .= ' - ';        
        }
        $this->html .= '</td>';
        //fin de docentes

        $this->html .= '<td valign="middle">'.$this->planUnidad->planCabecera->scholarisMateria->name.'</td>';//Para asignatura
        $this->html .= '<td valign="middle">'.$this->planUnidad->planCabecera->opCourseTemplate->name.'</td>';//Para curso


         //para paralelos
         $docentes = $this->consulta_docentes();
         $this->html .= '<td valign="middle">';
         foreach($docentes as $docente){
             $this->html .= ' | ';
             $this->html .= $docente['paralelo'];            
         }
         $this->html .= '</td>';
         //fin de paralelos
        $this->html .= '</tr>';
        
        $this->html .= '<tr>';
        $this->html .= '<td rowspan="2" valign="middle">';
        $this->html .= '<b>UNIDAD Nº: </b>'.$this->planUnidad->curriculoBloque->code;
        $this->html .= '</td>';

        $this->html .= '<td style="background-color: #ff9e18; color: #ab0a3d">';
        $this->html .= '<b>TÍTULO DE LA UNIDAD:</b> '.$this->planUnidad->unit_title;
        $this->html .= '</td>';

        //para consultar fechas
        
        $this->html .= '<td style="background-color: #0a1f8f; color: #ffffff"><b>FECHA DE COMIENZO DE LA UNIDAD:</b></td>';
        $this->html .= '<td style="background-color: #0a1f8f; color: #ffffff"><b>FECHA QUE TERMINA LA UNIDAD:</b></td>';
         //fin de cnsulta de fechas
        $this->html .= '</tr>';

        $this->html .= '<tr>';
        $this->html .= '<td><b>EJES DE LA UNIDAD:</b>';
        $this->html .= '<u>';
        $this->html .= '<li>Eje 1</li>';
        $this->html .= '<li>Eje 2</li>';
        $this->html .= '<li>Eje 3</li>';
        $this->html .= '<li>Eje 4</li>';
        $this->html .= '</u>';
        $this->html .= '</td>';
        
        //respuesta de fechas
        $fechas = $this->consulta_fechas_bloque();
        $this->html .= '<td valign="middle" align="center">'.$fechas['desde'].'</td>';
        $this->html .= '<td valign="middle" align="center">'.$fechas['hasta'].'</td>';
         //fin de respuesta de fechas

        $this->html .= '</tr>';
        

        $this->html .= '</thead>';
        $this->html .= '</table>';        
        $this->html .= '</div>';        
        $this->html .= '</div>';        

        $this->html .= $this->objetivos_de_unidad();
    }


    private function objetivos_de_unidad(){

        $html = '<div class="card shadow p-3">';
        
        $html .= '<b>OBJETIVOS DE LA UNIDAD</b>';
        $html .= $this->modal_selecciona_objetivos();

        $html .= $this->tabla_objetivos_seleccionados();

        $html .= '</div>';

        return $html;
    }

    private function tabla_objetivos_seleccionados(){
      $html = '<div class="table table-responsive">';
      $html .= '<table class="table table-striped table-bordered table-hover">';
      $html .= '<thead>';
      $html .= '<tr>';
      $html .= '<th style="text-align: center">CÓDIGO</th>';
      $html .= '<th style="text-align: center">OBJETIVOS DE LA UNIDAD</th>';
      $html .= '<th style="text-align: center">ACCIONES</th>';
      $html .= '</tr>';
      $html .= '</thead>';
      $html .= '<tbody id="div-objetivos-seleccionados">';
      $html .= '</tbody>';
      $html .= '</table>';
      $html .= '</div>';

      return $html;
    }

    private function modal_selecciona_objetivos(){

      $planUnidad = PlanificacionBloquesUnidad::findOne($this->planUnidad->id);

        $cabeceraEstado = $planUnidad->planCabecera->estado;
        $bloqueIsOpen = $planUnidad->is_open;
        $bloqueEsConfigurado = $planUnidad->settings_status;

        $helperAprobacion = new Condiciones();
        $validaAprobacion = $helperAprobacion->aprobacion_planificacion($cabeceraEstado, $bloqueIsOpen, $bloqueEsConfigurado);

        if($validaAprobacion){
          $html = '<div style="text-align: end">
        <a type="button" class="" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="ajaxObjetivosDisponibles('.$this->planUnidad->id.')">
        <i class="fas fa-plus-square" style="color: green"> Elegir objetivos de la unidad</i>
        </a>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Objetivos de la unidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" id="div-objetivos-disponibles">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                
              </div>
            </div>
          </div>
        </div>
        ';
        }else{
          $html = '<div style="text-align: end; color: #ab0a3d"><b>¡NO SE PUEDE ELEGIR OBJETIVOS PORQUE SU PLANIFICACIÓN SE ENCUENTRA APROBADA, EN CONFIGURACIÓN O EN PROCESO DE REVISIÓN POR JEFES DE ÁREA Y/O CORDINADORES!</b></div>';
        }

        

        return $html;
    }


    public function consulta_docentes(){

        $templateId = $this->planUnidad->planCabecera->op_course_template_id;
        $materiaId = $this->planUnidad->planCabecera->scholaris_materia_id;

        $con = Yii::$app->db;
        $query = "select 	par.name as paralelo
                            ,cla.id 
                            ,concat(f.x_first_name,' ', f.last_name) as docente  
                            ,cla.tipo_usu_bloque
                    from	op_course_template t 
                            inner join op_course c on c.x_template_id = t.id 
                            inner join scholaris_clase cla on cla.idcurso = c.id 
                            inner join scholaris_periodo sp on sp.codigo = cla.periodo_scholaris 
                            inner join op_course_paralelo par on par.id = cla.paralelo_id 
                            inner join op_faculty f on f.id = cla.idprofesor 
                    where 	t.id = $templateId
                            and sp.id = $this->scholarisPeriodoId
                            and cla.idmateria = $materiaId
                            and c.x_institute = $this->institutoId
                    order by par.name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;

    }

    public function consulta_fechas_bloque(){
        $orden = $this->planUnidad->curriculoBloque->code;
        $docentes   = $this->consulta_docentes();
        $uso        = $docentes[0]['tipo_usu_bloque'];
        $materiaId  = $this->planUnidad->planCabecera->scholaris_materia_id;
        
        $con = Yii::$app->db;
        $query = "select 	ba.desde 
                            ,ba.hasta 
                    from 	scholaris_bloque_actividad ba
                            inner join scholaris_periodo sp on sp.codigo = ba.scholaris_periodo_codigo 
                    where 	ba.orden = $orden
                            and ba.tipo_uso = '$uso'
                            and instituto_id = $this->institutoId
                            and sp.id = $this->scholarisPeriodoId;";
        $res = $con->createCommand($query)->queryOne();
        return $res;

    }


}

?>