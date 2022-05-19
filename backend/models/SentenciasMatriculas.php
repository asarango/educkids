<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $orden
 *
 * @property Operacion[] $operacions
 */
class SentenciasMatriculas extends \yii\db\ActiveRecord {

    public function tomaLiteralA($paralelo, $alumno) {
        $con = \Yii::$app->db;
        $query = "select 	s.id
                ,p.numero_identificacion
		,n.name as nacionalidad
		,e.id as codigo		
		,s.birth_date
		,cur.name as curso
		,par.name as paralelo
		,e.create_date
		,s.x_main_street
		,s.x_home_number
		,s.x_second_street
		,p.email
		,p.phone
from 	op_student s
		inner join op_student_inscription i on i.student_id = s.id
		left join op_student_enrollment e on e.inscription_id = i.id
		left join res_partner p on p.id = s.partner_id
		left join res_country_nationality n on n.id = p.x_nacionalidad_id
		left join op_course_paralelo par on par.id = i.parallel_id
		left join op_course cur on cur.id = par.course_id
where	s.id = $alumno
		and i.parallel_id = $paralelo;";
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
    }
    
    public function tomaLiteralB($alumno,$cualPapa) {
        $con = \Yii::$app->db;
        $query = "select 	p.x_state
		,par.name as nombre
		,par.numero_identificacion
		,n.name as nacionalidad
		,par.street
		,par.phone
		,par.mobile
		,pro.name as profesion
		,par.function as acupacion
		,par.email
from 	op_parent_op_student_rel r
		left join op_parent p on p.id = r.op_parent_id
		left join res_partner par on par.id = p.name
		left join res_country_nationality n on n.id = par.x_nacionalidad_id
		left join profesion_res_partner_rel rp on rp.res_partner_id = par.id
		left join profesion pro on pro.id = rp.profesion_id
where	r.op_student_id = $alumno "
                . "and p.x_state = '$cualPapa' order by p.x_state desc;";
        $resp = $con->createCommand($query)->queryAll();

        return $resp;
    }
    
    public function get_ciudad($instituto){
        $con = \Yii::$app->db;
        $query = "select 	p.city
                    from 	op_institute i
                                    inner join res_store r on r.id = i.store_id
                                    inner join res_company c on c.id = r.company_id
                                    inner join res_partner p on p.id = c.partner_id
                    where	i.id = $instituto;";
        $resp = $con->createCommand($query)->queryOne();

        return $resp['city'];
    }

    
}
