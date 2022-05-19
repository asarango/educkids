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
class SentenciasPud extends \yii\db\ActiveRecord {

    public function cambia_estado($pudId, $estado){
        $con = Yii::$app->db;
        $query = "update scholaris_plan_pud "
                . "set estado = '$estado' "
                . "where id = $pudId";
        $con->createCommand($query)->execute();
    }
    
    public function get_revisonC($usuario){
        $con = Yii::$app->db;
        $query = "select 	cur.name as curso
		,pa.name as paralelo
		,m.name as materia
                ,fac.last_name as nomprof
		,fac.x_first_name as apeprof
		,fa.last_name
		,fa.x_first_name
		,b.name as bloque
		,pu.estado
		,pu.titulo
                ,pu.id
from	scholaris_plan_pud pu
		inner join op_faculty fa on fa.id = pu.quien_revisa_id
		inner join res_users u on u.partner_id = fa.partner_id
		inner join scholaris_clase c on c.id = pu.clase_id
		inner join op_course cur on cur.id = c.idcurso
		inner join op_course_paralelo pa on pa.id = c.paralelo_id
		inner join scholaris_materia m on m.id = c.idmateria
		inner join scholaris_bloque_actividad b on b.id = pu.bloque_id
                inner join op_faculty fac on fac.id = c.idprofesor
where	u.login = '$usuario'
		and pu.estado in ('REVISIONC','RECHAZADO');";
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    
    public function get_revisonV(){
        $con = Yii::$app->db;
        $query = "select 	cur.name as curso
		,pa.name as paralelo
		,m.name as materia
                ,fac.last_name as nomprof
		,fac.x_first_name as apeprof
		,fa.last_name
		,fa.x_first_name
		,b.name as bloque
		,pu.estado
		,pu.titulo
                ,pu.id
from	scholaris_plan_pud pu
		inner join op_faculty fa on fa.id = pu.quien_revisa_id
		inner join res_users u on u.partner_id = fa.partner_id
		inner join scholaris_clase c on c.id = pu.clase_id
		inner join op_course cur on cur.id = c.idcurso
		inner join op_course_paralelo pa on pa.id = c.paralelo_id
		inner join scholaris_materia m on m.id = c.idmateria
		inner join scholaris_bloque_actividad b on b.id = pu.bloque_id
                inner join op_faculty fac on fac.id = c.idprofesor
where	pu.estado in ('REVISIONV','RECHAZADO');";
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    public function get_puds_aprobados($curso, $materia, $clase){
        $con = Yii::$app->db;
        $query = "select 	b.name as bloque 
		,m.name as materia
		,f.last_name
		,f.x_first_name
		,c.name as curso
		,p.name as paralelo
		,pud.titulo
		,pud.estado
		,pud.id as pud_id
from 	scholaris_plan_pud pud
		inner join scholaris_clase cla on cla.id = pud.clase_id
		inner join scholaris_bloque_actividad b on b.id = pud.bloque_id
		inner join scholaris_materia m on m.id = cla.idmateria
		inner join op_faculty f on f.id = cla.idprofesor
		inner join op_course c on c.id = cla.idcurso
		inner join op_course_paralelo p on p.id = cla.paralelo_id
where	cla.idcurso = $curso
		and cla.idmateria = $materia
		and pud.estado = 'APROBADO'
		and pud.pud_original is null
		--and pud.id not in (select pud_original from scholaris_plan_pud where clase_id = $clase)
order by b.orden;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }

}
