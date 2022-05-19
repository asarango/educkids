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

class Preguntas extends ActiveRecord{

    private $planUnidadId;
    private $planUnidad;
    private $scholarisPeriodoId;
    private $institutoId;
    public $html;


    public function __construct($planUnidadId){
        $this->planUnidadId = $planUnidadId;
        $this->planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $this->scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $this->institutoId = Yii::$app->user->identity->instituto_defecto;

        $this->html = '';

        $this->get_preguntas();
    }


    private function get_preguntas(){        

            $this->html .= '<h5 class=""><b>2.- INDAGACIÓN: ESTABLECIMIENTO DEL PROPÓSITO DE LA UNIDAD</b></h5>';
            $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $this->html .= '<div class="card" style="width: 90%; margin-top:20px">';

            $this->html .= '<div class="card-header">';
                $this->html .= '<h5 class=""><b>2.3.- PREGUNTAS DE INDAGACIÓN</b></h5>';
                $this->html .= '<small style="color: #9e28b5;">(inspiradas en el enunciado de indagación. Su fin es explorar el enunciado en mayor detalle. Ofrecen andamiajes).</small>';
            $this->html .= '</div>';
                
            $this->html .= '<div class="card-body">';
                // inicia row de botones de modales
                $this->html .= '<div class="row">';
                $this->html .= '<div class="col-lg-3 col-md-3 text-center">'.$this->modal_facticas().$this->modal_conceptuales(). $this->modal_debatibles().'</div>';
                // $this->html .= '<div class="col-lg-3 col-md-3 text-center">'..'</div>';
                // $this->html .= '<div class="col-lg-3 col-md-3 text-center">'..'</div>';
                $this->html .= '</div>';
                //******finaliza row de botones de modales
                
                // inicia row detalle de preguntas
                
                $this->html .= '<div id="div-preguntas"></div>';

                //******finaliza row de detalle de preguntas
                
            $this->html .= '</div>';//fin de card-body
            $this->html .= '</div>';
        $this->html .= '</div>';

    }

    private function modal_facticas(){
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#facticasModal"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                              Fácticas
                              </span>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="facticasModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">PREGUNTAS FÁCTICAS</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      
                    </div>
                    <div class="modal-body">
                    
                    <hr>';

                    $html .= '<input type="text" name="facticas" class="form-control" id="input-facticas" 
                                placeholder="Ingrese su pregunta fáctica"
                                onchange="ingresar_pregunta(this,\'facticas\',2)">';                    
                      
                    $html .= '</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }

    private function modal_conceptuales(){
      $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#conceptualModal"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ff9e18"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                              Conceptuales
                              </span>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="conceptualModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">PREGUNTAS CONCEPTUALES</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      
                    </div>
                    <div class="modal-body">
                    
                    <hr>';

                    $html .= '<input type="text" name="facticas" class="form-control" id="input-facticas" 
                                placeholder="Ingrese su pregunta conceptuales"
                                onchange="ingresar_pregunta(this,\'conceptuales\',2)">';                    
                      
                    $html .= '</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    } 
    
    

    private function modal_debatibles(){
      $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#debatibleModal"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d"><i class="fas fa-question-circle" aria-hidden="true"></i> 
                              Debatibles
                              </span>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="debatibleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">PREGUNTAS DEBATIBLES</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      
                    </div>
                    <div class="modal-body">
                    
                    <hr>';

                    $html .= '<input type="text" name="facticas" class="form-control" id="input-facticas" 
                                placeholder="Ingrese su pregunta debatible"
                                onchange="ingresar_pregunta(this,\'debatibles\',2)">';                    
                      
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