<?php

namespace backend\models\pca;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\PlanificacionDesagregacionCabecera;

class FormBibliografia extends \yii\db\ActiveRecord {

//    Atributos
    private $cabecera;
    public $html;

    public function __construct($cabeceraId) {
        
        $this->cabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);
        $this->genera_html();
    }
    
    private function genera_html(){
        $this->html = '';
        $this->html .= '<div class="row">';
            $this->html .= '<div class="col-lg-12 col-md-12">';
                $this->html .= '<div style="text-align:center">';
                    $this->html .= '<h5 style="color:black"><strong>Bibliografía</strong></h5>';
                $this->html .= '</div>';
            $this->html .= '</div>';
        $this->html .= '<hr>';
        
        if($this->cabecera->estado == 'APROBADO' || $this->cabecera->estado == 'EN_COORDINACION' ){
            $this->html .= '<div class="col-lg-12 col-md-12">';
                $this->html .= '<h6>La planificación está '.$this->cabecera->estado.'</h6>';
                $this->html .= '</div>';
        }else{
            $this->html .= '<div class="col-lg-12 col-md-12">';
                $this->html .= '<textarea class="form-control" placeholder="Bibliografía Norma APA" '
                                . 'onchange="ajaxSaveData(\''.null.'\',this,\'bibliografia\')" '
                                . 'name="observaciones"></textarea>';
            $this->html .= '</div>';
        }
        
            
        $this->html .= '</div>';
        
        return $this->html;
    }

}
