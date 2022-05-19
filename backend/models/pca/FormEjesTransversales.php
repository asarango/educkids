<?php

namespace backend\models\pca;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class FormEjesTransversales extends \yii\db\ActiveRecord {

//    Atributos
    public $html;

    public function __construct($cabeceraId) {
        $this->genera_html();
    }
    
    private function genera_html(){
        $this->html = '';
        $this->html .= '<div class="row">';
            $this->html .= '<div class="col-lg-12 col-md-12">';
                $this->html .= '<div style="text-align:center">';
                    $this->html .= '<h5 style="color:black"><strong>Ejes Transversales</strong></h5>';
                $this->html .= '</div>';
                $this->html .= '<hr>';
                $this->html .= '<div class="col-lg-12 col-md-12" style="margin-top:20px;text-align:center" >';
                    $this->html .= '<h5 style="color:#65b2e8">¡Todos los Ejes Transversales han sido seleccionados por defecto!</h5>';
                $this->html .= '</div>';
            $this->html .= '</div>';
        $this->html .= '</div>';
        
        return $this->html;
    }



}
