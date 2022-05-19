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
use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
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
        //$this->html.= $this->modal_criterio();
            $this->html.= '<div class="col-lg-12 col-md-12">';
                $this->html.= '<div class="table table-responsive">';
                $this->html.= $this->tabla();
                $this->html.= '</div>';
            $this->html.= '</div>';
        $this->html.= '</div>';

     }

     private function tabla(){
      $criterios = PlanificacionDesagregacionCriteriosEvaluacion::find()->where([
        'bloque_unidad_id' => $this->planUnidadId
        //'tipo' => 'criterio'
      ])->all();

      $html = '<div class="table table-responsive">';
      $html .= '<table class="table table-striped table-bordered table-hover">';
      $html .= '<thead>';
      $html .= '<tr>';
      $html .= '<th style="text-align: center">CÓDIGO</th>';
      $html .= '<th style="text-align: center">CRITERIOS DE EVALUACIÓN</th>';
      $html .= '</tr>';
      $html .= '</thead>';
      $html .= '<tbody id="">';
      foreach($criterios as $criterio){
        $html .= '<tr>';
        $html .= '<td>'.$criterio->criterioEvaluacion->code.'</td>';
        $html .= '<td>'.$criterio->criterioEvaluacion->description.'</td>';
        $html .= '</tr>';
      }
      $html .= '</tbody>';
      $html .= '</table>';
      $html .= '</div>';

      return $html;
     }        
    
    
}

?>