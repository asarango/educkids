<?php
namespace backend\models\pudpep;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\PlanificacionBloquesUnidad;
use backend\models\CurriculoMec;
use backend\models\PudPep;

class CriteriosEvaluacion extends ActiveRecord{
    
     private $planUnidadId;
     private $planUnidad;
     public $html;
    
    
     public function __construct($planUnidadId){        
         $this->planUnidadId = $planUnidadId;
         $this->planUnidad = PlanificacionBloquesUnidad::findOne($planUnidadId);
         $this->generate_information();
        
     }

     public function generate_information(){
         $this->html = '<div class="row" style="text-align:start">';
         $this->html .= '<h5><b>2.- PLAN DE UNIDAD</b> (2.1- CRITERIOS DE EVALUACIÓN)</h5>';
         $this->html .= '</div>';
         
        $this->html .= '<div class="row my-text-medium card shadow p-3" style="margin:20px;">';
        $this->html.= $this->modal_criterio();
            $this->html.= '<div class="col-lg-12 col-md-12">';
                $this->html.= '<div class="table table-responsive">';
                $this->html.= $this->tabla();
                $this->html.= '</div>';
            $this->html.= '</div>';
        $this->html.= '</div>';

     }

     private function tabla(){
      $html = '<div class="table table-responsive">';
      $html .= '<table class="table table-striped table-bordered table-hover">';
      $html .= '<thead>';
      $html .= '<tr>';
      $html .= '<th style="text-align: center">CÓDIGO</th>';
      $html .= '<th style="text-align: center">CRITERIOS DE EVALUACIÓN</th>';
      $html .= '<th style="text-align: center">ACCIONES</th>';
      $html .= '</tr>';
      $html .= '</thead>';
      $html .= '<tbody id="div-ce-seleccionados">';
      $html .= '</tbody>';
      $html .= '</table>';
      $html .= '</div>';

      return $html;
     }
     
     private function modal_criterio(){
         
    $html= '<div style="text-align: end">
            <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="ajaxCeDisponibles()">
              <i class="fas fa-plus-square" style="color:green"> Agregar Criterio</i>
            </a>
            </div>


            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Criterios de evaluación disponibles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="div-ce-disponibles">';
                    
    $html.='     </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div>
                </div>
              </div>
            </div>';
    
    return $html;

     }
     
     public function consultar_ce_disponibles(){
        $con = Yii::$app->db;
        $sql = "select 	cm.code as codigo
                ,cm.description as contenido
                from   	planificacion_desagregacion_criterios_evaluacion pce
                    inner join curriculo_mec cm on cm.id = pce.criterio_evaluacion_id 
                where 	pce.bloque_unidad_id = $this->planUnidadId
                    and cm.code not in (select codigo from pud_pep where planificacion_bloque_unidad_id = $this->planUnidadId and tipo = 'criterio' and codigo = cm.code)
                    order by cm.code;";
        $respuesta = $con->createCommand($sql)->queryAll();
        return $respuesta;
     }


    
    
    
    
}

?>