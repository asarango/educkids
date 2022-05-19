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
use backend\models\PlanificacionBloquesUnidadSubtitulo;
use backend\models\PudPai;
use DateTime;

class Reflexion extends ActiveRecord{

    private $planUnidadId;
    private $planUnidad;
    private $scholarisPeriodoId;
    private $institutoId;
    private $recursos;
    public $html;


    public function __construct($planUnidadId){
        $this->planUnidadId = $planUnidadId;
        $this->planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);
        $this->scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $this->institutoId = Yii::$app->user->identity->instituto_defecto;

        $this->html = '';

        $this->ingresa_recursos();
        $this->consulta_recursos();
        $this->get_accion();
    }

    private function consulta_recursos(){
        $this->recursos = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $this->planUnidadId,
            'seccion_numero' => 8
        ])->all();
    }

    private function ingresa_recursos(){
        $this->validate_recurso('bibliografico');
        $this->validate_recurso('tecnologico');
        $this->validate_recurso('otros');
    }

    private function validate_recurso($tipo){
        $pudPai = PudPai::find()->where([
            'planificacion_bloque_unidad_id' => $this->planUnidadId,
            'tipo' => $tipo
        ])->one();

        if(!$pudPai){
            $userLog = Yii::$app->user->identity->usuario;
            $fechaHoy = date("Y-m-d H:i:s");
            $model = new PudPai();
            
            $model->planificacion_bloque_unidad_id = $this->planUnidadId;
            $model->seccion_numero = 8;
            $model->tipo = $tipo;
            $model->contenido = '-';
            $model->created_at = $fechaHoy;
            $model->created = $userLog;
            $model->updated_at = $fechaHoy;
            $model->updated = $userLog;
            $model->save();
        }
    }


    private function get_accion(){       
        
            $temas = PlanificacionBloquesUnidadSubtitulo::find()->where([
                'plan_unidad_id' => $this->planUnidadId
            ])
            ->orderBy('orden')
            ->all();

            $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
                $this->html .= '<div class="card" style="width: 90%; margin-top:20px">';
                
                    $this->html .= '<div class="card-header">';                    
                        $this->html .= '<h5 class=""><b>9.- REFLEXIÓN: </b></h5>';                        
                        $this->html .= '<small style="color: #65b2e8">(Consideración de la planificación, el proceso y el impacto de la indagación. En el proceso de reflexión, garantizar dar respuesta a varias de la preguntas planteadas en cada momento.)</small>';
                    $this->html .= '</div>';
                        
                    $this->html .= '<div class="card-body">';

                        $this->html .= $this->modal();
                        $this->html .= '<div class="table table-responsive">';     

                        $this->html .= '<table class="table table-hover table-condensed table-bordered">';          
                                $this->html .= '<div class="table table-responsive">';
                                    $this->html .= '<table class="table table-condensed table-bordered">';
                                        $this->html .= '<thead>';
                                            $this->html .= '<tr style="background-color:#CCC">';
                                                $this->html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">ANTES DE ENSEÑAR LA UNIDAD</th>';
                                                $this->html .= '<th class="text-center" style="background-color: #9e28b5; color: white">MIENTRAS SE ENSEÑA LA UNIDAD</th>';
                                                $this->html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">DESPUÉS DE ENSEÑAR LA UNIDAD</th>';
                                            $this->html .= '</tr>';
                                        $this->html .= '</thead>';
                                        $this->html .= '<tbody id="table-reflexion-seleccionadas"></tbody>';
                                    $this->html .= '</table>';
                                $this->html .= '</div>';

                        $this->html .= '</table>';            
                        $this->html .= '</div>';

                    $this->html .= '</div>';
                $this->html .= '</div>';
            $this->html .= '</div>';                        

    }

    private function modal(){
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#reflexionModal" onclick="show_reflexion_disponibles()"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                              Seleccionar Preguntas
                              </span>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="reflexionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">SELECCIONAR PREGUNTAS</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                      
                    </div>'; //FIN DE MODAL -HEADER

                    $html .= '<div class="modal-body">'; //Inicio de modal-body
                
                        $html .= '<div class="table table-responsive">';
                            $html .= '<table class="table table-condensed table-bordered">';
                                $html .= '<thead>';
                                    $html .= '<tr>';
                                        $html .= '<th class="text-center" style="background-color: #0a1f8f; color: white">ANTES DE ENSEÑAR LA UNIDAD</th>';
                                        $html .= '<th class="text-center" style="background-color: #9e28b5; color: white">MIENTRAS SE ENSEÑA LA UNIDAD</th>';
                                        $html .= '<th class="text-center" style="background-color: #ab0a3d; color: white">DESPUÉS DE ENSEÑAR LA UNIDAD</th>';
                                    $html .= '</tr>';
                                $html .= '</thead>';
                                $html .= '<tbody id="table-reflexion-disponibles"></tbody>';
                            $html .= '</table>';
                        $html .= '</div>';

                      
                    $html .= '</div>';// fin de modal-body

                    $html .= '<div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                      
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }

       
   
}