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
class SentenciasClaseLibreta extends \yii\db\ActiveRecord {
    
    
    public function get_materias($malla){
        $con = Yii::$app->db;
        $query = "select m.id
                                ,mat.name as materia
                                ,mat.abreviarura
                                ,m.promedia
                from	scholaris_malla_area a
                                inner join scholaris_malla_materia m on m.malla_area_id = a.id
                                inner join scholaris_materia mat on mat.id = m.materia_id
                where	a.malla_id = $malla
                                and m.tipo <> 'COMPORTAMIENTO'
                order by a.orden, m.orden;";
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    
    public function get_notas_finales_normales($materiaId, $alumno){
        $con = Yii::$app->db;
        $query = "select l.q1
                                    ,l.q2
                                    ,l.final_ano_normal
                    from 	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                    where	c.malla_materia = $materiaId
                                    and g.estudiante_id = $alumno;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res;
    }
    
    public function get_promedios_normales($alumno){
        $con = Yii::$app->db;
        $query = "select trunc(avg(l.q1),2) as q1
                                    ,trunc(avg(l.q2),2) as q2
                                    ,trunc(avg(l.final_ano_normal),2) as final_ano_normal
                    from 	scholaris_clase_libreta l
                                    inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
                                    inner join scholaris_clase c on c.id = g.clase_id
                                    inner join scholaris_malla_materia m on m.id = c.malla_materia
                    where	g.estudiante_id = $alumno
                                    and m.promedia = true;";
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res;
    }
    
    public function get_finales_mayor_a_menor($paralelo){
        $con = Yii::$app->db;
        $query = "select s.last_name, s.first_name, s.middle_name
		,trunc(avg(l.q1),2) as q1
		,trunc(avg(l.q2),2) as q2
		,trunc(avg(l.final_ano_normal),2) as final_ano_normal
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_malla_materia m on m.id = c.malla_materia
		inner join op_student s on s.id = g.estudiante_id
		inner join op_student_inscription i on i.student_id = s.id
where	i.parallel_id = $paralelo		
		and m.promedia = true
group by s.last_name, s.first_name, s.middle_name
order by trunc(avg(l.q1),2) desc;";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    
    public function get_comportamiento_finales($alumno, $paralelo){
        $con = Yii::$app->db;
        $query = "select l.p3
                         ,l.p6
                         ,c.id
                    from 	scholaris_clase c 
                                    inner join scholaris_materia m on  m.id = c.idmateria
                                    inner join scholaris_malla_materia mal on mal.materia_id = m.id
                                                            and mal.id = c.malla_materia
                                    inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id
                    where 	c.paralelo_id = $paralelo
                                    and mal.tipo = 'COMPORTAMIENTO'
                                    and g.estudiante_id = $alumno;";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res;
    }
    
    public function get_total_supletorios($alumno, $supletorio, $remedial){
        $con = Yii::$app->db;
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);
        
        
        $query = "select
                (
                        select count(l.final_ano_normal)
                        from	scholaris_clase c 
                                        inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
                                        inner join scholaris_clase_libreta l on l.grupo_id = g.id
                        where	c.periodo_scholaris = '$modelPeriodo->codigo'
                                                and g.estudiante_id = $alumno
                                                and c.promedia = 1
                                                and l.final_ano_normal < $supletorio
                                                and l.final_ano_normal > $remedial
                ) as supletorio
                ,(
                select count(l.final_ano_normal)
                        from	scholaris_clase c 
                                        inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
                                        inner join scholaris_clase_libreta l on l.grupo_id = g.id
                        where	c.periodo_scholaris = '$modelPeriodo->codigo'
                                                and g.estudiante_id = $alumno
                                                and c.promedia = 1
                                                and l.final_ano_normal < $remedial
                ) as remedial";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res;
    }

}
