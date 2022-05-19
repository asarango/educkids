<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\filters\AccessControl;

/**
 * PlanPlanificacionController implements the CRUD actions for PlanPlanificacion model.
 */
class EnvioCorreosController extends Controller {

    public function actionCorreosComportamiento() {
        
    $fecha = date("Y-m-d");    
    $padres = $this->get_padres($fecha);
    
    $correo = new \backend\models\EnviarCorreo();
//    $correo->enviar('info@ism.edu.ec', 'asarango@zabyca.com', 'Notificaciones de comportamiento', 'prueba');
    
    foreach ($padres as $pad){
        $html = $this->construye_html($pad, $fecha);
        
        $correo = new \backend\models\EnviarCorreo();
        $correo->enviar('info@ism.edu.ec', $pad['email'], 'Notificaciones de comportamiento', $html);
        
    }
    
        
        
/*
        echo 'enviando desde comportamiento';
        
        
        
  */      
        
    }
    
    private function construye_html($pad, $fecha){
        
        $html = '';
        $html.= '<strong>Estimado padre de familia: </strong><br>';
        $html.= 'A continuación informamos de novedades existententes el día de hoy con respecto al comportamiento de su(s) representado(s).';
        
        
        $noveades = $this->get_novedades($fecha, $pad['id']);
        
        $html.= '<table border="1">';
        $html.= '<tr>';
        $html.= '<td align="center"><strong>ESTUDIANTE</strong></td>';
        $html.= '<td align="center"><strong>ASIGNATURA</strong></td>';
        $html.= '<td align="center"><strong>DOCENTE</strong></td>';
        $html.= '<td align="center"><strong>HORA</strong></td>';
        $html.= '<td align="center"><strong>COMPORTAMIENTO</strong></td>';
        $html.= '<td align="center"><strong>OBSERVACION</strong></td>';
        $html.= '</tr>';
        
        foreach ($noveades as $nov){
            $html.= '<tr>';
            $html.= '<td>'.$nov['last_name'].' '.$nov['first_name'].' '.$nov['middle_name'].'</td>';
            $html.= '<td>'.$nov['materia'].'</td>';
            $html.= '<td>'.$nov['apellido_prof'].' '.$nov['x_first_name'].'</td>';
            $html.= '<td>'.$nov['hora'].'</td>';
            $html.= '<td>'.$nov['comportamiento'].'</td>';
            $html.= '<td>'.$nov['observacion'].'</td>';
            $html.= '</tr>';
        }
        
        $html.= '</table>';
        
        
        return $html;
        
    }
    
    private function get_padres($fecha){
        
        $con = Yii::$app->db;
        $query = "select 	p.id
		,par.name
		,par.email 
		,p.x_state 
from 	scholaris_asistencia_profesor a
		inner join scholaris_asistencia_alumnos_novedades n on n.asistencia_profesor_id = a.id 
		inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
		inner join op_student s on s.id = g.estudiante_id 
		inner join op_parent_op_student_rel rel on rel.op_student_id = s.id 
		inner join op_parent p on p.id = rel.op_parent_id 
		inner join res_partner par on par.id = p.name
where	a.fecha = '$fecha'
group by p.id, par.name, par.email;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    private function get_novedades($fecha, $parentId){
        $con = Yii::$app->db;
        $query = "select 	s.id 
		,s.last_name 
		,s.first_name 
		,s.middle_name 
		,c.codigo 
		,c.nombre as comportamiento
		,n.observacion 
		,h.nombre as hora
		,m.name as materia
		,f.last_name as apellido_prof
		,f.x_first_name 
from 	scholaris_asistencia_profesor a
		inner join scholaris_asistencia_alumnos_novedades n on n.asistencia_profesor_id = a.id 
		inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
		inner join op_student s on s.id = g.estudiante_id 
		inner join op_parent_op_student_rel rel on rel.op_student_id = s.id 
		inner join op_parent p on p.id = rel.op_parent_id 
		inner join res_partner par on par.id = p.name
		inner join scholaris_asistencia_comportamiento_detalle c on c.id = n.comportamiento_detalle_id 
		inner join scholaris_horariov2_hora h on h.id = a.hora_id 
		inner join scholaris_clase cla on cla.id = a.clase_id 
		inner join scholaris_materia m on m.id = cla.idmateria 
		inner join op_faculty f on f.id = cla.idprofesor 
where	a.fecha = '$fecha'
		and p.id = $parentId;";
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

}
