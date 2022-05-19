<?php

namespace backend\models\pca;

use Yii;
use backend\models\PlanificacionDesagregacionCabecera;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class FormTiempo extends \yii\db\ActiveRecord {

//    Atributos
    private $cabecera;
    public $html;

    public function __construct($cabeceraId) {
        $this->cabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);
        $this->genera_formulario();

//        echo '<pre>';
//        print_r($this->cabecera);
//        die();
    }

    private function genera_formulario() {
        $this->html = '';
        $this->html .= '<div class="row my-text-medium" style="height:auto">';
        $this->html .= '<h5 style="text-align:center; color:black"><strong>Formulario Tiempo</strong></h5>';
        $this->html .= '<hr>';
        $this->html .= '<div class="col-lg-6 col-md-6" style="margin-top:5px; padding-left:20px;" >';
        $this->html .= '<p>Carga Horaria Semanal:</p>';
        $this->html .= '<p>N° Semanas Trabajo:</p>';
        $this->html .= '<p>Evaluación del aprendizaje e imprevistos:</p>';
        $this->html .= '</div>';
        $this->html .= '<div class="col-lg-6 col-md-6" style="margin-top:5px; padding-left:20px;" >';
        if ($this->cabecera->estado == 'APROBADO' || $this->cabecera->estado == 'EN_COORDINACION') {
            $this->html .= '<input class="form-control" '
                    . 'onchange="ajaxSaveData(\'carga_horaria_semanal\',this,\'tiempo\')" '
                    . 'style="width:100px" type="number" '
                    . 'name="carga_horaria_semanal" min="1" '
                    . 'value="' . $this->cabecera->carga_horaria_semanal . '" disabled>';
        } else {
            $this->html .= '<input class="form-control" '
                    . 'onchange="ajaxSaveData(\'carga_horaria_semanal\',this,\'tiempo\')" '
                    . 'style="width:100px" type="number" '
                    . 'name="carga_horaria_semanal" min="1" '
                    . 'value="' . $this->cabecera->carga_horaria_semanal . '" >';
        }


        if ($this->cabecera->estado == 'APROBADO' || $this->cabecera->estado == 'EN_COORDINACION') {
            $this->html .= '<input class="form-control" '
                    . 'onchange="ajaxSaveData(\'semanas_trabajo\',this,\'tiempo\')" '
                    . 'style="width:100px" type="number" '
                    . 'name="semanas_trabajo" min="1" '
                    . 'value="' . $this->cabecera->semanas_trabajo . '" disabled>';
        } else {
            $this->html .= '<input class="form-control" '
                    . 'onchange="ajaxSaveData(\'semanas_trabajo\',this,\'tiempo\')" '
                    . 'style="width:100px" type="number" '
                    . 'name="semanas_trabajo" min="1" '
                    . 'value="' . $this->cabecera->semanas_trabajo . '">';
        }

        if ($this->cabecera->estado == 'APROBADO' || $this->cabecera->estado == 'EN_COORDINACION') {
            $this->html .= '<input class="form-control" '
                    . 'onchange="ajaxSaveData(\'evaluacion_aprend_imprevistos\',this,\'tiempo\')" '
                    . 'style="width:100px" type="number" '
                    . 'name="aprendizaje" min="1" '
                    . 'value="' . $this->cabecera->evaluacion_aprend_imprevistos . '" disabled>';
        } else {
            $this->html .= '<input class="form-control" '
                    . 'onchange="ajaxSaveData(\'evaluacion_aprend_imprevistos\',this,\'tiempo\')" '
                    . 'style="width:100px" type="number" '
                    . 'name="aprendizaje" min="1" '
                    . 'value="' . $this->cabecera->evaluacion_aprend_imprevistos . '">';
        }

        $this->html .= '</div>';
        $this->html .= '</div>';

        return $this->html;
    }

}
