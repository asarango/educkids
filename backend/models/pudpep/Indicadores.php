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

class Indicadores extends ActiveRecord{
    
    private $planUnidad;
    private $planUnidadId;
    private $nivelId;
    public $html;

    public function __construct($planUnidadId){
        $this->planUnidadId = $planUnidadId;
        $this->planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $this->nivelId = $this->planUnidad->planCabecera->opCourseTemplate->curriculo_nivel_id;

        $this->generate_information();
    }

    public function generate_information(){
        $this->html = '<h5><b>2.- PLAN DE UNIDAD</b> (2.2.- Indicadores de la Evaluación de la Unidad)</h5>';

        $this->html .= '<div class="card shadow p-3" style="margin-bottom: 20px">';
        $this->html .= $this->modal_selecciona();
        $this->html.= '<div class="table table-responsive">';
        $this->html .= $this->tabla();
        $this->html .= '</div>';
        $this->html .= '</div>';
    }

    private function tabla(){
      $html = '<div class="" id="div-indicadores-seleccionados">';
      $html .= '</div>';

      return $html;
     }


    private function modal_selecciona(){

        $planUnidad = PlanificacionBloquesUnidad::findOne($this->planUnidad->id);
      
          $cabeceraEstado = $planUnidad->planCabecera->estado;
          $bloqueIsOpen = $planUnidad->is_open;
          $bloqueEsConfigurado = $planUnidad->settings_status;
  
          $helperAprobacion = new Condiciones();
          $validaAprobacion = $helperAprobacion->aprobacion_planificacion($cabeceraEstado, $bloqueIsOpen, $bloqueEsConfigurado);
  
          if($validaAprobacion){
            $html = '<div style="text-align: end">
                    <a type="button" class="" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="ajaxIndicadoresDisponibles('.$this->planUnidad->id.')">
                    <i class="fas fa-plus-square" style="color: #65b2e8"> Elegir Indicadores</i>
                    </a>
                    </div>
          
          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Indicadores de la unidad</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="div-indicadores-disponibles">
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                
                </div>
              </div>
            </div>
          </div>
          ';
          }else{
            $html = '<div style="text-align: end; color: #ab0a3d"><b>¡NO SE PUEDE ELEGIR INDICADORES PORQUE SU PLANIFICACIÓN SE ENCUENTRA APROBADA, EN CONFIGURACIÓN O EN PROCESO DE REVISIÓN POR JEFES DE ÁREA Y/O CORDINADORES!</b></div>';
          }
  
          
  
          return $html;
      }

      public function consulta_indicadores_disponibles(){
        $con = Yii::$app->db;
        $sql = "select 	curmec.id,curmec.code as codigo,curmec.description as contenido
        from 	curriculo_mec curmec 
        where	curmec.reference_type = 'indicador'
            and curmec.belongs_to in (select 	cm.code 
                            from 	planificacion_desagregacion_criterios_evaluacion dce
                                inner join curriculo_mec cm on cm.id = dce.criterio_evaluacion_id 
                            where 	dce.bloque_unidad_id = $this->planUnidadId)
            and curmec.code not in(
              select codigo from pud_pep p 
                        where planificacion_bloque_unidad_id= $this->planUnidadId and tipo = 'indicador' 
                            and codigo = curmec.code
            )
        order by curmec.code;";
      
        $respuesta = $con->createCommand($sql)->queryAll();
        return $respuesta;                
      }




}