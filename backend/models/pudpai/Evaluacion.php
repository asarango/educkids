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
use DateTime;

class Evaluacion extends ActiveRecord{

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

            $objetivos = PlanificacionVerticalPaiDescriptores::find()
            ->innerJoin('scholaris_criterio_descriptor d', 'd.id = planificacion_vertical_pai_descriptores.descriptor_id')
            ->innerJoin('scholaris_criterio c', 'c.id = d.criterio_id')
            ->where([
              'plan_unidad_id' => $this->planUnidadId
            ])
            ->orderBy('c.criterio')
            ->all();

            

            $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $this->html .= '<div class="card" style="width: 90%; margin-top:20px">';

            $this->html .= '<div class="card-header">';
                $this->html .= '<h5 class=""><b>3.- EVALUACIÓN</b></h5>';                
            $this->html .= '</div>';
                
            $this->html .= '<div class="card-body">';
                // inicia row 
                $this->html .= '<div class="row">';
                $this->html .= '<div class="col-lg-4 col-md-4 text-left"><b>OBJETIVOS ESPECÍFICOS Y ASPECTOS:</b><br>
                <small style="color: #65b2e8">(Copiar la redacción tal y como aparece en la guía de la asignatura, para cada año del PAI)</small>
                </div>';
                $this->html .= '<div class="col-lg-8 col-md-8 text-left"><b>EVALUACIÓN SUMATIVA:</b>
                <small style="color: #65b2e8"> (se explica claramente qué harán los alumnos para demostrar lo que saben, 
                lo que comprenden y lo que  pueden hacer; permite demostrar comprensión de los conceptos, 
                la relación conceptual y el contexto que se describen en el enunciado de la indagación; 
                permite demostrar objetivos y aspectos escogidos para  la  unidad;  
                utiliza  términos  de  instrucción  correctos para  ese  año  del  PAI,  
                permite  a  los  alumnos  demostrar  los  descriptores  de  todos  los  niveles  de  logro;  
                es estimulante pero accesible; permite a los alumnos comunicar lo que saben, lo que comprenden y lo que 
                pueden hacer de maneras múltiples y abiertas; permite aplicar lo que han aprendido a una variedad de 
                situaciones auténticas o situaciones que simulan el mundo real).</small>';

                // $this->html .= '<hr>';
                // $criterios = $this->consulta_criterios_agrupados();
                // foreach($criterios as $criterio){
                //   $this->html .= $this->modal_criterio($criterio['id'], $criterio['criterio']);
                // }

                $this->html .= '</div>';
                
                
                $this->html .= '</div>';
                //******finaliza row 
                
                $this->html .= '<hr>';

                //******inicia row 
                $this->html .= '<div class="row">';
                  $this->html .= '<div class="col-lg-4 col-md-4 text-left border">';
                    $this->html .= '<ul>';
                      foreach($objetivos as $obj){
                        $this->html .= '<li>'.'<b>CRITERIO '.$obj->descriptor->criterio->criterio.': </b> '.$obj->descriptor->descricpcion.'</li>';
                        $this->html .= '*** holi';
                        $this->html .= '<hr>';
                      }
                    $this->html .= '</ul>';
                  $this->html .= '</div>';//fin fin de descriptores

                  $this->html .= '<div class="col-lg-8 col-md-8 border">';                 
                  
                  $this->html .= '<div class="row">';
                  $this->html .= '<div class="col-lg-6 col-md-6 border text-left" id="div-evaluacion-sumativa">';

                  $this->html .= '</div>';
                  
                  $this->html .= '<div class="col-lg-6 col-md-6 border text-center" id="div-evaluacion-sumativa2">';
                  $this->html .= '</div>';
                  $this->html .= '</div>';


                  $this->html .= '</div>';
                $this->html .= '</div>';
                //******finaliza row 
                
            $this->html .= '</div>';//fin de card-body
            $this->html .= '</div>';
        $this->html .= '</div>';

    }


    private function modal_criterio($criterioId, $criterio){
      $html = '<a href="#"  data-bs-toggle="modal" data-bs-target="#modal'.$criterio.'"> 
                              <span class="badge rounded-pill" 
                              style="background-color: #ab0a3d">
                              '.$criterio.'
                              </span>';
                $html .= '</a>';
      
                $html.= '<div class="modal fade" id="modal'.$criterio.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">CRITERIO '.$criterio.'</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      
                    </div>
                    <div class="modal-body">                    
                    <hr>';

                    $html .= '<input type="text" name="facticas" class="form-control" id="input-titulo" 
                                placeholder="Ingrese el tema">'; 
                                
                    $html .= '<textarea name="revision_coordinacion_observaciones" id="editor">
                    <?= $cabecera->revision_coordinacion_observaciones ?>
                </textarea>
                <script>
                    CKEDITOR.replace("editor", {
                        customConfig: "/ckeditor_settings/config.js"
                    })
                </script>';
                      
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