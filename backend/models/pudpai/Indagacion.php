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
use backend\models\PlanificacionVerticalPaiOpciones;
use DateTime;

class Indagacion extends ActiveRecord{

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

        $this->get_conceptos();
        $this->get_enunciado();
    }


    private function get_conceptos(){        

            $this->html .= '<h5 class=""><b>2.- INDAGACIÓN: ESTABLECIMIENTO DEL PROPÓSITO DE LA UNIDAD</b></h5>';
            $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $this->html .= '<div class="card" style="width: 90%; margin-top:20px">';

            $this->html .= '<div class="card-header">';
                $this->html .= '<h5 class=""><b>2.1.- CONCEPTOS</b></h5>';
            $this->html .= '</div>';
                
            $this->html .= '<div class="card-body">';
                // inicia row
                $this->html .= '<div class="row">';
                $this->html .= '<div class="col-lg-3 col-md-3 text-center border"><b><u>RELACIONADOS</u></b></div>';
                $this->html .= '<div class="col-lg-3 col-md-3 text-center border"><b><u>CLAVE</u></b></div>';
                $this->html .= '<div class="col-lg-6 col-md-6 text-center border"><b><u>GLOBAL Y EXPLORACIÓN</u></b></div>';                                                    
                $this->html .= '</div>';
                //******finaliza row

                // inicia row
                $conceptos = PlanificacionVerticalPaiOpciones::find()->where([
                    'plan_unidad_id' => $this->planUnidadId
                ])
                ->orderBy('tipo', 'contenido')
                ->all();                
                
                $this->html .= '<div class="row">';
                $this->html .= '<div class="col-lg-3 col-md-3 text-center border">';
                $this->html .= '<ul>';
                foreach($conceptos as $clave){
                    if($clave->tipo == 'concepto_clave'){
                        $this->html .= '<li>';
                        $this->html .= $clave->contenido;
                        $this->html .= '</li>';
                    }                    
                }
                $this->html .= '</ul>';
                $this->html .= '</div>';
                
                $this->html .= '<div class="col-lg-3 col-md-3 text-center border">';
                $this->html .= '<ul>';
                foreach($conceptos as $clave){
                    if($clave->tipo == 'concepto_relacionado'){
                        $this->html .= '<li>';
                        $this->html .= $clave->contenido;
                        $this->html .= '</li>';
                    }                    
                }
                $this->html .= '</ul>';
                $this->html .= '</div>';


                $this->html .= '<div class="col-lg-6 col-md-6 text-center border">';
                $this->html .= '<ul>';
                foreach($conceptos as $clave){
                    if($clave->tipo == 'contexto_global'){
                        $this->html .= '<li>';
                        $this->html .= $clave->contenido;
                        $this->html .= '</li>';
                    }                    
                }
                $this->html .= '</ul>';
                $this->html .= '</div>';
                $this->html .= '</div>';
                //******finaliza row */
                
            $this->html .= '</div>';//fin de card-body
            $this->html .= '</div>';
        $this->html .= '</div>';

    }


    private function get_enunciado(){
        $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $this->html .= '<div class="card" style="width: 90%; margin-top:20px">';

                $this->html .= '<div class="card-header">';
                    $this->html .= '<h5 class=""><b>2.2.- ENUNCIADO DE LA INDAGACIÓN</b></h5>';
                $this->html .= '</div>';
                    
                $this->html .= '<div class="card-body">';
                $this->html .= '<p>';
                $this->html .= $this->planUnidad->enunciado_indagacion;
                $this->html .= '</p>';
                $this->html .= '</div>';

            $this->html .= '</div>';
        $this->html .= '</div>';
    }
    
}