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

class Indicadores extends ActiveRecord{

    private $planUnidadId;
    private $planUnidad;
    private $habilidades;
    private $scholarisPeriodoId;
    private $institutoId;
    public $html;


    public function __construct($planUnidadId){
        $this->planUnidadId = $planUnidadId;
        $this->planUnidad   = PlanificacionBloquesUnidad::findOne($planUnidadId);

        $this->habilidades = $this->get_hablidades();

        $this->scholarisPeriodoId = Yii::$app->user->identity->periodo_id;
        $this->institutoId = Yii::$app->user->identity->instituto_defecto;

        $this->html = '';

        $this->get_grupo();
    }

    public function get_hablidades(){
      $con = Yii::$app->db;
      $query = "select 	h.es_exploracion as contenido
                    ,h.es_titulo1 
                from 	planificacion_vertical_pai_opciones op 
                    inner join contenido_pai_habilidades h on h.es_exploracion = op.contenido 
                where 	op.plan_unidad_id = $this->planUnidadId
                group  by h.es_exploracion,h.es_titulo1 
                order by h.es_exploracion;";

      $res = $con->createCommand($query)->queryAll();
      return $res;
    }


    private function get_grupo(){        

      $this->html .= '<h5 class=""><b>4.- ENFOQUES DE APRENDIZAJE </b></h5>';                

      $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $this->html .= '<div class="card" style="width: 95%; margin-top:20px">';

            $this->html .= '<div class="card-header">';
                $this->html .= '<h5 class=""><b>4.3.- INDICADORES DE LA HABILIDAD </b></h5>';                
            $this->html .= '</div>';
                
            $this->html .= '<div class="card-body">';

            $this->html .= '<div class="table table-responsive">';
            $this->html .= '<table class="table table-hover table-condensed table-striped table-bordered">';
            $this->html .= '<thead>';
            $this->html .= '<tr style="background-color: #ab0a3d; color: #eee">';
            $this->html .= '<th class="text-center">COMUNICACIÓN</th>';
            $this->html .= '<th class="text-center">SOCIALES</th>';
            $this->html .= '<th class="text-center">AUTOGESTIÓN</th>';
            $this->html .= '<th class="text-center">INVESTIGACIÓN</th>';
            $this->html .= '<th class="text-center">PENSAMIENTO</th>';
            $this->html .= '</tr>';
            $this->html .= '</thead>';

            $this->html .= '<tbody>';
            $this->html .= '<tr>';

            $this->html .= '<td>';
            $this->html .= '<ul>';            
            foreach($this->habilidades as $habilidad){
              if( $habilidad['es_titulo1'] == 'HABILIDADES DE COMUNICACIÓN'){
                $this->html .= '<li>* '.$habilidad['contenido'].'</li>';
              }
            }
            $this->html .= '</ul>';
            $this->html .= '</td>';

            $this->html .= '<td>';
            $this->html .= '<ul>';            
            foreach($this->habilidades as $habilidad){
              if( $habilidad['es_titulo1'] == 'HABILIDADES DE SOCIALES'){
                $this->html .= '<li>* '.$habilidad['contenido'].'</li>';
              }
            }
            $this->html .= '</ul>';
            $this->html .= '</td>';

            $this->html .= '<td>';
            $this->html .= '<ul>';            
            foreach($this->habilidades as $habilidad){
              if( $habilidad['es_titulo1'] == 'HABILIDADES DE AUTOGESTIÓN'){
                $this->html .= '<li>* '.$habilidad['contenido'].'</li>';
              }
            }
            $this->html .= '</ul>';
            $this->html .= '</td>';

            $this->html .= '<td>';
            $this->html .= '<ul>';            
            foreach($this->habilidades as $habilidad){
              if( $habilidad['es_titulo1'] == 'HABILIDADES DE INVESTIGACIÓN'){
                $this->html .= '<li>* '.$habilidad['contenido'].'</li>';
              }
            }
            $this->html .= '</ul>';
            $this->html .= '</td>';

            $this->html .= '<td>';
            $this->html .= '<ul>';            
            foreach($this->habilidades as $habilidad){
              if( $habilidad['es_titulo1'] == 'HABILIDADES DE PENSAMIENTO'){
                $this->html .= '<li>* '.$habilidad['contenido'].'</li>';
              }
            }
            $this->html .= '</ul>';
            $this->html .= '</td>';

            $this->html .= '</tr>';
            $this->html .= '</tbody>';
            
            $this->html .= '</table>';
            $this->html .= '</div>';

          $this->html .= '</div>';
        $this->html .= '</div>';
      $this->html .= '</div>';

    }
    
   
}