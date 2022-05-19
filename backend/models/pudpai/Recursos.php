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

class Recursos extends ActiveRecord{

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
                        $this->html .= '<h5 class=""><b>5.- RECURSOS: </b></h5>';                        
                        $this->html .= '<small style="color: #65b2e8">En esta sección especificar claramente cada recurso que se utilizará. Podría mejorarse incluyendo recursos que pudieran utilizarse para llevar a cabo la diferenciación, así como también agregando, por ejemplo, oradores y entornos que pudieran generar mayor profundidad en el trabajo reflexivo sobre el enunciado de la unidad.</small>';
                    $this->html .= '</div>';
                        
                    $this->html .= '<div class="card-body">';

                        $this->html .= $this->modal();
                        $this->html .= '<div class="table table-responsive">';     

                        $this->html .= '<table class="table table-hover table-condensed table-bordered" id="table-recursos">';          
                                               

                        $this->html .= '</table>';            
                        $this->html .= '</div>';

                    $this->html .= '</div>';
                $this->html .= '</div>';
            $this->html .= '</div>';                        

    }

    private function modal(){
        $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#facticasModal"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> 
                              Detalle de Recursos
                              </span>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="facticasModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">DETALLE DE RECURSOS</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      
                    </div>
                    <div class="modal-body">';

                    $html .= '<div class="form-group">'; // inicia bibliograficos
                        $html .= '<label class=""><b>BIBLIOGRÁFICOS:</b></label>';            
                        $html .= '<textarea name="bibliografico" id="editor-bibliografico">';
                        foreach($this->recursos as $recurso){
                          if($recurso->tipo == 'bibliografico'){
                            $html .= $recurso->contenido;
                          }
                        }
                        $html .= '</textarea>
                        <script>
                            CKEDITOR.replace("editor-bibliografico", {
                                customConfig: "/ckeditor_settings/config.js"
                            })
                        </script>';
                    $html .= '</div><hr>'; //fin de bibliograficos

                    $html .= '<div class="form-group">'; // inicia tecnologicos
                        $html .= '<label class=""><b>TECNOLÓGICOS:</b></label>';            
                        $html .= '<textarea name="bibliografico" id="editor-tecnologico">';
                        foreach($this->recursos as $recurso){
                          if($recurso->tipo == 'tecnologico'){
                            $html .= $recurso->contenido;
                          }
                        }
                        $html .= '</textarea>
                        <script>
                            CKEDITOR.replace("editor-tecnologico", {
                                customConfig: "/ckeditor_settings/config.js"
                            })
                        </script>';
                    $html .= '</div><hr>'; //fin de tecnologico

                    $html .= '<div class="form-group">'; // inicia otros
                        $html .= '<label class=""><b>OTROS:</b></label>';            
                        $html .= '<textarea name="otros" id="editor-otros">';
                        foreach($this->recursos as $recurso){
                          if($recurso->tipo == 'otros'){
                            $html .= $recurso->contenido;
                          }
                        }
                        $html .= '</textarea>
                        <script>
                            CKEDITOR.replace("editor-otros", {
                                customConfig: "/ckeditor_settings/config.js"
                            })
                        </script>';
                    $html .= '</div>'; //fin de otros
                                    
                      
                    $html .= '</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" onclick="update_recurso();">Actualizar</button>
                      
                    </div>
                  </div>
                </div>
              </div>';
        return $html;
    }

       
   
}