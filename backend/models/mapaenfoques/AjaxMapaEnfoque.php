<?php

namespace backend\models\mapaenfoques;

use backend\models\ContenidoPaiHabilidades;
use backend\models\MapaEnfoquesPai;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class AjaxMapaEnfoque extends \yii\db\ActiveRecord {

    private $materiaId;
    private $habilidadOrden;
    public $objectHabilidad;
    private $arrayCursosPai;
    private $scholarisPeriodoId;
    
    public $html;

    public function __construct($materiaId, $habilidadOrden, $cursosPai, $periodoId){
        $this->materiaId        = $materiaId;
        $this->habilidadOrden   = $habilidadOrden;
        $this->arrayCursosPai   = $cursosPai;
        $this->scholarisPeriodoId = $periodoId;
         
        
         $this->objectHabilidad = ContenidoPaiHabilidades::find()->where([
             'orden_titulo2' => $habilidadOrden
         ])->one();

        $this->generate_html();

    }

    private function generate_html(){
        $html = '';
        $html .= '<h3>'.$this->objectHabilidad->es_titulo2.'</h3>';

        $exploradores = ContenidoPaiHabilidades::find()->where([
            'orden_titulo2' => $this->habilidadOrden
        ])
        ->all();

        $html .= '<div class="row">';
            $html .= '<div class="col-lg-12 col-md-12 col-sm-12">';
                // Inicia de Tabla
                $html .= '<table class="table table-hover table-bordered table-striped my-text-medium">';
                    $html .= '<thead>';
                        $html .= '<tr>';
                            //Se repiten encabezados los cursos de PAI 
                            $html .= '<th scope="col" style="text-align:center">Exploradores</th>';
                            foreach ($this->arrayCursosPai as $curso) {
                                $html .= '<th scope="col" style="text-align:center">'.$curso['name'].'</th>';
                            }
                        $html .= '</tr>';
                    $html .= '</thead>';
                    $html .= '<tbody>';
                    foreach ($exploradores as $explorador) {
                        $html .= '<tr>';
                            $html .= '<td  style="vertical-align:middle; width:380px">'.$explorador['es_exploracion'].'</td>';
                            foreach ($this->arrayCursosPai as $cur) {
                                $data = $this->consulta_mapa_enfoques_pai($this->scholarisPeriodoId,$cur['id'],$explorador->id);
                                if( $data->estado == true){
                                    $html .= '<td style="vertical-align:middle; text-align:center">';
                                        $html .= '<a onclick="cambiaEstado('.$data->id.',\''.$explorador->orden_titulo2.'\')"><i type="button" class="fas fa-check" style="color:green"></i></a>';
                                    $html .= '</td>';    
                                }else{
                                    $html .= '<td style="vertical-align:middle; text-align:center">';
                                        $html .= '<a onclick="cambiaEstado('.$data->id.',\''.$explorador->orden_titulo2.'\')"><i type="button" class="fas fa-times" style="color:red"></i></a>';
                                    $html .= '</td>'; 
                                }
                                
                            }
                        $html .= '</tr>';
                    }    
                    $html .= '</tbody>';
                $html .= '</table>';
                // Fin de tabla
            $html .= '</div>';
        $html .= '</div>';


            //   echo '<pre>';
            //  print_r($this->arrayCursosPai);
            //  echo '************************************* <br>';
            //   print_r($exploradores);

        $this->html = $html;
    }

    /* Metodo que consulta si enfoque esta seleccionado en la 
        Tabla: mapa_enfoques_pai
        Parametros: periodo_id, course_template_id, pai_habilidad_id
    */
    private function consulta_mapa_enfoques_pai($periodoId,$cursoId,$paiHabilId){
         $enfoqueActivo = MapaEnfoquesPai::find()->where([
             'periodo_id' => $this->scholarisPeriodoId,
             'course_template_id' => $cursoId,
             'pai_habilidad_id' => $paiHabilId
         ])->one();
        
        return $enfoqueActivo;
    }


}