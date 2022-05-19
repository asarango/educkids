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
use backend\models\PlanificacionVerticalPaiDescriptores;
use backend\models\PudPai;
use DateTime;

class Ensenara extends ActiveRecord{

    private $planUnidadId;
    private $planUnidad;
    private $habilidades;
    private $scholarisPeriodoId;
    private $institutoId;
    private $enfoques;
    public $html;


    public function __construct($planUnidadId){
        $this->planUnidadId = $planUnidadId;
        $this->planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $this->scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $this->institutoId = Yii::$app->user->identity->instituto_defecto;

        $this->html = '';
        $this->ingresa_ensenara();

        $this->enfoques = PudPai::find()->where([
          'seccion_numero' => 4,
          'planificacion_bloque_unidad_id' => $planUnidadId
        ])->all();

        $this->get_grupo();
    }

    private function ingresa_ensenara(){

      $this->validate_ensenara('ensenara_comunicacion');
      $this->validate_ensenara('ensenara_sociales');
      $this->validate_ensenara('ensenara_autogestion');
      $this->validate_ensenara('ensenara_investigacion');
      $this->validate_ensenara('ensenara_pensamiento');

    }

    private function validate_ensenara($tipo){
      $usuarioLog = Yii::$app->user->identity->usuario;
      $fechaHoy = date('Y-m-d H:i:s');
      
      $pudPai = PudPai::find()->where([
        'planificacion_bloque_unidad_id' => $this->planUnidadId,
        'tipo' => $tipo
      ])->one();

        if(!$pudPai){
          $model = new PudPai();
          $model->planificacion_bloque_unidad_id = $this->planUnidadId;
          $model->seccion_numero = 4;
          $model->tipo = $tipo;
          $model->contenido = 'sin contenido';
          $model->created = $usuarioLog;
          $model->created_at = $fechaHoy;
          $model->updated = $usuarioLog;
          $model->updated_at = $fechaHoy;
          $model->save();
        }

    }



    private function get_grupo(){        

      $this->html .= '<h5 class=""><b>4.- ENFOQUES DE APRENDIZAJE </b></h5>';                

      $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $this->html .= '<div class="card" style="width: 95%; margin-top:20px">';

            $this->html .= '<div class="card-header">';
                $this->html .= '<h5 class=""><b>4.4.- ¿COMO ENSEÑARÁ? </b></h5>'.$this->modal();                
            $this->html .= '</div>';
                
            $this->html .= '<div class="card-body">';

            $this->html .= '<div class="table table-responsive">';
            $this->html .= '<table class="table table-hover table-condensed table-striped table-bordered">';
            $this->html .= '<thead>';
            $this->html .= '<tr>';
            $this->html .= '<th>COMUNICACIÓN</th>';
            $this->html .= '<th>SOCIALES</th>';
            $this->html .= '<th>AUTOGESTIÓN</th>';
            $this->html .= '<th>INVESTIGACIÓN</th>';
            $this->html .= '<th>PENSAMIENTO</th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';

            $this->html .= '<tbody id="div-como-ensenara">';
            
            $this->html .= '</tbody>';
            
            $this->html .= '</table>';
            $this->html .= '</div>';

          $this->html .= '</div>';
        $this->html .= '</div>';
      $this->html .= '</div>';

    }

    private function modal(){

      $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#modalEnsenara"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d">Ver Detalle</span>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="modalEnsenara" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">¿Cómo enseñará?</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      
                    </div>
                    <div class="modal-body">';

                    $html .= '<div class="form-group">'; // inicia comunicacion
                        $html .= '<label class=""><b>COMUNICACIÓN:</b></label>';            
                        $html .= '<textarea name="ense_comunicacion" id="editor-comunicacion">';
                        foreach($this->enfoques as $enfoque){
                          if($enfoque->tipo == 'ensenara_comunicacion'){
                            $html .= $enfoque->contenido;
                          }
                        }
                        $html .= '</textarea>
                        <script>
                            CKEDITOR.replace("editor-comunicacion", {
                                customConfig: "/ckeditor_settings/config.js"
                            })
                        </script>';
                    $html .= '</div><hr>'; //fin de comunicacion
                    
                    $html .= '<div class="form-group">'; // inicia sociales
                        $html .= '<label class=""><b>SOCIALES:</b></label>';            
                        $html .= '<textarea name="ense_sociales" id="editor-sociales">';
                        foreach($this->enfoques as $enfoque){
                          if($enfoque->tipo == 'ensenara_sociales'){
                            $html .= $enfoque->contenido;
                          }
                        }
                        $html .= '</textarea>
                        <script>
                            CKEDITOR.replace("editor-sociales", {
                                customConfig: "/ckeditor_settings/config.js"
                            })
                        </script>';
                    $html .= '</div><hr>'; //fin de sociales

                    $html .= '<div class="form-group">'; // inicia autogestion
                        $html .= '<label class=""><b>AUTOGESTIÓN:</b></label>';            
                        $html .= '<textarea name="ense_autogestion" id="editor-autogestion">';
                        foreach($this->enfoques as $enfoque){
                          if($enfoque->tipo == 'ensenara_autogestion'){
                            $html .= $enfoque->contenido;
                          }
                        }
                        $html .= '</textarea>
                        <script>
                            CKEDITOR.replace("editor-autogestion", {
                                customConfig: "/ckeditor_settings/config.js"
                            })
                        </script>';
                    $html .= '</div><hr>'; //fin de autogestion

                    $html .= '<div class="form-group">'; // inicia INVESTIGACIÓN
                        $html .= '<label class=""><b>INVESTIGACIÓN:</b></label>';            
                        $html .= '<textarea name="ense_investigacion" id="editor-investigacion">';
                        foreach($this->enfoques as $enfoque){
                          if($enfoque->tipo == 'ensenara_investigacion'){
                            $html .= $enfoque->contenido;
                          }
                        }
                        $html .= '</textarea>
                        <script>
                            CKEDITOR.replace("editor-investigacion", {
                                customConfig: "/ckeditor_settings/config.js"
                            })
                        </script>';
                    $html .= '</div><hr>'; //fin de INVESTIGACIÓN

                    $html .= '<div class="form-group">'; // inicia PENSAMIENTO
                        $html .= '<label class=""><b>PENSAMIENTO:</b></label>';            
                        $html .= '<textarea name="ense_pensamiento" id="editor-pensamiento">';
                        foreach($this->enfoques as $enfoque){
                          if($enfoque->tipo == 'ensenara_pensamiento'){
                            $html .= $enfoque->contenido;
                          }
                        }
                        $html .= '</textarea>
                        <script>
                            CKEDITOR.replace("editor-pensamiento", {
                                customConfig: "/ckeditor_settings/config.js"
                            })
                        </script>';
                    $html .= '</div>'; //fin de PENSAMIENTO

                    $html .= '</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_ensenara();">Modificar</button>
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }
    
   
}