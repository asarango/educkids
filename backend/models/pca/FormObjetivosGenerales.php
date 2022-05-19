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

class FormObjetivosGenerales extends \yii\db\ActiveRecord {

//    Atributos
    private $objetivos;
    private $cabecera;
    public $html;

    public function __construct($cabeceraId) {
        $this->cabecera = PlanificacionDesagregacionCabecera::findOne($cabeceraId);

        $asignaturaCurriculoId = $this->cabecera->ismAreaMateria->asignatura_curriculo_id;
        $nivelCurriculoId = $this->cabecera->ismAreaMateria->curso_curriculo_id;
        
        $this->objetivos = $this->consulta_objetivos_generales($asignaturaCurriculoId, $nivelCurriculoId, $cabeceraId);
        
//        echo '<pre>';
//        print_r($this->objetivos);
//        die();
                
        $this->genera_html();
    }
    
    
    
    
    private function genera_html(){
        $this->html = '';
        $this->html .= '<div class="row">';
            $this->html .= '<div class="col-lg-12 col-md-12">';
                $this->html .= '<div style="text-align:center">';
                    $this->html .= '<h5 style="color:black"><strong>Objetivos Generales</strong></h5>';
                    
                    if($this->cabecera->estado == 'APROBADO' || $this->cabecera->estado == 'EN_COORDINACION' ){
                    $this->html .= '<small>La planificación está '.$this->cabecera->estado.'.</small>';
                $this->html .= '</div>';
                    }else{
                    $this->html .= '</div>';
                $this->html .= '</div>';
                
        $this->html .= '<div class="row">';
            $this->html .= '<div class="col-lg-12 col-md-12">';
                $this->html .= '<div class="table-responsive" id="global">';
                    $this->html .= '<table class="table table-hover table-striped my-text-medium">';
                        $this->html .= '<thead>';
                            $this->html .= '<tr>';
                                $this->html .= '<td><strong>CÓDIGO</strong></td>';
                                $this->html .= '<td style="text-align:center"><strong>DESCRIPCIÓN</strong></td>';
                            $this->html .= '</tr>';
                        $this->html .= '</thead>';
                        $this->html .= '<tbody>';
                        foreach ($this->objetivos as $objetivo){
                            $descripcion = $objetivo['description'];
                            //$descripcion = 'Evaluar, con sentido crítico, discursos orales relacionados con la actualidad social y cultural para asumir y consolidar una perspectiva personal. ';                                
                                $code = $objetivo['code'];
                            $this->html .= '<tr>';
                                $this->html .= '<td style="vertical-align:middle" ><strong>';
                                $this->html .= '<a>';
                                    $this->html .= $objetivo['code'];
                                $this->html .= '</a>';
                                $this->html .='</strong></td>';
                                       
                                $this->html .= '<td style="text-align:justify">';
                                        $this->html .= '<a class="link" type="button" onclick="ajaxSaveContent(\''.$descripcion.'\',\''.$code.'\',\'objetivos_generales\')">';                                        
                                         $this->html.= $descripcion;                                
                                        $this->html .= '</a>';
                                                '</td>';
                            $this->html .= '</tr>';
                        }
                        $this->html .= '</tbody>';
                    $this->html .= '</table>';
                $this->html .= '</div>';
            $this->html .= '</div>';
        $this->html .= '</div>';
                    }
                    
                    
        
        
        
        return $this->html;
    }
    
    private function consulta_objetivos_generales($asignaturaCurriculoId, $nivelCurriculoId, $cabeceraId) {
        $con = Yii::$app->db;
        $query = "select 	m.reference_type 
                    ,m.code 
                    ,m.description 
                from 	curriculo_mec m
                where 	m.asignatura_id = $asignaturaCurriculoId
                                and reference_type = 'objgeneral'
                                and subnivel_id = $nivelCurriculoId
                                and m.code not in (
                                        select 	det.codigo 
                                        from 	planificacion_desagregacion_cabecera cab
                                                        --inner join op_course_template tem on tem.id = cab.op_course_template_id 
                                                        inner join pca_detalle det on det.desagregacion_cabecera_id = cab.id 
                                        where	cab.id = $cabeceraId
                                                        and det.tipo = 'objetivos_generales'
                                                        and det.codigo = m.code);";
        //echo $query;                                                
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    

}
?>