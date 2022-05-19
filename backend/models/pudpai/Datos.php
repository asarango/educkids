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
use DateTime;

class Datos extends ActiveRecord{

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

        $this->get_data();
    }


    private function get_data(){
        
        $tiempo = $this->calcula_horas($this->planUnidad->planCabecera->ismAreaMateria->materia_id, 
                                                $this->planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id);
        

        $this->html .= '<div class="" style="align-items: center; display: flex; justify-content: center;">';
            $this->html .= '<div class="card" style="width: 70%; margin-top:20px">';

            $this->html .= '<div class="card-header">';
                $this->html .= '<h5 class=""><b>1.- DATOS INFORMATIVOS</b></h5>';
            $this->html .= '</div>';
                
            $this->html .= '<div class="card-body">';
                // inicia row
                $this->html .= '<div class="row">';
                $this->html .= '<div class="col"><b>GRUPO DE ASIGNATURAS Y DISCIPLINA</b></div>';
                $this->html .= '<div class="col">'.$this->planUnidad->planCabecera->ismAreaMateria->materia->nombre.'</div>';
                $this->html .= '<div class="col"><b>PROFESOR</b></div>';
                $docentes = $this->get_docentes();
                $this->html .= '<div class="col">';
                    foreach($docentes as $docente){
                        $this->html .= $docente['docente'].' | ';
                    }
                $this->html .= '</div>';
                $this->html .= '</div>';
                //******finaliza row
                $this->html .= '<hr>';
                //inicia row
                $this->html .= '<div class="row">';
                $this->html .= '<div class="col"><b>UNIDAD Nº</b></div>';
                $this->html .= '<div class="col">'.$this->planUnidad->curriculoBloque->last_name.'</div>';
                $this->html .= '<div class="col"><b>TÍTULO DE LA UNIDAD</b></div>';            
                $this->html .= '<div class="col">'.$this->planUnidad->unit_title.'</div>';
                $this->html .= '</div>';
                //******finaliza row
                $this->html .= '<hr>';
                //inicia row
                $this->html .= '<div class="row">';
                $this->html .= '<div class="col"><b>AÑO DEL PAI:</b></div>';
                $this->html .= '<div class="col">'.$this->planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name.'</div>';
                $this->html .= '<div class="col"><b>DURACIÓN DE LA UNIDAD EN HORAS:</b></div>';                
                $this->html .= '<div class="col">'.$tiempo['horas'].'</div>';
                $this->html .= '</div>';
                //******finaliza row
                $this->html .= '<hr>';
                //inicia row
                $this->html .= '<div class="row">';
                $this->html .= '<div class="col"><b>FECHA INICIO:</b></div>';
                $this->html .= '<div class="col">'.$tiempo['fecha_inicio'].'</div>';
                $this->html .= '<div class="col"><b>DURACIÓN DE LA UNIDAD EN HORAS:</b></div>';                
                $this->html .= '<div class="col">'.$tiempo['fecha_final'].'</div>';
                $this->html .= '</div>';
                //******finaliza row
            $this->html .= '</div>';//fin de card-body
            $this->html .= '</div>';
        $this->html .= '</div>';

    }


    public function get_docentes(){
        $areaMateriaId = $this->planUnidad->planCabecera->ismAreaMateria->id;
        $templateId = $this->planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->op_course_template_id;

        $con = Yii::$app->db;
        $query = "select 	concat(f.x_first_name,' ', f.last_name) as docente
        from 	scholaris_clase c 
                 inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
                 inner join op_course oc on oc.id = c.idcurso 
                inner join op_faculty f on f.id = c.idprofesor 
         where 	c.idmateria  = $areaMateriaId
                 and p.id = $this->scholarisPeriodoId
                 and oc.x_template_id = $templateId
         group by f.x_first_name, f.last_name;";

        $res = $con->createCommand($query)->queryAll();

        return $res;
    }


    public function calcula_horas($materiaId, $courseTemplateId){
        $con = Yii::$app->db;
        $query = "select 	count(h.detalle_id) as hora_semanal
                    ,h.clase_id 
                    ,cla.tipo_usu_bloque
        from	scholaris_horariov2_horario h		
                inner join scholaris_clase cla on cla.id = h.clase_id
        where 	h.clase_id = (select 	max(c.id)
                                from 	scholaris_clase c 
                                        inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
                                        inner join op_course oc on oc.id = c.idcurso 
                                        inner join op_faculty f on f.id = c.idprofesor 
                                where 	c.ism_area_materia_id  = $materiaId
                                        and p.id = $this->scholarisPeriodoId
                                        and oc.x_template_id = $courseTemplateId) group by h.clase_id, cla.tipo_usu_bloque;";

        $resH = $con->createCommand($query)->queryOne();
        
        
        $horasSemana = $resH['hora_semanal'];
        
        $uso = $resH['tipo_usu_bloque'];
        $orden = $this->planUnidad->curriculoBloque->code;

        $queryFechas = "select 	b.bloque_inicia 
                                ,b.bloque_finaliza 
                        from 	scholaris_bloque_actividad b
                                inner join scholaris_periodo p on p.codigo = b.scholaris_periodo_codigo 
                        where 	b.tipo_uso = '$uso'
                                and p.id = $this->scholarisPeriodoId
                                and b.orden = $orden;";
        $resF = $con->createCommand($queryFechas)->queryOne();
        
        $fechaInicia = new DateTime($resF['bloque_inicia']);
        $fechaFinal = new DateTime($resF['bloque_finaliza']);

        $diff = $fechaInicia->diff($fechaFinal);

        return array(
            'horas' => ($diff->days) * $horasSemana,
            'fecha_inicio' => $resF['bloque_inicia'],
            'fecha_final' => $resF['bloque_finaliza']
        );

    }
}