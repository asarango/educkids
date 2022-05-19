<?php
namespace backend\models\pudpai;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use backend\models\PlanificacionBloquesUnidad;
use DateTime;

class ServicioAccion extends ActiveRecord{

    private $planUnidadId;
    private $planUnidad;
    public $html = '';

    public function __construct($planUnidadId){
        $this->planUnidadId = $planUnidadId;
        $this->generate_response();
    }

    private function generate_response(){
        $this->html .= '<h5 class=""><b>6.- SERVICIOS COMO ACCIÓN: </b><small class="my-text-small" style="color: #65b2e8">
        (Los servicios de acción son Servicio Directo, Servicio Indirecto, Promoción de una causa, Investigación, etc. )
        </small></h5>';



            $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
                $this->html .= '<div class="card" style="width: 90%; margin-top:20px">';

                $this->html .= '<div class="table table-responsive">';
                $this->html .= '<table class="table table-hover table-condensed table-striped table-bordered">';
                    $this->html .= '<tr>';
                        $this->html .= '<th class="text-center">TIPO DE ACCIÓN</th>';
                        $this->html .= '<th class="text-center">ACTIVIDAD DE ACCIÓN</th>';
                        $this->html .= '<th class="text-center" colspan="4">SITUACIÓN DE APRENDIZAJE</th>';
                    $this->html .= '</tr>';
                    
                    $this->html .= '<tr>';
                    
                    $this->html .= '<th class="text-center" style="background-color: #ab0a3d">';
                    $this->html .= $this->modal_actividades();
                    $this->html .= '</th>';

                    $this->html .= '<th class="text-center" style="background-color: #0a1f8f">';
                    
                    $this->html .= '</th>';
                    
                    $this->html .= '<th class="text-center">Presencial</th>';
                    $this->html .= '<th class="text-center">En Línea</th>';
                    $this->html .= '<th class="text-center">Combinado</th>';                    
                    $this->html .= '<th class="text-center">Remoto</th>';
                    $this->html .= '</tr>';
                    $this->html .= '</thead>';
                    $this->html .= '<tbody id="body-como-accion">';
                    $this->html .= '</tbody>';
                $this->html .= '</table>';
                $this->html .= '</div>';

                $this->html .= '</div>';
            $this->html .= '</div>';
        $this->html .= '</div>';
    }


    private function modal_actividades(){
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#actividadesModal"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                              Tipos y actividades de acción
                              </span>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="actividadesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">TIPOS Y ACTIVIDAD DE ACCIÓN</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      
                    </div>
                    <div class="modal-body">';

                        $html .= '<div id="acciones-disponibles" style="text-align: left"></div>';
                      
                    $html .= '</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }

    

}