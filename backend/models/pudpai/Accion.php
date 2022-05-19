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
use DateTime;

class Accion extends ActiveRecord{

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

        $this->get_accion();
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
                        $this->html .= '<h5 class=""><b>5.- ACCIÓN: ENSEÑANZA Y APRENDIZAJE A TRAVÈS DE LA INDAGACIÒN</b></h5>';                        
                    $this->html .= '</div>';
                        
                    $this->html .= '<div class="card-body">';
                        $this->html .= '<div class="table table-responsive">';
                        $this->html .= '<table class="table table-hover table-condensed table-bordered">';
                        $this->html .= '<thead>';            
                        $this->html .= '<tr valign="top" style="background-color: #eee">';            
                        $this->html .= '<th><b>CONTENIDOS: </b>
                        <small style="color: #65b2e8">
                            copiar OA de MINEDUC. Incluir las habilidades, los conocimientos disciplinarios y los conceptos clave y relacionados elegidos para la unidad.
                        </small></th>';
                        
                        $this->html .= '<th><b>EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS DE ENSEÑANZA: </b>
                        <small style="color: #65b2e8">
                            variedad que abarque el espectro de preferencias de los alumnos. 
                            Basadas en los conocimientos previos y en la indagación. (Todas las actividades a realizar en clase o para la casa)
                        </small></th>';

                        $this->html .= '<th><b>EVALUACIÓN FORMATIVAS: </b>
                        <small style="color: #65b2e8">
                            genera evidencia de avance y ofrece oportunidades variadas de practicar, de hacer comentarios detallados y adaptar la enseñanza planificada. 
                            Incluye autoevaluación y coevaluación. Se deben ofrecer comentarios sobre el avance en el desarrollo de habilidades.
                        </small></th>';

                        $this->html .= '<th><b>DIFERENCIACIÓN: </b>
                        <small style="color: #65b2e8">
                            de contenido, de proceso (cómo se enseñará y se aprenderá) y de producto (lo que se evaluará). Definir las actividades 
                            correspondientes a los 3 diferentes estilos de aprendizaje más reconocidos: VISUAL, KINESTÉSICO, AUDITIVO.
                        </small></th>';                        

                        $this->html .= '</tr>';            
                        $this->html .= '</thead>';       

                        $this->html .= '<tbody>';
                        foreach($temas as $tema){
                            $this->html .= '<tr>';
                            $this->html .= '<td>'.$tema->subtitulo.'</td>';
                            $this->html .= '<td>'.$tema->experiencias.'</td>';
                            $this->html .= '<td>'.$tema->evaluacion_formativa.'</td>';
                            $this->html .= '<td>'.$tema->diferenciacion.'</td>';
                            $this->html .= '</tr>';
                        }            
                        $this->html .= '</tbody>';      

                        $this->html .= '</table>';            
                        $this->html .= '</div>';

                    $this->html .= '</div>';
                $this->html .= '</div>';
            $this->html .= '</div>';                        

    }

       
   
}